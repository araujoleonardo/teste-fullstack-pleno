<?php

namespace App\Providers;

use App\Repository\User\IUserRepository;
use App\Repository\User\UserRepository;
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
        /*Services*/



        /*Repositories*/
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
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
