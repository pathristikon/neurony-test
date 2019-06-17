<?php

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

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'name'    => $faker->words($nb = 5, $asText = true),
        'content' => $faker->paragraphs($nb = 5, $asText = true),
        'active'  => 1
    ];
});
