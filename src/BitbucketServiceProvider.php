<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Bitbucket.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Bitbucket;

use Bitbucket\Client;
use GrahamCampbell\Bitbucket\Authenticators\AuthenticatorFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the bitbucket service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
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
        $this->registerAuthFactory();
        $this->registerBitbucketFactory();
        $this->registerManager();
        $this->registerBindings();
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
     * Register the bitbucket factory class.
     *
     * @return void
     */
    protected function registerBitbucketFactory()
    {
        $this->app->singleton('bitbucket.factory', function (Container $app) {
            $auth = $app['bitbucket.authfactory'];
            $cache = $app['cache'];

            return new BitbucketFactory($auth, $cache);
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
            'bitbucket.authfactory',
            'bitbucket.factory',
            'bitbucket',
            'bitbucket.connection',
        ];
    }
}
