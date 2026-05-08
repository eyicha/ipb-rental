<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Rental;
use App\Models\Message;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $asPenyewa = Rental::where('penyewa_id', $userId)
            ->with(['item', 'penyewa', 'pemilik'])
            ->latest()->paginate(10, ['*'], 'penyewa_page')->withQueryString();

        $asPemilik = Rental::where('pemilik_id', $userId)
            ->with(['item', 'penyewa', 'pemilik'])
            ->latest()->paginate(10, ['*'], 'pemilik_page')->withQueryString();

        return view('rentals.index', compact('asPenyewa', 'asPemilik'));
    }

    public function store(Request $request)
{
    // 1. Cek blokir
    if (Auth::user()->is_blocked) {
        return back()->with('error',
            'Akun kamu diblokir karena terlambat mengembalikan barang. ' .
            'Silakan hubungi admin melalui menu Report untuk klarifikasi.'
        );
    }

    // 2. Validasi input
    $validated = $request->validate([
        'item_id'         => 'required|exists:items,id',
        'tanggal_mulai'   => 'required|date|after_or_equal:today',
        'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        'catatan'         => 'nullable|string|max:500',
    ]);

    $item = Item::findOrFail($validated['item_id']);

    // 3. Cek bukan item sendiri
    if ($item->user_id === Auth::id()) {
        return back()->with('error', 'Kamu tidak bisa menyewa item milikmu sendiri.');
    }

    // 4. Cek verifikasi KTM
    $ktmVerified = Auth::user()
        ->verifications()
        ->where('tipe', 'ktm')
        ->where('status', 'verified')
        ->exists();

    if (!$ktmVerified) {
        return back()->with('error', 'Kamu harus memverifikasi KTM terlebih dahulu sebelum menyewa. Upload KTM di halaman Profil.');
    }

    // 5. Hitung harga
    $start   = \Carbon\Carbon::parse($validated['tanggal_mulai']);
    $end     = \Carbon\Carbon::parse($validated['tanggal_selesai']);
    $durasi  = $start->diffInDays($end);
    $total   = $durasi * $item->harga_per_hari;
    $deposit = (int) round($total * 0.5);

    $rental = Rental::create([
        'item_id'         => $item->id,
        'penyewa_id'      => Auth::id(),
        'pemilik_id'      => $item->user_id,
        'tanggal_mulai'   => $validated['tanggal_mulai'],
        'tanggal_selesai' => $validated['tanggal_selesai'],
        'durasi_hari'     => $durasi,
        'total_harga'     => $total,
        'deposit'         => $deposit,
        'catatan'         => $validated['catatan'] ?? null,
        'status'          => 'pending',
    ]);

    Message::create([
        'sender_id'   => Auth::id(),
        'receiver_id' => $item->user_id,
        'rental_id'   => $rental->id,
        'pesan'       => Auth::user()->name . ' mengajukan permintaan sewa untuk item "' . $item->nama . '" mulai ' . $start->format('d M Y') . ' – ' . $end->format('d M Y') . '.',
    ]);

    return redirect()->route('rentals.index')->with('success', 'Permintaan sewa berhasil dikirim! Tunggu konfirmasi pemilik.');
}

    public function show(Rental $rental)
    {
        $this->authorizeRental($rental);
        $rental->load(['item', 'penyewa', 'pemilik']);
        return view('rentals.show', compact('rental'));
    }

    public function action(Request $request, Rental $rental)
    {
        $this->authorizeRental($rental);
        $action = $request->input('action');
        $userId = Auth::id();

        match ($action) {
            'accept'          => $this->acceptRental($rental, $userId),
            'reject'          => $this->rejectRental($rental, $userId),
            'deliver'         => $this->deliverRental($rental, $userId),
            'confirm_receive' => $this->confirmReceive($rental, $userId),
            'return_item'     => $this->returnItem($rental, $userId),
            'confirm_return'  => $this->confirmReturn($rental, $userId),
            'cancel'          => $this->cancelRental($rental, $userId),
            'rate'            => $this->rateRental($rental, $userId, $request),
            default           => abort(422, 'Aksi tidak dikenal.'),
        };

        return back()->with('success', 'Aksi berhasil dilakukan.');
    }

    // ═══════════════════════════════════════════════
    // PEMILIK: Terima permintaan → pending_payment
    // ═══════════════════════════════════════════════
    private function acceptRental(Rental $rental, $userId): void
    {
        abort_if($rental->pemilik_id !== $userId || $rental->status !== 'pending', 403);
        $rental->update(['status' => 'pending_payment']);
    }

    // ═══════════════════════════════════════════════
    // PEMILIK: Tolak permintaan → cancelled
    // ═══════════════════════════════════════════════
    private function rejectRental(Rental $rental, $userId): void
    {
        abort_if($rental->pemilik_id !== $userId || $rental->status !== 'pending', 403);
        $rental->update(['status' => 'cancelled']);
    }

    // ═══════════════════════════════════════════════
    // PEMILIK: Kirim barang → delivering
    // Alert kalau dikirim sebelum tanggal mulai
    // ═══════════════════════════════════════════════
    private function deliverRental(Rental $rental, $userId): void
    {
        abort_if($rental->pemilik_id !== $userId || $rental->status !== 'dp_paid', 403);

        $isBeforeStart = now()->lt(\Carbon\Carbon::parse($rental->tanggal_mulai)->startOfDay());

        $rental->update([
            'status'         => 'delivering',
            'early_delivery' => $isBeforeStart, // simpan flag untuk ditampilkan di blade
        ]);
    }

    // ═══════════════════════════════════════════════
    // PENYEWA: Konfirmasi terima barang → active
    // ═══════════════════════════════════════════════
    private function confirmReceive(Rental $rental, $userId): void
    {
        abort_if($rental->penyewa_id !== $userId || $rental->status !== 'delivering', 403);
        $rental->update(['status' => 'active']);
    }

    // ═══════════════════════════════════════════════
    // PENYEWA: Kembalikan barang → returning
    // Alert kalau dikembalikan sebelum tanggal selesai
    // ═══════════════════════════════════════════════
    private function returnItem(Rental $rental, $userId): void
    {
        abort_if($rental->penyewa_id !== $userId || $rental->status !== 'active', 403);
        $rental->update(['status' => 'returning']);
    }

    // ═══════════════════════════════════════════════
    // PEMILIK: Konfirmasi terima kembali → finished
    // Cek apakah terlambat → blokir penyewa + kirim pesan admin
    // ═══════════════════════════════════════════════
    private function confirmReturn(Rental $rental, $userId): void
    {
        abort_if($rental->pemilik_id !== $userId || $rental->status !== 'returning', 403);

        $tanggalSelesai = \Carbon\Carbon::parse($rental->tanggal_selesai)->endOfDay();
        $isLate         = now()->gt($tanggalSelesai);

        $rental->update([
            'status'      => 'finished',
            'returned_at' => now(),
            'is_late'     => $isLate,
        ]);

        $rental->item->increment('total_sewa');

        if ($isLate) {
            // ── Blokir penyewa ──
            $penyewa = User::find($rental->penyewa_id);
            $penyewa->update([
                'is_blocked'     => true,
                'blocked_reason' => 'Telat mengembalikan barang rental #' . $rental->id,
            ]);

            // ── Cari admin (user pertama dengan role admin) ──
            $admin = User::where('role', 'admin')->first();

            // ── Kirim pesan otomatis dari admin ke penyewa ──
            if ($admin) {
                Message::create([
                    'sender_id'   => $admin->id,
                    'receiver_id' => $penyewa->id,
                    'rental_id'   => $rental->id,
                    'pesan'       => '[SISTEM] Akun kamu telah diblokir sementara karena terlambat mengembalikan barang "' .
                                     $rental->item->nama . '" (Rental #' . $rental->id . '). ' .
                                     'Untuk membuka kembali akun, buat laporan di menu Report → kategori "Pengembalian Terlambat" dan jelaskan kronologinya. ' .
                                     'Admin akan memverifikasi dan memulihkan aksesmu.',
                ]);
            }

            // ── Buat Report otomatis atas nama sistem ──
            Report::create([
                'reporter_id' => $rental->pemilik_id,
                'terlapor_id' => $rental->penyewa_id,
                'item_id'     => $rental->item_id,
                'rental_id'   => $rental->id,
                'kategori'    => 'keterlambatan',
                'deskripsi'   => 'Penyewa ' . $penyewa->name . ' terlambat mengembalikan barang "' .
                                 $rental->item->nama . '". ' .
                                 'Tanggal selesai: ' . $tanggalSelesai->format('d M Y') . '. ' .
                                 'Barang dikembalikan pada: ' . now()->format('d M Y H:i') . '.',
                'bukti'       => [],
                'status'      => 'diproses',
            ]);
        }
    }

    // ═══════════════════════════════════════════════
    // PENYEWA: Batalkan → cancelled (hanya saat pending)
    // ═══════════════════════════════════════════════
    private function cancelRental(Rental $rental, $userId): void
    {
        abort_if($rental->penyewa_id !== $userId || $rental->status !== 'pending', 403);
        $rental->update(['status' => 'cancelled']);
    }

    // ═══════════════════════════════════════════════
    // PENYEWA: Isi rating (setelah finished)
    // ═══════════════════════════════════════════════
    private function rateRental(Rental $rental, $userId, Request $request): void
    {
        abort_if(
            $rental->penyewa_id !== $userId ||
            $rental->status !== 'finished' ||
            $rental->rating !== null,
            403
        );

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'ulasan' => 'nullable|string|max:500',
        ]);

        $rental->update([
            'rating' => (int) $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        // Update rating item
        $item    = $rental->item;
        $avgItem = Rental::where('item_id', $item->id)->whereNotNull('rating')->avg('rating');
        $item->update(['rating_avg' => round($avgItem, 1)]);

        // Update rating pemilik
        $pemilik    = $rental->pemilik;
        $avgPemilik = Rental::where('pemilik_id', $pemilik->id)->whereNotNull('rating')->avg('rating');
        $pemilik->update(['rating_avg' => round($avgPemilik, 1)]);
    }

    private function authorizeRental(Rental $rental): void
    {
        $userId = Auth::id();
        if ($rental->penyewa_id !== $userId && $rental->pemilik_id !== $userId) {
            abort(403);
        }
    }
}