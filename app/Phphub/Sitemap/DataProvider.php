<?php

namespace App\Phphub\Sitemap;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Routing\UrlGenerator;

class DataProvider
{
    /**
     * The URL generator instance.
     * @var UrlGenerator
     */
    protected $url;

    /**
     * Topic Eloquent Model
     * @var Topic
     */
    protected $topics;

    /**
     * Catebory Eloquent Model
     * @var Category
     */
    protected $categories;

    /**
     * Create a new data provider instance.
     * @param UrlGenerator $url
     * @param Topic $topics
     * @param Category $categories
     */
    public function __construct(UrlGenerator $url, Topic $topics, Category $categories)
    {
        $this->url = $url;
        $this->topics = $topics;
        $this->categories = $categories;
    }

    /**
     * Get all the static pages to include in the sitemap.
     * @param $route
     * @param $freq 频率
     * @param $priority 优先级
     * @return array
     */
    protected function getPage($route, $freq, $priority)
    {
        $url = $this->url->route($route);

        return compact('url', 'freq', 'priority');
    }

    /**
     * Get all the static pages to include in the sitemap.
     * @return array
     */
    public function getStaticPages()
    {
        $static = [];

        $static[] = $this->getPage('home', 'daily', '1.0');
        $static[] = $this->getPage('topics.index', 'daily', '1.0');
        // $static[] = $this->getPage('wiki', 'monthly', '0.7');
        $static[] = $this->getPage('users.index', 'weekly', '0.8');
        $static[] = $this->getPage('about', 'monthly', '0.7');

        return $static;
    }

    /**
     * Get all the topic to include in the sitemap.
     * @return mixed
     */

    public function getTopics()
    {
        return $this->topics->recent()->get();
    }

    /**
     * Get the url for the given topic.
     * @param $topic
     * @return string
     */
    public function getTopicUrl($topic)
    {
        return $this->url->route('topics.show', $topic->id);
    }

    /**
     * Get all the Categories to include in the sitemap.
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get the url for the given category.
     * @param $category
     * @return string
     */
    public function getCategoryUrl($category)
    {
        return $this->url->route('categories.show', $category->id);
    }
}
