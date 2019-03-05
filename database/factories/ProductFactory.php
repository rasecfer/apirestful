<?php

use Faker\Generator as Faker;
use App\Product;
use App\User;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $quantity = $faker->numberBetween(0, 100),
        'status' => $quantity > 0 ? Product::PRODUCTO_DISPONIBLE : Product::PRODUCTO_NO_DISPONIBLE,
        'image' => $faker->randomElement(['IMG1.jpg', 'IMG2.jpg', 'IMG3.jpg']),
        'seller_id' => User::all()->random()->id,
    ];
});
