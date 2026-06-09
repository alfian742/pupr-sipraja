<?php

namespace App\Providers;

use App\Models\Contact;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
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
        // Load helper
        if (file_exists(app_path('Helpers/helpers.php'))) {
            require_once app_path('Helpers/helpers.php');
        }

        View::composer(['layouts.guest'], function ($view) {
            // Ambil contact dari cache selamanya
            $contact = Cache::rememberForever('contact', function () {
                return Contact::first();
            });

            $view->with('contact', $contact);
        });
    }
}
