<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attention extends Model
{
    protected $fillable = [

    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public static function isUserAttentedTopic(User $user, Topic $topic)
    {
        return Attention::where('user_id', $user->id)
                        ->where('topic_id', $topic->id)
                        ->first();
    }
}
