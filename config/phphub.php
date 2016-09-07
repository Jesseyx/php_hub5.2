<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | 会用于生成 URL Schema 等.
    |
    */
    'name' => env('APP_NAME', 'PHPHub'),
    
    'url_static'        => env('URL_STATIC', null),
    'user_static'       => env('USER_STATIC', null),

    'replies_perpage'         => 80,
    'actived_time_for_update' => 'actived_time_for_update',
    'actived_time_data'       => 'actived_time_data',

];
