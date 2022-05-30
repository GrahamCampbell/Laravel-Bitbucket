<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Bitbucket.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Bitbucket;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory as GuzzlePsrFactory;
use Bitbucket\Client;
use GrahamCampbell\Bitbucket\Auth\AuthenticatorFactory;
use GrahamCampbell\Bitbucket\Cache\ConnectionFactory;
use GrahamCampbell\Bitbucket\HttpClient\BuilderFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the bitbucket service provider class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class BitbucketServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/bitbucket.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('bitbucket.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('bitbucket');
        }

        $this->mergeConfigFrom($source, 'bitbucket');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHttpClientFactory();
        $this->registerAuthFactory();
        $this->registerCacheFactory();
        $this->registerBitbucketFactory();
        $this->registerManager();
        $this->registerBindings();
    }

    /**
     * Register the http client factory class.
     *
     * @return void
     */
    protected function registerHttpClientFactory()
    {
        $this->app->singleton('gitlab.httpclientfactory', function () {
            $psrFactory = new GuzzlePsrFactory();

            return new BuilderFactory(
                new GuzzleClient(['connect_timeout' => 10, 'timeout' => 30]),
                $psrFactory,
                $psrFactory,
            );
        });

        $this->app->alias('gitlab.httpclientfactory', BuilderFactory::class);
    }

    /**
     * Register the auth factory class.
     *
     * @return void
     */
    protected function registerAuthFactory()
    {
        $this->app->singleton('bitbucket.authfactory', function () {
            return new AuthenticatorFactory();
        });

        $this->app->alias('bitbucket.authfactory', AuthenticatorFactory::class);
    }

    /**
     * Register the cache factory class.
     *
     * @return void
     */
    protected function registerCacheFactory()
    {
        $this->app->singleton('bitbucket.cachefactory', function (Container $app) {
            $cache = $app->bound('cache') ? $app->make('cache') : null;

            return new ConnectionFactory($cache);
        });

        $this->app->alias('bitbucket.cachefactory', ConnectionFactory::class);
    }

    /**
     * Register the bitbucket factory class.
     *
     * @return void
     */
    protected function registerBitbucketFactory()
    {
        $this->app->singleton('bitbucket.factory', function (Container $app) {
            $builder = $app['bitbucket.httpclientfactory'];
            $auth = $app['bitbucket.authfactory'];
            $cache = $app['bitbucket.cachefactory'];

            return new BitbucketFactory($builder, $auth, $cache);
        });

        $this->app->alias('bitbucket.factory', BitbucketFactory::class);
    }

    /**
     * Register the manager class.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('bitbucket', function (Container $app) {
            $config = $app['config'];
            $factory = $app['bitbucket.factory'];

            return new BitbucketManager($config, $factory);
        });

        $this->app->alias('bitbucket', BitbucketManager::class);
    }

    /**
     * Register the bindings.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind('bitbucket.connection', function (Container $app) {
            $manager = $app['bitbucket'];

            return $manager->connection();
        });

        $this->app->alias('bitbucket.connection', Client::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'bitbucket.httpclientfactory',
            'bitbucket.authfactory',
            'bitbucket.cachefactory',
            'bitbucket.factory',
            'bitbucket',
            'bitbucket.connection',
        ];
    }
}
