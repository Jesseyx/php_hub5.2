<?php

namespace App\Models;

use App\Models\Traits\TopicApiHelper;
use App\Models\Traits\TopicFilterable;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Naux\AutoCorrect;
use Venturecraft\Revisionable\RevisionableTrait;

class Topic extends Model
{

    use PresentableTrait;
    protected $presenter = 'App\Phphub\Presenters\TopicPresenter';

    // For admin log
    use RevisionableTrait;
    protected $keepRevisionOf = [
        'deleted_at',
        'is_excellent',
        'is_blocked',
        'order',
    ];

    // Don't forget to fill this array
    protected $fillable = [
        'title',
        'body',
        'excerpt',
        'source',
        'body_original',
        'user_id',
        'category_id',
        'created_at',
        'updated_at'
    ];

    // manually maintian 手动维护
    public $timestamps = false;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // for filter
    use TopicFilterable, TopicApiHelper;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function ($topic) {
            SiteStatus::newTopic();
        });
    }

    /*
     * Define relationship
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function lastReplyUser()
    {
        return $this->belongsTo(User::class, 'last_reply_user_id');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function votedBy()
    {
        $user_ids = Vote::where('votable_type', 'App\Models\Topic')
                        ->where('votable_id', $this->id)
                        ->where('is', 'upvote')
                        ->lists('user_id')
                        ->toArray();

        return User::whereIn('id', $user_ids)->get();
    }

    public function appends()
    {
        return $this->hasMany(Append::class);
    }

    public function attentedBy()
    {
        return $this->belongsToMany(User::class, 'attentions');
    }

    /*
     * getRepliesWithLimit
     */
    public function getRepliesWithLimit($limit = 30)
    {
        $pageName = 'page';

        // Default display the latest reply
        $latest_page = is_null(request($pageName)) ? ceil($this->reply_count / $limit) : 1;

        return $this->replies()
            ->orderBy('created_at', 'asc')
            ->with('user')
            ->paginate($limit, ['*'], $pageName, $latest_page);
    }

    public function getRandomExcellent()
    {
        $data = Cache::remember('phphub_hot_topics', 10, function(){
            $topic = new Topic;
            return $topic->getTopicsWithFilter('random-excellent', 5);
        });

        return $data;
    }

    /*
     * getSameCategoryTopics
     */
    public function getSameCategoryTopics($limit = 8)
    {
        $data = Cache::remember('phphub_category_topics', 30, function () use ($limit) {
            return Topic::where('category_id', '=', $this->category_id)
                ->recent()
                ->with('user')
                ->take($limit)
                ->get();
        });

        return $data;
    }

    // 删除评论时，重新生成最后回复
    public function generateLastReplyUserInfo($user_id)
    {
        $lastReply = $this->replies()->recent()->first();

        if ($this->last_reply_user_id != $user_id) return;
        
        $this->last_reply_user_id = $lastReply ? $lastReply->user_id : 0;
        $this->save();
    }

    /*
     * Scope
     */
    public function scopeExcellent($query)
    {
        return $query->where('is_excellent', '=', 'yes');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeWhose($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id)->with('category');
    }

    public function scopeByWhom($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeRecentReply($query)
    {
        return $query->pinned()
                     ->orderBy('updated_at', 'desc');
    }

    public function scopePinAndRecentReply($query)
    {
        return $query->fresh()
                     ->pinned()
                     ->orderBy('updated_at', 'desc');
    }

    public function scopePinned($query)
    {
        return $query->orderBy('order', 'desc');
    }

    public function scopeWithoutBlocked($query)
    {
        return $query->where('is_blocked', '=', 'no');
    }

    public function scopeFresh($query)
    {
        // 当前时间一个月内，或者排序大于 0 的
        return $query->whereRaw("(`created_at` > '" . Carbon::today()->subMonths(3)->toDateString() . "' or (`order` > 0) )");
    }

    public function scopeRandom($query)
    {
        return $query->orderByRaw("RAND()");
    }

    /*
     * Attribute
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = (new AutoCorrect)->convert($value);
    }

    // static
    public static function makeExcerpt($body)
    {
        $html = $body;
        // 去除 html 标记
        $excerpt = trim(preg_replace('/\s\s+/', ' ', strip_tags($html)));

        return str_limit($excerpt, 200);
    }
}
