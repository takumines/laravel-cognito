<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\MembershipType;
use App\Enums\Role;
use App\Models\User;
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
        'email'             => $faker->unique()->safeEmail,
        'cognito_username'  => '6449f262-970f-4bf7-8d34-8b9f3578557a',
        'email_verified_at' => now(),
        'role'              => Role::PRE_MEMBER
    ];
}, 'pre_member');

$factory->define(User::class, function (Faker $faker) {
    return [
        'email'             => $faker->unique()->safeEmail,
        'cognito_username'  => '6449f262-970f-4bf7-8d34-8b9f3578557a',
        'email_verified_at' => now(),
        'role'              => MembershipType::RESIDENCE
    ];
}, 'member');

$factory->define(User::class, function (Faker $faker) {
    return [
        'email'             => $faker->unique()->safeEmail,
        'cognito_username'  => '6449f262-970f-4bf7-8d34-8b9f3578557b',
        'email_verified_at' => now(),
        'role'              => Role::ADMIN
    ];
}, 'admin');
