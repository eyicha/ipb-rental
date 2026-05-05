<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use ApiResponse;

    /**
     * Get user's reports
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $status = $request->get('status', null);

            $query = Report::where('reporter_id', $userId)
                ->with(['reporter', 'terlapor', 'item', 'rental']);

            if ($status) {
                $query->where('status', $status);
            }

            $reports = $query->latest()->paginate($request->get('per_page', 10));

            return $this->paginated($reports, 'Reports retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve reports', $e->getMessage(), 500);
        }
    }

    /**
     * Get single report detail
     */
    public function show(Report $report)
    {
        try {
            if ($report->reporter_id !== Auth::id()) {
                return $this->error('Unauthorized', null, 403);
            }

            $report->load(['reporter', 'terlapor', 'item', 'rental']);
            return $this->success($report, 'Report retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve report', $e->getMessage(), 500);
        }
    }

    /**
     * Create new report
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'terlapor_id' => 'required|exists:users,id',
                'item_id' => 'nullable|exists:items,id',
                'rental_id' => 'nullable|exists:rentals,id',
                'kategori' => 'required|in:penipuan,barang_rusak,barang_hilang,perilaku_tidak_baik,lainnya',
                'deskripsi' => 'required|string|max:1000',
                'bukti' => 'nullable|array',
                'bukti.*' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);

            $userId = Auth::id();

            // Cannot report yourself
            if ($userId === $validated['terlapor_id']) {
                return $this->error('Cannot report yourself', null, 400);
            }

            $buktiArray = [];
            if ($request->hasFile('bukti')) {
                foreach ($request->file('bukti') as $bukti) {
                    $path = $bukti->store('reports', 'public');
                    $buktiArray[] = $path;
                }
            }

            $report = Report::create([
                'reporter_id' => $userId,
                'terlapor_id' => $validated['terlapor_id'],
                'item_id' => $validated['item_id'] ?? null,
                'rental_id' => $validated['rental_id'] ?? null,
                'kategori' => $validated['kategori'],
                'deskripsi' => $validated['deskripsi'],
                'bukti' => $buktiArray,
                'status' => 'pending',
            ]);

            $report->load(['reporter', 'terlapor', 'item', 'rental']);
            return $this->success($report, 'Report created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to create report', $e->getMessage(), 500);
        }
    }

    /**
     * Admin: Get all reports
     */
    public function adminIndex(Request $request)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return $this->error('Unauthorized', null, 403);
            }

            $status = $request->get('status', null);

            $query = Report::with(['reporter', 'terlapor', 'item', 'rental']);

            if ($status) {
                $query->where('status', $status);
            }

            $reports = $query->latest()->paginate($request->get('per_page', 15));

            return $this->paginated($reports, 'Reports retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve reports', $e->getMessage(), 500);
        }
    }

    /**
     * Admin: Update report status and add response
     */
    public function adminUpdate(Request $request, Report $report)
    {
        try {
            if (Auth::user()->role !== 'admin') {
                return $this->error('Unauthorized', null, 403);
            }

            $validated = $request->validate([
                'status' => 'required|in:pending,diproses,selesai,ditolak',
                'balasan_admin' => 'nullable|string|max:1000',
            ]);

            $report->update($validated);

            $report->load(['reporter', 'terlapor', 'item', 'rental']);
            return $this->success($report, 'Report updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to update report', $e->getMessage(), 500);
        }
    }
}
