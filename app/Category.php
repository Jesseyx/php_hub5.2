<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [];

    /*
     * Define relationship
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
