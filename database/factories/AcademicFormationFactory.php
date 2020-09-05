<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\AcademicFormation;
use Faker\Generator as Faker;

$factory->define(AcademicFormation::class, function (Faker $faker) {
    return [
        'category_id' => random_int(1, 100),
        'professional_degree_id' => random_int(1, 100),
        'registration_date' => $faker->date,
        'senescyt_code' => $faker->ean13,
        'has_titling' => $faker->boolean,
        'state_id' => 1,
    ];
});
