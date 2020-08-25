<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [

        'ethnic_origin_id' => random_int(1, 8),
        'location_id' => 30,
        'identification_type_id' => random_int(14, 15),
        'identification' => $faker->numberBetween($min = 1000000000, $max = 9999999999),
        'first_name' => $faker->firstNameMale,
        'second_name' => $faker->firstNameMale,
        'first_lastname' => $faker->lastName,
        'second_lastname' => $faker->lastName,
        'sex_id' => 10,
        'gender_id' => 12,
        'personal_email' => $faker->unique()->safeEmail,
        'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'blood_type_id' => random_int(16, 23),
        'user_name' => $faker->numberBetween($min = 1000000000, $max = 9999999999),
        'email' => $faker->unique()->safeEmail,
        'state_id' => 1,
        'password' => '$2y$10$Jspv6JzkO2O4EGxpCFiZquVdXfjozT5VdO9i0KfQn0Z4GxNYWRJIW', // password

    ];
});
