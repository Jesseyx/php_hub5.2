<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Category;
use App\Link;
use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;

class CategoriesController extends Controller
{
    public function show($id, Topic $topic)
    {
        $category = Category::findOrFail($id);
        $filter = $topic->present()->getTopicFilter();
        $topics = $topic->getCategoryTopicsWithFilter($filter, $id);
        $banners = Banner::allByPosition();
        $links = Link::allFromCache();

        return view('topics.index', compact('topics', 'category', 'banners', 'links'));
    }
}
