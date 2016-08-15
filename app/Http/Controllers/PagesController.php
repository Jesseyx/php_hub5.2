<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use Purifier;
use Rss;

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

    /**
     * Feed function
     */
    public function feed()
    {
        $topics = Topic::excellent()->recent()->limit(20)->get();

        $channel =[
            'title'       => 'PHPHub - PHP & Laravel的中文社区',
            'description' => 'PHPHub是 PHP 和 Laravel 的中文社区，在这里我们讨论技术, 分享技术。',
            'link'        => url(route('feed')),
        ];

        $feed = Rss::feed('2.0', 'UTF-8');

        $feed->channel($channel);

        foreach ($topics as $topic) {
            $feed->item([
                'title'             => $topic->title,
                'description|cdata' => str_limit($topic->body, 200),
                'link'              => url(route('topics.show', $topic->id)),
                'pubDate'           => date('Y-m-d', strtotime($topic->created_at)),
            ]);
        }

        return response($feed, 200, array('Content-Type' => 'text/xml'));
    }

    /**
     * Sitemap function
     */
    public function sitemap()
    {
        return app('App\Phphub\Sitemap\Builder')->render();
    }
}
