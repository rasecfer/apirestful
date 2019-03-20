<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::created(function($user) {
            retry(5, function() use ($user) {
                Mail::to($user->email)->send(new UserCreated($user));
            }, 100);
        });

        Product::updated(function($product) {
            if($product->quantity == 0 && $product->esDisponible()){
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;

                $product->save();
            }
        });
    }
}
