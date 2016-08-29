<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Site;
use Illuminate\Http\Request;

use App\Http\Requests;

class SitesController extends Controller
{
    public function index()
    {
        $sites = Site::allFromCache();
        $banners = Banner::allByPosition();

        return view('sites.index', compact('sites', 'banners'));
    }
}
