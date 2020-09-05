<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JobBoard\Language;
use Faker\Generator as Faker;

$factory->define(Language::class, function (Faker $faker) {
    return [
        'written_level_id' => random_int(1, 100),
        'spoken_level_id' => random_int(1, 100),
        'reading_level_id' => random_int(1, 100),
        'state_id' => 1
    ];
});
