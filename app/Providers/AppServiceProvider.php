<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->bind('path.public', function() {
            return base_path('public_html');
        });
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);


        // Guest only used in front office
        Blade::if('guest', function () {
            return !Auth::guard("user")->check() && !Auth::guard("instructor")->check();
        });

        Blade::if('user', function () {
            return Auth::guard("user")->check();
        });

        Blade::if('instructor', function () {
            return Auth::guard("instructor")->check();
        });

        Blade::if('admin', function () {
            return Auth::guard("admin")->check();
        });

    }
}
