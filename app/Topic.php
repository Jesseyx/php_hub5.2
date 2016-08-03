<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;

class Topic extends Model
{

    use PresentableTrait;
    protected $presenter = 'App\Phphub\Presenters\TopicPresenter';

    // Don't forget to fill this array
    protected $fillable = [
        'title',
        'body',
        'excerpt',
        'body_original',
        'user_id',
        'category_id',
        'created_at',
        'updated_at'
    ];

    // manually maintian 手动维护
    public $timestamps = false;

    use SoftDeletes;

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

    /*
     * getTopicsWithFilter
     */
    public function getTopicsWithFilter($filter, $limit = 20)
    {
        return $this->applyFilter($filter)
                    ->with('user', 'category', 'lastReplyUser')
                    ->paginate($limit);
    }

    /*
     * getCategoryTopicsWithFilter
     */
    public function getCategoryTopicsWithFilter($filter, $category_id, $limit = 20)
    {
        return $this->applyFilter($filter == 'default' ? 'category' : $filter);
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

    /*
     * getSameCategoryTopics
     */
    public function getSameCategoryTopics($limit = 8)
    {
        return Topic::where('category_id', '=', $this->category_id)
                ->recent()
                ->take($limit)
                ->get();
    }

    /*
     * applyFilter
     */
    public function applyFilter($filter)
    {
        switch ($filter) {
            case 'noreply':
                return $this->orderBy('reply_count', 'asc')->recent();

            case 'vote':
                return $this->orderBy('vote_count', 'desc')->recent();

            case 'excellent':
                return $this->excellent()->recent();

            case 'recent':
                return $this->recent();

            case 'category':
                return $this->recentReply();

            default:
                return $this->pinAndRecentReply();
        }
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

    public function scopeRecentReply($query)
    {
        return $query->orderBy('order', 'desc')
                     ->orderBy('updated_at', 'desc');
    }

    public function scopePinAndRecentReply($query)
    {
        return $query->whereRaw("(`created_at` > '" . Carbon::today()->subMonth()->toDateString() . "' or (`order` > 0))")
                     ->orderBy('order', 'desc')
                     ->orderBy('updated_at', 'desc');
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
