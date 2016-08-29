<?php

namespace App\Models\Traits;

trait TopicFilterable
{
    /*
     * getTopicsWithFilter
     */
    public function getTopicsWithFilter($filter, $limit = 20)
    {
        $filter = $this->getTopicFilter($filter);

        return $this->applyFilter($filter)
            ->with('user', 'category', 'lastReplyUser')
            ->paginate($limit);
    }

    /*
     * getCategoryTopicsWithFilter
     */
    public function getCategoryTopicsWithFilter($filter, $category_id, $limit = 20)
    {
        return $this->applyFilter($filter == 'default' ? 'category' : $filter)
            ->where('category_id', '=', $category_id)
            ->with('user', 'category', 'lastReplyUser')
            ->paginate($limit);
    }

    public function getTopicFilter($request_filter) {
        $filters = ['noreply', 'vote', 'excellent','recent', 'wiki', 'jobs', 'excellent-pinned'];
        if (in_array($request_filter, $filters)) {
            return $request_filter;
        }

        return 'default';
    }

    /*
     * applyFilter
     */
    public function applyFilter($filter)
    {
        $query = $this->withoutBlocked();

        switch ($filter) {
            case 'noreply':
                return $query->pinned()->orderBy('reply_count', 'asc')->recent();

            case 'vote':
                return $query->pinned()->orderBy('vote_count', 'desc')->recent();

            case 'excellent':
                return $query->excellent()->recent();

            case 'recent':
                return $query->pinned()->recent();

            // for api，分类：教程
            case 'wiki':
                return $query->where('category_id', 6)->pinned()->recent();

            // for api，分类：招聘
            case 'jobs':
                return $query->where('category_id', 1)->pinned()->recent();

            // 主要 API 首页在用，置顶+精华
            case 'excellent-pinned':
                return $query->excellent()->pinned()->recent();

            case 'random-excellent':
                return $query->excellent()->fresh()->random();

            case 'category':
                return $query->pinned()->recentReply();

            default:
                return $query->pinAndRecentReply();
        }
    }
}
