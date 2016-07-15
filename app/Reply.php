<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /*
     * Define relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
