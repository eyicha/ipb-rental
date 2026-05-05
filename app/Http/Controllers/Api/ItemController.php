<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    use ApiResponse;

    /**
     * Get all active items with filters and pagination
     */
    public function index(Request $request)
    {
        try {
            $query = Item::where('status', 'aktif')->with('owner');

            // Search filter
            if ($request->filled('q')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->q . '%')
                      ->orWhere('deskripsi', 'like', '%' . $request->q . '%');
                });
            }

            // Category filter
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Sorting
            $sort = $request->get('sort', 'popular');
            switch ($sort) {
                case 'harga_asc':
                    $query->orderBy('harga_per_hari');
                    break;
                case 'harga_desc':
                    $query->orderByDesc('harga_per_hari');
                    break;
                case 'rating':
                    $query->orderByDesc('rating_avg');
                    break;
                case 'terbaru':
                    $query->latest();
                    break;
                default:
                    $query->orderByDesc('total_sewa');
            }

            $items = $query->paginate($request->get('per_page', 12));

            return $this->paginated($items, 'Items retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve items', $e->getMessage(), 500);
        }
    }

    /**
     * Get single item detail
     */
    public function show(Item $item)
    {
        try {
            if ($item->status === 'nonaktif') {
                return $this->error('Item not found', null, 404);
            }

            $item->load('owner');
            return $this->success($item, 'Item retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve item', $e->getMessage(), 500);
        }
    }

    /**
     * Get categories list
     */
    public function categories()
    {
        $categories = [
            'elektronik',
            'fotografi',
            'audio',
            'drone',
            'akademik',
            'olahraga',
            'perabot',
            'kendaraan',
            'lainnya'
        ];

        return $this->success($categories, 'Categories retrieved successfully');
    }

    /**
     * Create new item (user's item)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'kategori' => 'required|in:elektronik,fotografi,audio,drone,akademik,olahraga,perabot,kendaraan,lainnya',
                'harga_per_hari' => 'required|numeric|min:1000',
                'deposit' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:1',
                'foto' => 'nullable|array',
                'foto.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $fotoArray = [];
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $foto) {
                    $path = $foto->store('items', 'public');
                    $fotoArray[] = $path;
                }
            }

            $item = Item::create([
                'user_id' => Auth::id(),
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'kategori' => $validated['kategori'],
                'harga_per_hari' => $validated['harga_per_hari'],
                'deposit' => $validated['deposit'],
                'stok' => $validated['stok'],
                'foto' => $fotoArray,
                'status' => 'aktif',
                'rating_avg' => 0,
                'total_sewa' => 0,
            ]);

            return $this->success($item, 'Item created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to create item', $e->getMessage(), 500);
        }
    }

    /**
     * Update item
     */
    public function update(Request $request, Item $item)
    {
        try {
            // Check authorization
            if ($item->user_id !== Auth::id()) {
                return $this->error('Unauthorized', null, 403);
            }

            $validated = $request->validate([
                'nama' => 'sometimes|string|max:255',
                'deskripsi' => 'sometimes|string',
                'kategori' => 'sometimes|in:elektronik,fotografi,audio,drone,akademik,olahraga,perabot,kendaraan,lainnya',
                'harga_per_hari' => 'sometimes|numeric|min:1000',
                'deposit' => 'sometimes|numeric|min:0',
                'stok' => 'sometimes|integer|min:1',
                'foto' => 'nullable|array',
                'foto.*' => 'file|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle foto update
            if ($request->hasFile('foto')) {
                // Delete old photos
                if ($item->foto) {
                    foreach ($item->foto as $foto) {
                        Storage::disk('public')->delete($foto);
                    }
                }

                // Upload new photos
                $fotoArray = [];
                foreach ($request->file('foto') as $foto) {
                    $path = $foto->store('items', 'public');
                    $fotoArray[] = $path;
                }
                $validated['foto'] = $fotoArray;
            }

            $item->update($validated);

            return $this->success($item, 'Item updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to update item', $e->getMessage(), 500);
        }
    }

    /**
     * Delete item
     */
    public function destroy(Item $item)
    {
        try {
            // Check authorization
            if ($item->user_id !== Auth::id()) {
                return $this->error('Unauthorized', null, 403);
            }

            // Delete photos
            if ($item->foto) {
                foreach ($item->foto as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }

            $item->delete();

            return $this->success(null, 'Item deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete item', $e->getMessage(), 500);
        }
    }

    /**
     * Toggle item status
     */
    public function toggleStatus(Request $request, Item $item)
    {
        try {
            if ($item->user_id !== Auth::id()) {
                return $this->error('Unauthorized', null, 403);
            }

            $item->status = $item->status === 'aktif' ? 'nonaktif' : 'aktif';
            $item->save();

            return $this->success($item, 'Item status updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update item status', $e->getMessage(), 500);
        }
    }
}
