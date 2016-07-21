<?php

namespace App\Http\Controllers;

use App\Phphub\Core\CreatorListener;
use Illuminate\Http\Request;

use App\Http\Requests;

class RepliesController extends Controller implements CreatorListener
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Requests\StoreReplyRequest $request)
    {
        dd('sdasda');
    }

    /**
     * ----------------------------------------
     * CreatorListener Delegate
     * ----------------------------------------
     */
    public function creatorFailed($errors)
    {
        // TODO: Implement creatorFailed() method.
    }

    public function creatorSucceed($model)
    {
        // TODO: Implement creatorSucceed() method.
    }
}
