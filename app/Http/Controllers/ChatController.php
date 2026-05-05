<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get unique conversation partners with latest message
        $messages = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->orderByDesc('created_at')
            ->get();

        $latestByPartner = [];
        foreach ($messages as $message) {
            $partnerId = $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            if (! isset($latestByPartner[$partnerId])) {
                $latestByPartner[$partnerId] = $message;
            }
        }

        $conversationList = [];
        foreach ($latestByPartner as $partnerId => $message) {
            $partner = User::find($partnerId);
            if (! $partner) {
                continue;
            }

            $unreadCount = Message::where('sender_id', $partnerId)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();

            $conversationList[] = [
                'partner'     => $partner,
                'lastMessage' => $message,
                'unreadCount' => $unreadCount,
            ];
        }

        // Active chat
        $activePartner = null;
        $messages      = collect();
        if ($request->filled('with')) {
            $activePartner = User::find($request->with);
            if ($activePartner) {
                $messages = Message::where(function ($q) use ($userId, $activePartner) {
                    $q->where('sender_id', $userId)->where('receiver_id', $activePartner->id);
                })->orWhere(function ($q) use ($userId, $activePartner) {
                    $q->where('sender_id', $activePartner->id)->where('receiver_id', $userId);
                })->orderBy('created_at')->get();

                // Mark as read
                Message::where('sender_id', $activePartner->id)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        $conversations = $conversationList;
        return view('chat.index', compact('conversations', 'activePartner', 'messages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'pesan'       => 'required|string|max:1000',
            'rental_id'   => 'nullable|exists:rentals,id',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'rental_id'   => $validated['rental_id'] ?? null,
            'pesan'       => $validated['pesan'],
        ]);

        return redirect()->route('chat.index', ['with' => $validated['receiver_id']]);
    }
}
