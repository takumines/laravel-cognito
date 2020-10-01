<?php

namespace App\Providers;

use App\Cognito\CognitoClient;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Cognito\CognitoAuth;

class CognitoAuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(CognitoClient::class, function (Application $app) {
            $config = [
                'region'  => config('cognito.region'),
                'version' => config('cognito.version')
            ];

            return new CognitoClient(
                new CognitoIdentityProviderClient($config),
                config('cognito.app_client_id'),
                config('cognito.app_client_secret'),
                config('cognito.user_pool_id')
            );
        });

        $this->app['auth']->extend('cognito', function (Application $app, $name, array $config) {
            $guard = new CognitoAuth(
                $name,
                $client = $app->make(CognitoClient::class),
                $app['auth']->createUserProvider($config['provider']),
                $app['session.store'],
                $app['request']
            );

            $guard->setCookieJar($this->app['cookie']);
            $guard->setDispatcher($this->app['events']);
            $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));

            return $guard;
        });
    }
}
