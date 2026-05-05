<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Verification::with('user');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $verifications = $query->latest()->paginate(20)->withQueryString();
        return view('admin.verifications.index', compact('verifications'));
    }

    public function update(Request $request, Verification $verification)
    {
        $request->validate(['status' => 'required|in:pending,verified,rejected']);
        $verification->update(['status' => $request->status]);
        return back()->with('success', 'Status verifikasi diperbarui.');
    }
}
