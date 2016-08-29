<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;

class CategoriesController extends Controller
{
    public function show(Request $request, $id, Topic $topic)
    {
        $category = Category::findOrFail($id);
        $topics = $topic->getCategoryTopicsWithFilter($request->get('filter'), $id);
        $banners = Banner::allByPosition();
        $links = Link::allFromCache();

        return view('topics.index', compact('topics', 'category', 'links', 'banners'));
    }
}
