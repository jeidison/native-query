<?php

namespace Jeidison\NativeQuery\Providers;

use Illuminate\Support\ServiceProvider;
use Jeidison\NativeQuery\NativeQuery\NativeQuery;

class NativeQueryServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes(array(
            __DIR__ . '/../../config/native-query.php' => config_path('nativequery.php'),
        ), 'NativeQuery');

        $this->publishes(array(
            __DIR__ . '/../../database/native-query.xml' => database_path('native-query/native-query.xml'),
        ), 'NativeQuery');

        $this->publishes(array(
            __DIR__ . '/../../database/native-query.php' => database_path('native-query/native-query.php'),
        ), 'NativeQuery');
    }

    public function register()
    {
        $this->app->singleton(NativeQuery::class, function () {
            return app()->make(NativeQuery::class);
        });

        $this->app->alias(NativeQuery::class, 'NativeQuery');
    }

    public function provides() {
        return array('NativeQuery');
    }
}
