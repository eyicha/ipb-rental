<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->items()->withTrashed(false);

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total'     => Auth::user()->items()->count(),
            'aktif'     => Auth::user()->items()->where('status', 'aktif')->count(),
            'nonaktif'  => Auth::user()->items()->where('status', 'nonaktif')->count(),
            'disewa'    => Auth::user()->rentalsAsPemilik()->whereIn('status', ['active','dp_paid'])->count(),
        ];

        $categories = ['elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya'];

        return view('my-items.index', compact('items', 'stats', 'categories'));
    }

    public function create()
    {
        $categories = ['elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya'];
        return view('my-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'kategori'      => 'required|in:elektronik,fotografi,audio,drone,akademik,olahraga,perabot,kendaraan,lainnya',
            'harga_per_hari'=> 'required|integer|min:1000',
            'deposit'       => 'nullable|integer|min:0',
            'stok'          => 'required|integer|min:1',
            'foto.*'        => 'nullable|image|max:5120',
        ]);

        $fotos = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotos[] = $file->store('items', 'public');
            }
        }

        Auth::user()->items()->create([
            'nama'           => $validated['nama'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'kategori'       => $validated['kategori'],
            'harga_per_hari' => $validated['harga_per_hari'],
            'deposit'        => $validated['deposit'] ?? 0,
            'stok'           => $validated['stok'],
            'foto'           => $fotos,
            'status'         => 'aktif',
        ]);

        return redirect()->route('my-items.index')->with('success', 'Item berhasil ditambahkan!');
    }

    public function edit(Item $myItem)
    {
        $this->authorizeItem($myItem);
        $categories = ['elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya'];
        return view('my-items.edit', compact('myItem', 'categories'));
    }

    public function update(Request $request, Item $myItem)
    {
        $this->authorizeItem($myItem);

        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'kategori'       => 'required|in:elektronik,fotografi,audio,drone,akademik,olahraga,perabot,kendaraan,lainnya',
            'harga_per_hari' => 'required|integer|min:1000',
            'deposit'        => 'nullable|integer|min:0',
            'stok'           => 'required|integer|min:1',
            'foto.*'         => 'nullable|image|max:5120',
        ]);

        $fotos = $myItem->foto ?? [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotos[] = $file->store('items', 'public');
            }
        }

        $myItem->update([
            'nama'           => $validated['nama'],
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'kategori'       => $validated['kategori'],
            'harga_per_hari' => $validated['harga_per_hari'],
            'deposit'        => $validated['deposit'] ?? 0,
            'stok'           => $validated['stok'],
            'foto'           => $fotos,
        ]);

        return redirect()->route('my-items.index')->with('success', 'Item berhasil diperbarui!');
    }

    public function destroy(Item $myItem)
    {
        $this->authorizeItem($myItem);
        $myItem->delete();
        return redirect()->route('my-items.index')->with('success', 'Item berhasil dihapus.');
    }

    public function toggleStatus(Item $myItem)
    {
        $this->authorizeItem($myItem);
        $myItem->update([
            'status' => $myItem->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);
        return back()->with('success', 'Status item diperbarui.');
    }

    private function authorizeItem(Item $item): void
    {
        if ($item->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
