<?php

namespace App\Providers;

use App\Services\CurrenciesApi\AwesomeApi;
use App\Services\CurrencyService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\CurrencyServiceInterface', function () {
            return new CurrencyService(app('App\Services\CurrenciesApi\CurrencyApiInterface'));
        });

        $this->app->bind('App\Services\CurrenciesApi\CurrencyApiInterface', function () {
            return new AwesomeApi(new Client(), config('app.currency_api'), config('app.valid_currencies'));
        });
    }
}
