<?php

namespace App\Models;

use App\Phphub\Presenters\SitePresenter;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Site extends Model
{
    use PresentableTrait;
    protected $presenter = SitePresenter::class;

    protected $guarded = ['id'];

    public static function allFromCache($expire = 1440)
    {
        $data = Cache::remember('phphub_sites', 60, function () {
            $raw_sites = self::orderBy('order', 'desc')->orderBy('created_at', 'desc')->get();
            $sorted = [];

            $sorted['site'] = $raw_sites->filter(function ($item) {
                return $item->type == 'site';
            });

            $sorted['blog'] = $raw_sites->filter(function ($item) {
                return $item->type == 'blog';
            });

            $sorted['weibo'] = $raw_sites->filter(function ($item) {
                return $item->type == 'weibo';
            });

            $sorted['dev_service'] = $raw_sites->filter(function ($item) {
                return $item->type == 'dev_service';
            });

            $sorted['site_foreign'] = $raw_sites->filter(function ($item) {
                return $item->type == 'site_foreign';
            });

            return $sorted;
        });

        return $data;
    }
}
