<?php

namespace App\Http\Controllers;

use App\Banner;
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
        $banners = Banner::allByPosition();

        return view('pages.home', compact('topics', 'banners'));
    }

    /*
     * Search page, using google's.
     */
    public function search(Request $request)
    {
        dd($request->input('q'));
    }
}
