<?php

use App\Task;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'due_date' => $faker->dateTime,
        'completed' => $faker->boolean,
        'user_id' => function () {
            return factory(App\User::class)->create()->id; // Crée un utilisateur
        },
    ];
});
