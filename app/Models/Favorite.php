<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [

    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public static function isUserFavoritedTopic(User $user, Topic $topic)
    {
        return Favorite::where('user_id', $user->id)
                        ->where('topic_id', $topic->id)
                        ->first();
    }
}
