<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('owner');
        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $items = $query->latest()->paginate(20)->withQueryString();
        return view('admin.items.index', compact('items'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate(['status' => 'required|in:aktif,nonaktif,habis']);
        $item->update(['status' => $request->status]);
        return back()->with('success', 'Status item diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }
}
