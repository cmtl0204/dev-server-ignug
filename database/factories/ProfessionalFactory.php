<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Professional;
use Faker\Generator as Faker;

$factory->define(Professional::class, function (Faker $faker) {
    return [
        'about_me' => $faker->text($maxNbChars = 200),
        'state_id' => 1
    ];
});
