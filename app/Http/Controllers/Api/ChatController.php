<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    use ApiResponse;

    /**
     * Get conversation list with latest message
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();

            $messages = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->orderByDesc('created_at')
            ->get();

            $latestByPartner = [];
            foreach ($messages as $message) {
                $partnerId = $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
                if (!isset($latestByPartner[$partnerId])) {
                    $latestByPartner[$partnerId] = $message;
                }
            }

            $conversationList = [];
            foreach ($latestByPartner as $partnerId => $message) {
                $partner = User::find($partnerId);
                if (!$partner) {
                    continue;
                }

                $unreadCount = Message::where('sender_id', $partnerId)
                    ->where('receiver_id', $userId)
                    ->where('is_read', false)
                    ->count();

                $conversationList[] = [
                    'partner' => $partner,
                    'lastMessage' => $message,
                    'unreadCount' => $unreadCount,
                ];
            }

            // Sort by latest message
            usort($conversationList, function ($a, $b) {
                return $b['lastMessage']->created_at <=> $a['lastMessage']->created_at;
            });

            return $this->success(
                array_values($conversationList),
                'Conversations retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve conversations', $e->getMessage(), 500);
        }
    }

    /**
     * Get messages with specific user
     */
    public function show(User $user, Request $request)
    {
        try {
            $userId = Auth::id();
            $perPage = $request->get('per_page', 20);

            // Get conversation messages
            $messages = Message::where(function ($q) use ($userId, $user) {
                $q->where(function ($subQ) use ($userId, $user) {
                    $subQ->where('sender_id', $userId)
                         ->where('receiver_id', $user->id);
                })
                ->orWhere(function ($subQ) use ($userId, $user) {
                    $subQ->where('sender_id', $user->id)
                         ->where('receiver_id', $userId);
                });
            })
            ->latest()
            ->paginate($perPage);

            // Mark messages from user as read
            Message::where('sender_id', $user->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return $this->paginated($messages, 'Messages retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve messages', $e->getMessage(), 500);
        }
    }

    /**
     * Send message to user
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'pesan' => 'required|string|max:500',
            ]);

            $userId = Auth::id();

            if ($userId === $validated['receiver_id']) {
                return $this->error('Cannot message yourself', null, 400);
            }

            $message = Message::create([
                'sender_id' => $userId,
                'receiver_id' => $validated['receiver_id'],
                'pesan' => $validated['pesan'],
                'is_read' => false,
            ]);

            $message->load('sender', 'receiver');

            return $this->success($message, 'Message sent successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->error('Failed to send message', $e->getMessage(), 500);
        }
    }

    /**
     * Mark message as read
     */
    public function markAsRead(Message $message)
    {
        try {
            if ($message->receiver_id !== Auth::id()) {
                return $this->error('Unauthorized', null, 403);
            }

            $message->is_read = true;
            $message->save();

            return $this->success($message, 'Message marked as read');
        } catch (\Exception $e) {
            return $this->error('Failed to mark message as read', $e->getMessage(), 500);
        }
    }

    /**
     * Mark all messages from user as read
     */
    public function markAllAsRead(User $user)
    {
        try {
            $userId = Auth::id();

            Message::where('sender_id', $user->id)
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return $this->success(null, 'All messages marked as read');
        } catch (\Exception $e) {
            return $this->error('Failed to mark messages as read', $e->getMessage(), 500);
        }
    }
}
