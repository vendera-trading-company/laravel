<?php

namespace VenderaTradingCompany\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use VenderaTradingCompany\App\Support\Ip;

class VenderaTradingCompanyProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('vendera-trading-company-location', function()
        {
            return new Ip();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadBladeDirectives();

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'vendera');

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'vendera');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    private function loadBladeDirectives()
    {
        Blade::directive('input', function ($expression) {
            return '<?php $input = $this->getInput(' . $expression . '); ?>';
        });

        Blade::directive('endinput', function ($expression) {
            return "<?php  ?>";
        });

        Blade::directive('collection', function ($expression) {
            return '<?php $collection = $this->getCollection(' . $expression . '); ?>';
        });

        Blade::directive('endcollection', function ($expression) {
            return "<?php  ?>";
        });

        Blade::directive('field', function ($expression) {
            return '<?php $field = $this->getComponent(' . $expression . '); ?>';
        });

        Blade::directive('endfield', function ($expression) {
            return "<?php  ?>";
        });
    }
}
