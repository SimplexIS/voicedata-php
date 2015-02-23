<?php

namespace SimplexIS\VoicedataPhp;

use Illuminate\Support\ServiceProvider;

class VoicedataPhpServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['voicedata'] = $this->app->share(function ($app){
            $vd = new Voicedata();
            $vd->setConfig($app['config']);
            return $vd;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'voicedata'
        ];
    }

    public function boot()
    {
        $this->package('simplexis/voicedata-php');
    }
}
