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
            __DIR__.'/../public' => public_path('vendor/video-services'),
        ], 'public');
        $this->publishes([
            __DIR__.'/../resources/assets' => base_path('resources/assets/vendor/video-services'),
        ], 'assets');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/video-services'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => base_path('resources/lang/vendor/video-services'),
        ], 'lang');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'video-services');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'video-services');

        Route::group([
            'middleware' => config('video-services.router.attributes.middleware'),
            'prefix' => config('video-services.router.attributes.prefix'),
            'as' => 'video-services.'
        ], __DIR__ . '/../routes/web.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/video-services.php', 'video-services');

        $this->app->singleton('videoservices', function($app) {
            return new VideoServices();
        });
    }
}
