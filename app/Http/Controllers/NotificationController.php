<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->with([
                'sender:id,username,full_name,email,phone,avatar_url',
                'post:id,post_type,name,image_url,status',
            ])
            ->latest()
            ->get();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, int $id)
    {
        $notification = Notification::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $notification->update([
            'is_read' => true,
        ]);

        return response()->json([
            'message' => 'Notification marked as read.',
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
            ]);

        return response()->json([
            'message' => 'All notifications marked as read.',
        ]);
    }

   public function updateStatus(Request $request, int $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:accepted,rejected,ACCEPTED,REJECTED',
            ]);

            $notification = Notification::where('user_id', $request->user()->id)->findOrFail($id);
            $statusUpper = strtoupper($request->status);

            if ($notification->post_id && $notification->sender_id) {
                \App\Models\RescueRequest::where('post_id', $notification->post_id)
                    ->where('user_id', $notification->sender_id)
                    ->update(['status' => $statusUpper]);

                \App\Models\AdoptionRequest::where('post_id', $notification->post_id)
                    ->where('user_id', $notification->sender_id)
                    ->update(['status' => $statusUpper]);
            }

            if ($statusUpper === 'ACCEPTED' && $notification->post_id) {
                \App\Models\Post::where('id', $notification->post_id)->update(['status' => 'CLOSED']);
            }

            if (!empty($notification->sender_id) && $notification->sender_id != $request->user()->id) {
                $actionText = ($statusUpper === 'ACCEPTED') ? 'accepted' : 'declined';

                Notification::create([
                    'user_id'   => $notification->sender_id,
                    'sender_id' => $request->user()->id,
                    'post_id'   => $notification->post_id,
                    'type'      => $notification->type,
                    'message'   => $actionText . ' your request.',
                    'is_read'   => false,
                ]);
            }

            $notification->update(['is_read' => true]);

            return response()->json([
                'message' => 'Status updated successfully.',
                'notification' => $notification->load('post'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update status',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
