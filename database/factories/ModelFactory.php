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
$factory->define(App\User::class, function (Generator $faker) {
    return [
        'name'              => $faker->userName,
        'email'             => $faker->safeEmail,
        'image_url'         => $faker->url,
        'city'              => $faker->city,
        'company'           => $faker->userName,
        'introduction'      => $faker->sentence,
        'personal_website'  => $faker->url,
        'twitter_account'   => $faker->userName,
        'github_url'        => $faker->url,
        'github_name'       => $faker->userName,
        'created_at'        => Carbon::now()->toDateTimeString(),
        'updated_at'        => Carbon::now()->toDateTimeString(),
    ];
});

// Topic
$factory->define(App\Topic::class, function (Generator $faker) {
    return [
        'title'             => $faker->sentence,
        'body'              => $faker->text,
        'created_at'        => Carbon::now()->toDateTimeString(),
        'updated_at'        => Carbon::now()->toDateTimeString(),
    ];
});

// Reply
$factory->define(\App\Reply::class, function (Generator $faker) {
    $body = $faker->text();
    return [
        'body'              => $body,
        'body_original'     => $body,
        'created_at'        => Carbon::now()->toDateTimeString(),
        'updated_at'        => Carbon::now()->toDateTimeString(),
    ];
});
