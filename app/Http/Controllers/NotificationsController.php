<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function markAsRead(Notification $notification)
    {
        abort_if($notification->user_id != auth()->id(), 403);

        $notification->update([
            'is_read' => 1
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}