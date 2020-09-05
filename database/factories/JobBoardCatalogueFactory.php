<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Catalogue;
use Faker\Generator as Faker;

$factory->define(Catalogue::class, function (Faker $faker) {
    return [
        'code' => $faker->ean13,
        'name' => $faker->name,
        'type' => $faker->text($maxNbChars = 100),
        'icon' => $faker->text($maxNbChars = 100),
        'state_id' => 1
    ];
});
