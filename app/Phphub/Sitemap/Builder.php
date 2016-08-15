<?php

namespace App\Phphub\Sitemap;

use Illuminate\Config\Repository;
use Roumen\Sitemap\Sitemap;

class Builder
{
    /**
     * The type of sitemap to build.
     * @var string
     */
    protected $type = 'xml';

    /**
     * Config repository instance.
     * @var Repository
     */
    protected $config;

    /**
     * The sitemap generator instance.
     * @var
     */
    protected $sitemap;

    /**
     * The data provider instance.
     * @var DataProvider
     */
    protected $provider;

    /**
     * Create a new sitemap builder instance.
     * @param DataProvider $provider
     * @param Repository $config
     */
    public function __construct(DataProvider $provider, Repository $config)
    {
        // Repository 契约通常是应用中 cache 配置文件中指定的默认缓存驱动的一个实现。
        $this->sitemap = app('sitemap');
        $this->provider = $provider;
        $this->config = $config;
    }

    /**
     * Set the type of sitemap to build.
     * @param $type
     */
    public function setType($type)
    {
        $this->type = strtolower($type);
    }

    /**
     * Build the sitemap.
     */
    public function render()
    {
        if (!$this->sitemap->isCached()) {
            $this->addStaticPages();

            foreach ($this->getTypes()['custom'] as $type => $config) {
                $this->addDynamicData($type, $config);
            }
        }

        return $this->sitemap->render($this->type);
    }

    /**
     * Add the static pages to the sitemap
     */
    protected function addStaticPages()
    {
        $pages = $this->provider->getStaticPages();

        foreach ($pages as $page) {
            $this->sitemap->add($page['url'], null, $page['priority'], $page['freq']);
        }
    }

    /**
     * Get the dynamic data types.
     * @return mixed
     */
    protected function getTypes()
    {
        return $this->config->get('sitemap');
    }

    /**
     * Add the dynamic data of the given type to the sitemap.
     * @param $type
     * @param $config
     */
    protected function addDynamicData($type, $config)
    {
        foreach ($this->getItems($type) as $item) {
            $url = $this->getItemUrl($item, $type);
            $lastMod = $item->{ $config['lastMod'] };

            $this->sitemap->add($url, $lastMod, $config['priority'], $config['freq']);
        }
    }

    /**
     * Get the dynamic items from the data provider.
     * @param $type
     * @return mixed
     */
    protected function getItems($type) {
        $method = $this->getDataMethodName($type);
        return $this->provider->$method();
    }

    /**
     * Get the name of the data method.
     * @param $type
     * @return string
     */
    protected function getDataMethodName($type) {
        // studly_case 函数将给定字符串转化为单词开头字母大写的格式
        return 'get' . studly_case($type);
    }

    /**
     * Get the url of the given item.
     * @param $item
     * @param $type
     * @return mixed
     */
    protected function getItemUrl($item, $type)
    {
        $method = $this->getUrlMethodName($type);
        return $this->provider->$method($item);
    }

    /**
     * Get the name of the url method.
     * @param $type
     * @return string
     */
    protected function getUrlMethodName($type)
    {
        // str_singular 函数将字符串转化为单数形式，该函数目前只支持英文
        return 'get' . studly_case(str_singular($type)) . 'Url';
    }
}
