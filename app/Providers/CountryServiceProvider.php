<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CountryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::component('components.countries-select', 'countries-select');
    }

    public function register()
    {
        //
    }
}