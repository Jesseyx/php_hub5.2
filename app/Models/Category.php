<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Category extends Model
{
    // For admin log
    use RevisionableTrait;
    protected $keepRevisionOf = [
        'deleted_at',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [];

    /*
     * Define relationship
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
