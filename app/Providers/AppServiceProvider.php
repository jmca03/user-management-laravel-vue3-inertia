<?php

namespace App\Providers;

use App\Events\UserSaved;
use App\Http\Controllers\UserController;
use App\Interfaces\UserServiceInterface;
use App\Listeners\SaveUserBackgroundInformation;
use App\Services\UserService;
use Illuminate\Support\Facades\Event;
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
        app()->bind(UserServiceInterface::class, UserService::class);
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

        // Register Events
        Event::listen(
            UserSaved::class,
            SaveUserBackgroundInformation::class
        );
    }
}
