<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'type_id' => 1,
        'trade_name' => $faker->company,
        'web_page' => $faker->url,
        'comercial_activity' => $faker->catchPhrase,
        'state_id' => 1
    ];
});
