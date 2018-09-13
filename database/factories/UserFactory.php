<?php

use Illuminate\Support\Facades\Hash;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(App\Models\UserModel::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'password' => Hash::make('123456'),
        'role' => $faker->randomElement(get_class_constants_value(App\Constants\UserRoleConstant::class)),
        'status' => 1,
        'api_token' => $faker->swiftBicNumber,
        'activation_code' => $faker->creditCardNumber,
        'activation_date' => $faker->dateTimeBetween('-7 days', '- 3 days')->format('Y-m-d H:i:s'),
        'last_login_date' => $faker->dateTimeBetween('-2 days', 'now')->format('Y-m-d H:i:s'),
    ];
});
$factory->afterCreating(App\Models\UserModel::class, function ($user, $faker) {
    $user->userMeta()->save(factory(App\Models\UserMetaModel::class)->make(['user_id' => $user->id]));
});
