<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;

class PagesController extends Controller
{
    /*
     * The home page
     */
    public function home(Topic $topic)
    {
        $topics =$topic->getTopicsWithFilter('excellent');

        return view('pages.home', compact('topics'));
    }
}
