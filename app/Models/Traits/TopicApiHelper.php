<?php

namespace App\Models\Traits;

use App\Models\Category;
use App\Models\User;

trait TopicApiHelper
{
    public function correctApiFilter($filter)
    {
        switch($filter) {
            case 'newest':
                return 'recent';

            case 'excellent':
                return 'excellent-pinned';

            case 'nobody':
                return 'noreply';

            default:
                return $filter;
        }
    }

    public function last_reply_user()
    {
        return $this->belongsTo(User::class, 'last_reply_user_id');
    }

    public function node()
    {
        return $this->belongsTo(Category::class);
    }
}
