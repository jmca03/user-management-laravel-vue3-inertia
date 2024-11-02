<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Register a macro for soft deletes
        Route::macro('softDeletes', function () {

            Route::group([
               'prefix' => 'users',
               'controller' => UserController::class,
            ], function () {
                Route::get('trashed', 'trashed')->name('users.trashed');
                Route::patch('{user}/restore', 'restore')->name('users.restore');
                Route::delete('{user}/delete', 'delete')->name('users.delete');
            });

        });
    }
}
