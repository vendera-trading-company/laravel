<?php

namespace VenderaTradingCompany\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use VenderaTradingCompany\App\Support\Ip;
use VenderaTradingCompany\App\Support\SMS;

class VenderaTradingCompanyProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('vendera-trading-company-ip', function()
        {
            return new Ip();
        });

        $this->app->bind('vendera-trading-company-sms', function()
        {
            return new SMS();
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
