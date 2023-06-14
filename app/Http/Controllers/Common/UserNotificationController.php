<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function index(Request $request) {
        if($request->wantsJson()) {
            return auth()->user()->notifications()->latest()->paginate(2);
        }
    }

    public function indexAdmin()
    {
        return inertia('Admin/Notification/Index', [
            'notifications' => auth()->user()->notifications()->latest()->paginate(),
        ]);
    }

    public function indexLawyer()
    {

    }
}
