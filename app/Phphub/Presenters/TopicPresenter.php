<?php

namespace App\Phphub\Presenters;

use Laracasts\Presenter\Presenter;

class TopicPresenter extends Presenter
{
    public function topicFilter($filter)
    {
        $category_id = request()->segment(2);
        $category_append = '';

        if (request()->is('categories*') && $category_id) {
            $link = url('categories', $category_id) . '?filter=' . $filter;
        } else {
            $query_append = '';
            // for page
            $query = request()->except('filter', '_pjax');
            if ($query) {
                $query_append = '&' . http_build_query($query);
            }
            $link = url('topics') . '?filter=' . $filter . $query_append . $category_append;
        }

        $selected = request('filter') ? (request('filter') == $filter ? ' class="selected"' : '') : ($filter == 'default' ? ' class="active"' : '');

        return 'href="' . $link . '"' . $selected;
    }

    public function voteState($vote_type)
    {
        if ($this->votes()->ByWhom(Auth::id())->WithType($vote_type)->count()) {
            return 'active';
        } else {
            return;
        }
    }

    public function replyFloorFromIndex($index)
    {
        $index += 1;
        $current_page = request('page') ?: 1;

        return ($current_page - 1) * config('phphub.replies_perpage') + $index;
    }
}
