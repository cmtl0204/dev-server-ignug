<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\ProfessionalExperience;
use Faker\Generator as Faker;

$factory->define(ProfessionalExperience::class, function (Faker $faker) {
    return [
        'employer' => $faker->name,
        'position' => $faker->jobTitle,
        'job_description' => $faker->text,
        'start_date' => $faker->date,
        'end_date' => $faker->date,
        'reason_leave' => $faker->text,
        'current_work' => $faker->boolean,
        'state_id' => 1
    ];
});
