<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    /**
     * 右侧边栏小贴士
     */

    protected $fillable = ['body'];

    const CACHE_KEY = 'site_tips';
    const CACHE_MINUTES = 1440;

    /**
     * 返回随机数据项
     */
    public static function getRandomTip()
    {
        $tips = Cache::remember(self::CACHE_KEY, self::CACHE_MINUTES, function () {
            return Tip::all();
        });

        // 没有参数，返回一个
        return $tips->random();
    }
}
