<?php

namespace JwtApi\Laravel;

use Auth;
use Closure;
use Illuminate\Support\ServiceProvider;
use JwtApi\Laravel\Guards\RequestGuard;
use JwtApi\Server\RequestParser;

class JwtApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'jwtapi-migrations');
        }
    }

    public function register(): void
    {
        $this->registerRequestParser();

        $this->registerGuard();
    }

    protected function registerRequestParser(): void
    {
        $this->app->bind(RequestParser::class, function () {
            return new RequestParser(
                $this->getPublicKeyResolver(),
                config('auth.jwtapi.expiration', 60),
                config('auth.jwtapi.leeway', 0)
            );
        });
    }

    protected function registerGuard(): void
    {
        Auth::extend('jwtapi', function ($app, $name, array $config) {
            $guard = new RequestGuard(
                $this->app['request'],
                $this->app->make(RequestParser::class),
                Auth::createUserProvider($config['provider']),
                $this->app->make(ClientRepository::class)
            );

            $this->app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }

    protected function getPublicKeyResolver(): Closure
    {
        return function (string $apiKey): ?string {
            if ($client = $this->app->make(ClientRepository::class)->find($apiKey)) {
                return $client->public_key;
            }

            return null;
        };
    }
}
