<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ignug\Location;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    return [
        'code' => $faker->stateAbbr,
        'name' => $faker->country,
        'post_code' => $faker->postcode,
        'state_id' => 1
    ];
});
