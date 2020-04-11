<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TaskList;
use Faker\Generator as Faker;

$factory->define(TaskList::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class)->create()->id,
        'name' => $faker->sentence,
        'description' => $faker->paragraph,
        'is_complete' => $faker->boolean()
    ];
});
