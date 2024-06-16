<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function index(Request $request) {
        if($request->wantsJson()) {
            return auth()->user()
                    ->notifications()
                    ->latest()
                    ->paginate(5)
                    ->through(fn($notification) => [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'data' => $notification->data,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at->diffForHumans(),
                        
                    ]);
        }
    }

    public function markAllAsRead()
    {
        try {
            //throw new Exception();
            auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        } catch (\Exception $e) {
            abort('500');
        }
    }
}
