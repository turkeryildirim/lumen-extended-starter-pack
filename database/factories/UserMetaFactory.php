<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(App\Models\UserMetaModel::class, function (Faker\Generator $faker) {
    return [
        'user_id' => function () {
            return factory(\App\Models\UserModel::class)->create()->id;
        },
        'gender' => $faker->randomElement(get_class_constants_value(App\Constants\UserGenderConstant::class)),
        'phone' => $faker->phoneNumber,
        'city' => $faker->city,
        'address' => $faker->address,
        'birth_date' => $faker->dateTimeBetween('-50 years', '-20 years')->format('Y-m-d H:i:s'),
    ];
});
