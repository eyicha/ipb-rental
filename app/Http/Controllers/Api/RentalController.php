<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Rental;
use App\Models\Item;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RentalController extends Controller
{
    use ApiResponse;

    /**
     * Get user's rentals (both as penyewa and pemilik)
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $status = $request->get('status', null);

            // Get rentals as penyewa
            $asPenyewa = Rental::where('penyewa_id', $userId)
                ->with(['item', 'penyewa', 'pemilik']);

            // Get rentals as pemilik
            $asPemilik = Rental::where('pemilik_id', $userId)
                ->with(['item', 'penyewa', 'pemilik']);

            // Apply status filter if provided
            if ($status) {
                $asPenyewa->where('status', $status);
                $asPemilik->where('status', $status);
            }

            $asPenyewa = $asPenyewa->latest()->paginate($request->get('per_page', 10));
            $asPemilik = $asPemilik->latest()->paginate($request->get('per_page', 10));

            return $this->success([
                'as_penyewa' => [
                    'data' => $asPenyewa->items(),
                    'pagination' => [
                        'total' => $asPenyewa->total(),
                        'per_page' => $asPenyewa->perPage(),
                        'current_page' => $asPenyewa->currentPage(),
                        'last_page' => $asPenyewa->lastPage(),
                    ],
                ],
                'as_pemilik' => [
                    'data' => $asPemilik->items(),
                    'pagination' => [
                        'total' => $asPemilik->total(),
                        'per_page' => $asPemilik->perPage(),
                        'current_page' => $asPemilik->currentPage(),
                        'last_page' => $asPemilik->lastPage(),
                    ],
                ],
            ], 'Rentals retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve rentals', $e->getMessage(), 500);
        }
    }

    /**
     * Get single rental detail
     */
    public function show(Rental $rental)
    {
        try {
            $userId = Auth::id();

            if ($rental->penyewa_id !== $userId && $rental->pemilik_id !== $userId) {
                return $this->error('Unauthorized', null, 403);
            }

            $rental->load(['item', 'penyewa', 'pemilik', 'messages']);
            return $this->success($rental, 'Rental retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve rental', $e->getMessage(), 500);
        }
    }

    /**
     * Create new rental request
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_id' => 'required|exists:items,id',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after:tanggal_mulai',
                'catatan' => 'nullable|string|max:500',
            ]);

            $item = Item::findOrFail($validated['item_id']);

            // Check if user is trying to rent their own item
            if ($item->user_id === Auth::id()) {
                return $this->error('Cannot rent your own item', null, 400);
            }

            // Check stock
            if ($item->stok <= 0) {
                return $this->error('Item out of stock', null, 400);
            }

            $start = Carbon::parse($validated['tanggal_mulai']);
            $end = Carbon::parse($validated['tanggal_selesai']);
            $durasi = $start->diffInDays($end);
            $total = $durasi * $item->harga_per_hari;
            $deposit = $item->deposit;

            $rental = Rental::create([
                'item_id' => $item->id,
                'penyewa_id' => Auth::id(),
                'pemilik_id' => $item->user_id,
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'durasi_hari' => $durasi,
                'total_harga' => $total,
                'deposit' => $deposit,
                'catatan' => $validated['catatan'] ?? null,
                'status' => 'pending',
            ]);

            // Create notification message for owner
            Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $item->user_id,
                'rental_id' => $rental->id,
                'pesan' => Auth::user()->name . ' mengajukan permintaan sewa untuk item "' . $item->nama . '" mulai ' . $start->format('d M Y') . ' – ' . $end->format('d M Y') . '.',
            ]);

            $rental->load(['item', 'penyewa', 'pemilik']);
            return $this->success($rental, 'Rental request created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to create rental', $e->getMessage(), 500);
        }
    }

    /**
     * Update rental status (accept/decline/return)
     */
    public function action(Request $request, Rental $rental)
    {
        try {
            $userId = Auth::id();

            // Only pemilik can update rental
            if ($rental->pemilik_id !== $userId) {
                return $this->error('Unauthorized', null, 403);
            }

            $validated = $request->validate([
                'action' => 'required|in:accept,decline,returned',
                'bukti_dp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            ]);

            $action = $validated['action'];

            if ($action === 'accept') {
                if ($rental->status !== 'pending') {
                    return $this->error('Can only accept pending rentals', null, 400);
                }
                $rental->status = 'dp_paid';
            } elseif ($action === 'decline') {
                if ($rental->status !== 'pending') {
                    return $this->error('Can only decline pending rentals', null, 400);
                }
                $rental->status = 'declined';
            } elseif ($action === 'returned') {
                if ($rental->status !== 'active') {
                    return $this->error('Can only mark active rentals as returned', null, 400);
                }
                $rental->status = 'finished';
            }

            // Handle bukti_dp upload
            if ($request->hasFile('bukti_dp')) {
                $path = $request->file('bukti_dp')->store('bukti_dp', 'public');
                $rental->bukti_dp = $path;
            }

            $rental->save();
            $rental->load(['item', 'penyewa', 'pemilik']);

            return $this->success($rental, 'Rental updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to update rental', $e->getMessage(), 500);
        }
    }

    /**
     * Add review/rating to rental
     */
    public function review(Request $request, Rental $rental)
    {
        try {
            $userId = Auth::id();

            if ($rental->penyewa_id !== $userId) {
                return $this->error('Only penyewa can add review', null, 403);
            }

            if ($rental->status !== 'finished') {
                return $this->error('Can only review finished rentals', null, 400);
            }

            $validated = $request->validate([
                'rating' => 'required|integer|between:1,5',
                'ulasan' => 'required|string|max:500',
            ]);

            $rental->update($validated);

            return $this->success($rental, 'Review added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to add review', $e->getMessage(), 500);
        }
    }
}
