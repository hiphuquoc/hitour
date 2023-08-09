<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BuildInsertUpdateModel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        // Binds BuildInsertUpdateModel to the container
        $this->app->bind(BuildInsertUpdateModel::class, function ($app) {
            return new BuildInsertUpdateModel(/* Add any required dependencies here */);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
