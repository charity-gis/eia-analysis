<?php

namespace App\Providers;


use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;
use Filament\Support\Assets\AlpineComponent;


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
        /**
         * Register custom javascript for open layers
         */


        FilamentAsset::register([
            AlpineComponent::make('map-component', __DIR__ . '/../../resources/js/dist/components/map-component.js'),

        ]);
    }
}
