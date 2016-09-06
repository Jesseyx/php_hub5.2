<?php

function insanity_check()
{
    if (app()->environment('production')) {
        exit('别傻了? 这是线上环境呀。');
    }
}

function cdn($filePath)
{
    if (config('phphub.url_static')) {
        return config('phphub.url_static') . $filePath;
    } else {
        return config('phphub.url') . $filePath;
    }
}

function getCdnDomain()
{
    return config('phphub.url_static') ?:  config('phphub.url');
}

function lang($text, $parameters = [])
{
    // trans 函数使用本地文件翻译给定语言行
    return str_replace('phphub.', '', trans('phphub.' . $text, $parameters));
}

function getUserStaticDomain()
{
    return config('phphub.user_static') ?: config('phphub.url');
}

function admin_link($title, $path, $id = '')
{
    return '<a href="' . admin_url($path, $id) . '" target="_blank">' . $title . '</a>';
}

function admin_url($path, $id = '')
{
    return env('APP_URL') . "admin/$path" . ($id ? '/' . $id : '');
}

function show_crx_hint()
{
    // 存储一次性数据
    session()->flash('show_crx_hint', 'yes');
}

function check_show_crx_hint()
{
    return session('show_crx_hint') ? true : false;
}

function navViewActive($anchor)
{
    return Route::currentRouteName() == $anchor ? ' active' : '';
}

function is_request_from_api()
{
    return $_SERVER['SERVER_NAME'] == env('API_DOMAIN');
}

function get_user_static_domain()
{
    return config('phphub.user_static') ?: config('phphub.url');
}

function get_platform()
{
    return request()->header('X-Client-Platform');
}

/**
 * @param $value
 * @param bool $reverse 颠倒
 * @return string
 */
function admin_enum_style_output($value, $reverse = false)
{
    if ($reverse) {
        $class = ($value === true || $value == 'yes') ? 'danger' : 'success';
    } else {
        $class = ($value === true || $value == 'yes') ? 'success' : 'danger';
    }

    return '<span class="label bg-'.$class.'">'.$value.'</span>';
}

function model_link($title, $model, $id)
{
    return '<a href="' . model_url($model, $id). '" target="_blank">' . $title . '</a>';
}

function model_url($model, $id)
{
    return env('APP_URL') . "/$model/$id";
}

// for api
function api_per_page($default = null)
{
    $max_per_page = config('api.max_per_page');
    $per_page = (request('per_page') ?: $default) ?: config('api.default_per_page');

    return (int) ($per_page < $max_per_page ? $per_page : $max_per_page);
}

function get_cdn_domain()
{
    return config('phphub.url_static') ?:  config('phphub.url');
}

// formartted Illuminate\Support\MessageBag
function output_msb(\Illuminate\Support\MessageBag $messageBag)
{
    return implode(", ", $messageBag->all());
}

/**
 * 生成用户客户端 URL Schema 技术的链接.
 */
function schema_url($path, $parameters = [])
{
    $query = empty($parameters) ? '' : '?'.http_build_query($parameters);

    return strtolower(config('app.name')).'://'.trim($path, '/').$query;
}
