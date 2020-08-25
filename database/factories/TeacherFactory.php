<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ignug\Teacher;
use Faker\Generator as Faker;

$factory->define(Teacher::class, function (Faker $faker) {
    return [
        'name' => $faker->firstNameFemale
    ];
});
