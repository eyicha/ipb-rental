<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter','item','rental']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $reports = $query->latest()->paginate(20)->withQueryString();
        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['reporter','terlapor','item','rental.item']);
        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status'        => 'required|in:pending,diproses,selesai',
            'balasan_admin' => 'nullable|string|max:1000',
        ]);
        $report->update($validated);
        return back()->with('success', 'Laporan berhasil diperbarui.');
    }
}
