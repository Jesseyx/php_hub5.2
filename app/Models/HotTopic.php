<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class HotTopic extends Model
{
    protected $guarded = ['id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function fetchAll()
    {
        $data = Cache::remember('phphub_hot_topics', 30, function () {
            return self::orderBy('weight', 'desc')
                        ->with('topic', 'topic.user')
                        ->limit(10)
                        ->get()
                        ->pluck('topic');
        });

        return $data;
    }
}
