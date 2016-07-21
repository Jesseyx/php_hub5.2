<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    public static function allByPosition()
    {
        $return = [];
        $data = Banner::orderBy('position', 'desc')
                        ->orderBy('order', 'asc')
                        ->get();

        foreach ($data as $banner) {
            $return[$banner->position][] = $banner;
        }

        return $return;
    }
}
