<?php

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

function lang($text)
{
    // trans 函数使用本地文件翻译给定语言行
    return str_replace('phphub.', '', trans('phphub.' . $text));
}

function getUserStaticDomain()
{
    return config('phphub.user_static') ?: config('phphub.url');
}
