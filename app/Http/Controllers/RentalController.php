<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Rental;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'item_id'         => 'required|exists:items,id',
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'catatan'         => 'nullable|string|max:500',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa menyewa item milikmu sendiri.');
        }

    // Cek verifikasi KTM
$ktmVerified = Auth::user()
    ->verifications()
    ->where('tipe', 'ktm')
    ->where('status', 'verified')
    ->exists();

if (!$ktmVerified) {
    return back()->with('error', 'Kamu harus memverifikasi KTM terlebih dahulu sebelum menyewa. Upload KTM di halaman Profil.');
}

        $start    = \Carbon\Carbon::parse($validated['tanggal_mulai']);
        $end      = \Carbon\Carbon::parse($validated['tanggal_selesai']);
        $durasi   = $start->diffInDays($end);
        $total    = $durasi * $item->harga_per_hari;
        $deposit  = $item->deposit;

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

        // Notify owner via system message
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
        $rental->load(['item','penyewa','pemilik']);
        return view('rentals.show', compact('rental'));
    }

    public function action(Request $request, Rental $rental)
    {
        $this->authorizeRental($rental);
        $action = $request->input('action');
        $userId = Auth::id();

        match ($action) {
    'accept'      => $this->acceptRental($rental, $userId),
    'reject'      => $this->rejectRental($rental, $userId),
    'deliver'     => $this->deliverRental($rental, $userId),
    'return_item' => $this->returnRental($rental, $userId),
    'cancel'      => $this->cancelRental($rental, $userId),
    'rate'        => $this->rateRental($rental, $userId, $request),
    default       => abort(422, 'Aksi tidak dikenal.'),
};

        return back()->with('success', 'Aksi berhasil dilakukan.');
    }

    private function acceptRental(Rental $rental, $userId): void
    {
        abort_if($rental->pemilik_id !== $userId || $rental->status !== 'pending', 403);
        $rental->update(['status' => 'pending_payment']);
    }

    private function rejectRental(Rental $rental, $userId): void
    {
        abort_if($rental->pemilik_id !== $userId || $rental->status !== 'pending', 403);
        $rental->update(['status' => 'cancelled']);
    }


    private function deliverRental(Rental $rental, $userId): void
{
    abort_if($rental->pemilik_id !== $userId || $rental->status !== 'dp_paid', 403);
    $rental->update(['status' => 'active']);
    // hapus increment di sini
}

private function returnRental(Rental $rental, $userId): void
{
    abort_if($rental->pemilik_id !== $userId || $rental->status !== 'active', 403);
    $rental->update(['status' => 'finished']);
    $rental->item->increment('total_sewa'); // ← pindah ke sini
}

    private function cancelRental(Rental $rental, $userId): void
    {
        abort_if($rental->penyewa_id !== $userId || !in_array($rental->status, ['pending']), 403);
        $rental->update(['status' => 'cancelled']);
    }

    private function rateRental(Rental $rental, $userId, Request $request): void
{
    abort_if($rental->penyewa_id !== $userId || $rental->status !== 'finished' || $rental->rating !== null, 403);
    $request->validate(['rating' => 'required|integer|between:1,5', 'ulasan' => 'nullable|string|max:500']);
    $rental->update(['rating' => (int) $request->rating, 'ulasan' => $request->ulasan]);

    // Update rating item
    $item = $rental->item;
    $avgItem = Rental::where('item_id', $item->id)->whereNotNull('rating')->avg('rating');
    $item->update(['rating_avg' => round($avgItem, 1)]);

    // ← TAMBAHKAN INI: Update rating pemilik
    $pemilik = $rental->pemilik;
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
