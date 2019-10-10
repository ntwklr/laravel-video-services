<?php

namespace Ntwklr\VideoServices;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class VideoServicesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/video-services.php' => config_path('video-services.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/video-services'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang/vendor/video-services'),
        ], 'lang');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'video-services');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'video-services');

        Route::group(__DIR__ . '/../routes/api.php')
            ->prefix(config('video-services.router.attributes.prefix'))
            ->middleware(config('video-services.router.attributes.middleware'))
            ->as('video-services');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/video-services.php', 'video-services');
    }

    public function provides()
    {
        return [VideoServices::class];
    }
}