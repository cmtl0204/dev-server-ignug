<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'event_type_id' => random_int(1, 100),
        'institution_id' => random_int(1, 100),
        'event_name' => $faker->name,
        'start_date' => $faker->date,
        'end_date' => $faker->date,
        'hours' => $faker->time,
        'type_certification_id' => random_int(1, 100),
        'state_id' => 1
    ];
});
