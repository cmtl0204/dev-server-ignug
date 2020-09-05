<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Offer;
use Faker\Generator as Faker;

$factory->define(Offer::class, function (Faker $faker) {
    return [
        'code' => $faker->text($maxNbChars = 100),
        'contact' => $faker->tollFreePhoneNumber,
        'email' => $faker->email,
        'phone' => $faker->tollFreePhoneNumber,
        'cell_phone' => $faker->tollFreePhoneNumber,
        'contract_type_id' => random_int(1, 100),
        'position' => $faker->text($maxNbChars = 100),
        'training_hours' => $faker->text($maxNbChars = 100),
        'experience_time' => $faker->text($maxNbChars = 100),
        'remuneration' => $faker->text($maxNbChars = 100),
        'working_day' => $faker->text($maxNbChars = 100),
        'number_jobs' => random_int(1, 10),
        'start_date' => $faker->date,
        'end_date' => $faker->date,
        'activities' => $faker->words,
        'aditional_information' => $faker->text($maxNbChars = 100),
        'location_id' => random_int(1, 100),
        'state_id' => 1
    ];
});
