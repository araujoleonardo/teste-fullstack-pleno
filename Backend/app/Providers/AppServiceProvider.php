<?php

namespace App\Providers;

use App\Repository\Product\IProductRepository;
use App\Repository\Product\ProductRepository;
use App\Repository\User\IUserRepository;
use App\Repository\User\UserRepository;
use App\Services\Product\IProductService;
use App\Services\Product\ProductService;
use App\Services\User\IUserService;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*Services*/
        $this->app->bind(
            IUserService::class,
            UserService::class
        );
        $this->app->bind(
            IProductService::class,
            ProductService::class
        );
        /*Services*/



        /*Repositories*/
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );
        $this->app->bind(
            IProductRepository::class,
            ProductRepository::class
        );
        /*Repositories*/
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
