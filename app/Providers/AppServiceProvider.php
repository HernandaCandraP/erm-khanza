<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

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
        // \URL::forceScheme('https');
        Blade::directive('nl2br', function ($string) {
            return "<?php echo nl2br($string); ?>";
        });

        Blade::directive('money', function ($amount) {
            return "<?php echo 'Rp. ' . number_format($amount, 0); ?>";
        });
    }
}
