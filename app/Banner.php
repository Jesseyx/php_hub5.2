<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Banner extends Model
{
    // For admin log
    use RevisionableTrait;
    protected $keepRevisionOf = [
        'deleted_at',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
