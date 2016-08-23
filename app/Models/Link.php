<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Link extends Model
{
    /**
     *  友情链接，分类和文章首页用到
     */
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // For admin log
    use RevisionableTrait;
    protected $keepRevisionOf = [
        'deleted_at',
    ];

    public static function allFromCache($expire = 1440)
    {
        $cache_name = 'links';

        return Cache::remember($cache_name, $expire, function () {
            return self::all();
        });
    }
}
