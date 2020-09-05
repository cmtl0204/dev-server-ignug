<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Ability;
use Faker\Generator as Faker;

$factory->define(Ability::class, function (Faker $faker) {
    return [
        'category_id' => random_int(1, 100),
        'description' => $faker->text,
        'state_id' => 1,
    ];
});
