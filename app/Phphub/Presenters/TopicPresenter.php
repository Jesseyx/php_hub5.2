<?php

namespace App\Phphub\Presenters;

use Laracasts\Presenter\Presenter;

class TopicPresenter extends Presenter
{
    public function getTopicFilter()
    {
        $filters = ['noreply', 'vote', 'excellent', 'recent'];
        $request_filter = request('filter');
        if (in_array($request_filter, $filters)) {
            return $request_filter;
        }

        return 'default';
    }

    public function topicFilter($filter)
    {
        $category_id = request()->segment(2);
        $category_append = '';

        if ($category_id) {
            $link = url('categories', $category_id) . '?filter=' . $filter;
        } else {
            $query_append = '';
            $query = request()->except('filter', '_pjax');

            if ($query) {
                $query_append = '&' . http_build_query($query);
            }
            $link = url('topics') . '?filter=' . $filter . $query_append . $category_append;
        }

        $selected = request('filter') ? (request('filter') == $filter ? ' class="selected"' : '') : '';

        return 'href="' . $link . '"' . $selected;
    }

    public function replyFloorFromIndex($index)
    {
        $index += 1;
        $current_page = request('page') ?: 1;

        return ($current_page - 1) * config('phphub.replies_perpage') + $index;
    }
}
