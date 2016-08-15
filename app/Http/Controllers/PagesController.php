<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Purifier;

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
        $query = Purifier::clean($request->input('q'), 'search_q');
        return redirect()->away('https://www.bing.com/search?q=site:localhost:8000 ' . $query, 301);
    }

    /**
     * About us page
     */
    public function about()
    {
        return view('pages.about');
    }
}
