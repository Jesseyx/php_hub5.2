<?php

use Carbon\Carbon;
use Faker\Generator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// User
$factory->define(App\Models\User::class, function (Generator $faker) {
    return [
        'email'             => $faker->safeEmail,
        'name'              => $faker->userName,
        'image_url'         => $faker->url,
        'city'              => $faker->city,
        'company'           => $faker->userName,
        'introduction'      => $faker->sentence,
        'personal_website'  => $faker->url,
        'twitter_account'   => $faker->userName,
        'certification'     => $faker->sentence,
        'github_url'        => $faker->url,
        'github_name'       => $faker->userName,
        'verified'          => true,
        'login_token'       => 'uDFDJys7iwM0fTXuLNNH',
        'created_at'        => Carbon::now()->toDateTimeString(),
        'updated_at'        => Carbon::now()->toDateTimeString(),
    ];
});

// Topic
$factory->define(App\Models\Topic::class, function (Generator $faker) {
    return [
        'title'             => $faker->sentence,
        'body'              => $faker->text,
        'created_at'        => Carbon::now()->toDateTimeString(),
        'updated_at'        => Carbon::now()->toDateTimeString(),
    ];
});

// Reply
$factory->define(App\Models\Reply::class, function (Generator $faker) {
    $body = $faker->text();
    return [
        'body'              => $body,
        'body_original'     => $body,
        'created_at'        => Carbon::now()->toDateTimeString(),
        'updated_at'        => Carbon::now()->toDateTimeString(),
    ];
});

// Site
$factory->define(App\Models\Site::class, function (Generator $faker) {
    return [
        'title'             => $faker->userName,
        'description'       => $faker->sentence,
        'type'              => $faker->randomElement(['site', 'blog', 'weibo', 'dev_service', 'site_foreign']),
        'link'              => $faker->url,
        'favicon'           => 'assets/images/favicon.png',
        'created_at'        => Carbon::now()->toDateTimeString(),
        'updated_at'        => Carbon::now()->toDateTimeString(),
    ];
});
