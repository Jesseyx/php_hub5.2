<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'is_banned'];

    /*
     * Define relationship
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /* protected $hidden = [
        'password', 'remember_token',
    ]; */

    public static function getByGithubId($id)
    {
        return self::where('github_id', '=', $id)->first();
    }

    /**
     * Cache github avatar to local
     */
    public function cacheAvatar()
    {
        // Download Image
        $guzzle = new Client();
        $response = $guzzle->get($this->image_url);

        // Get ext
        $content_type = explode('/', $response->getHeader('Content-Type')[0]);
    }
}
