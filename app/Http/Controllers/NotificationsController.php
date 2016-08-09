<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class NotificationsController extends Controller
{
    /**
     * Display a listing of notifications
     *
     * @return Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        $notifications = $currentUser->notifications();

        $currentUser->notification_count = 0;
        $currentUser->save();

        return view('notifications.index', compact('notifications'));
    }

    public function count()
    {
        return Auth::user()->notification_count;
    }
}
