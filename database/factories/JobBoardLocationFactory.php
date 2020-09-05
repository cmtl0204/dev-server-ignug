<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Location;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    return [
        'code' => $faker->ean13,
        'name' => $faker->name,
        'type' => $faker->text($maxNbChars = 100),
        'post_code' => $faker->postcode,
        'state_id' => 1
    ];
});
