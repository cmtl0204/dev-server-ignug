<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\ProfessionalReference;
use Faker\Generator as Faker;

$factory->define(ProfessionalReference::class, function (Faker $faker) {
    return [
        'institution' => $faker->name,
        'position' => $faker->jobTitle,
        'contact' => $faker->tollFreePhoneNumber,
        'phone' => $faker->phoneNumber,
        'state_id' => 1
    ];
});
