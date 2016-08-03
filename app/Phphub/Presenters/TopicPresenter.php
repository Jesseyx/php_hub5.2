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
}
