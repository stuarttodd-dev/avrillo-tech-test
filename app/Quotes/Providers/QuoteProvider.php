<?php

namespace App\Quotes\Providers;

use App\Managers\QuoteManager;
use App\Quotes\Services\KayneRestService;
use Illuminate\Support\ServiceProvider;

class QuoteProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('quotes', function ($app) {
            return new \App\Quotes\Managers\QuoteManager($app);
        });
    }
}
