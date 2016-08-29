<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class ActiveUser extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function fetchAll()
    {
        $data = Cache::remember('phphub_active_users', 30, function () {
            // pluck方法为给定键获取所有集合值, 就是最后只取 user 列的值
            return self::with('user')
                        ->orderBy('weight', 'desc')
                        ->limit(8)
                        ->get()
                        ->pluck('user');
        });

        return $data;
    }
}
