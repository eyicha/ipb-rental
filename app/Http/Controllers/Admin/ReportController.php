<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'item', 'rental']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $reports = $query->latest()->paginate(20)->withQueryString();
        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['reporter', 'terlapor', 'item', 'rental.item']);
        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
{
    $validated = $request->validate([
'status' => 'required|in:pending,diproses,selesai,ditolak',        
'balasan_admin' => 'nullable|string|max:1000',
    ]);

    $report->update($validated);

    // ✅ Jika laporan keterlambatan selesai → unblock penyewa otomatis
    if (in_array($report->kategori, ['klarifikasi', 'keterlambatan'])) {
    $terlapor = \App\Models\User::find($report->terlapor_id);

    if ($validated['status'] === 'selesai' && $terlapor && $terlapor->is_blocked == true) {
        // Admin verifikasi → unblock user
        $terlapor->update(['is_blocked' => false, 'blocked_reason' => null]);
    \Log::info('terlapor_id: ' . $report->terlapor_id);
    \Log::info('terlapor found: ' . (\App\Models\User::find($report->terlapor_id) ? 'yes' : 'no'));

        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'rental_id' => $report->rental_id ?? null,
            'pesan'     => '[SISTEM] Akun kamu telah dipulihkan oleh admin. Kamu sudah bisa menyewa barang kembali!',
        ]);

    } elseif ($validated['status'] === 'ditolak' && $terlapor) {
        // Admin tolak → pastikan tetap blocked
        $terlapor->update(['is_blocked' => true]);
    }
}

    return back()->with('success', 'Laporan berhasil diperbarui.');
}
}