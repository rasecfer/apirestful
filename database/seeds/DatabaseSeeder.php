<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Category;
use App\Product;
use App\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(User::class, 1000)->create();
        factory(Category::class, 30)->create();
        factory(Product::class, 1000)->create()->each(function ($product){
            $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');
            $product->categories()->attach($categorias);
        });
        factory(Transaction::class, 1000)->create();

    }
}
