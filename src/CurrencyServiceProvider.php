<?php

namespace Koddea\Currency;

use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/currency.php' => config_path('currency.php'),
        ], 'currency-config');

        $this->publishes([
            __DIR__.'/migrations' => database_path('/migrations'),
        ], 'currency-migrations');
        
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/currency.php', 'currency');

        // Register providers.
        $this->registerCurrency();

        // Register commands.
        $this->registerCurrencyCommands();

        // Assign commands.
        $this->commands(
            'currency.update',
            'currency.cleanup'
        );
    }

    /**
     * Register currency provider.
     */
    public function registerCurrency()
    {
        $this->app->singleton('currency', function ($app) {
            return new Currency($app);
        });
    }

    /**
     * Register generator of Currency.
     */
    public function registerCurrencyCommands()
    {
        $this->app->singleton('currency.update', function ($app) {
            return new Commands\CurrencyUpdateCommand($app);
        });

        $this->app->singleton('currency.cleanup', function ($app) {
            return new Commands\CurrencyCleanupCommand();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['currency'];
    }
}
