<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Attendance\Attendance;
use Faker\Generator as Faker;

$factory->define(Attendance::class, function (Faker $faker) {
    return [
        'code'=>$faker->word
    ];
});
