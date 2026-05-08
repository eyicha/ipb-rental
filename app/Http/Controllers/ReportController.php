<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $myReports = Report::where('reporter_id', Auth::id())
            ->latest()->get();

        $myRentals = Rental::where(function ($q) {
            $q->where('penyewa_id', Auth::id())->orWhere('pemilik_id', Auth::id());
        })->with('item')->latest()->get();

        return view('report.index', compact('myReports', 'myRentals'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
'kategori' => 'required|in:penipuan,barang_rusak,tidak_sesuai,keterlambatan,klarifikasi,lainnya',        'deskripsi'   => 'required|string',
        'terlapor_id' => 'nullable|exists:users,id',
        'rental_id'   => 'nullable|exists:rentals,id',
        'bukti.*'     => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
    ]);

    $buktis = [];
    if ($request->hasFile('bukti')) {
        foreach ($request->file('bukti') as $file) {
            $buktis[] = $file->store('reports', 'public');
        }
    }

    // ✅ Laporan keterlambatan dari user yang diblokir:
    //    terlapor_id = diri sendiri agar admin bisa unblock
    $terlapor_id = $validated['terlapor_id'] ?? null;
    if (in_array($validated['kategori'], ['keterlambatan', 'klarifikasi']) && !$terlapor_id) {
    $terlapor_id = Auth::id();
}
    Report::create([
        'reporter_id' => Auth::id(),
        'terlapor_id' => $terlapor_id,
        'rental_id'   => $validated['rental_id'] ?? null,
        'kategori'    => $validated['kategori'],
        'deskripsi'   => $validated['deskripsi'],
        'bukti'       => $buktis,
        'status'      => 'pending',
    ]);

    return redirect()->route('report.index')
        ->with('success', 'Laporan berhasil dikirim! Tim kami akan merespons dalam 1×24 jam.');
}
}
