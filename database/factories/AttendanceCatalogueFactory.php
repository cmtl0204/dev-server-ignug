<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Attendance\Catalogue;
use Faker\Generator as Faker;

$factory->define(Catalogue::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'state_id' => 1
    ];
});
