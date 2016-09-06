<?php

namespace App\Http\ApiControllers;

use App\Transformers\NotificationTransformer;
use Auth;
use Authorizer;
use Response;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications();
        $user->notification_count = 0;
        $user->save();

        return $this->response()->paginator($notifications, new NotificationTransformer());
    }

    public function unreadMessagesCount()
    {
        $count = Auth::user()->notification_count;

        return response(compact('count'));
    }
}
