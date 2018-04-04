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
use Bitbucket\HttpClient\Builder;
use GrahamCampbell\Bitbucket\Authenticators\AuthenticatorFactory;
use Http\Client\Common\Plugin\RetryPlugin;
use Illuminate\Contracts\Cache\Factory;
use InvalidArgumentException;
use Madewithlove\IlluminatePsrCacheBridge\Laravel\CacheItemPool;

/**
 * This is the bitbucket factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class BitbucketFactory
{
    /**
     * The authenticator factory instance.
     *
     * @var \GrahamCampbell\Bitbucket\Authenticators\AuthenticatorFactory
     */
    protected $auth;

    /**
     * The illuminate cache instance.
     *
     * @var \Illuminate\Contracts\Cache\Factory|null
     */
    protected $cache;

    /**
     * Create a new bitbucket factory instance.
     *
     * @param \GrahamCampbell\Bitbucket\Authenticators\AuthenticatorFactory $auth
     * @param \Illuminate\Contracts\Cache\Factory|null                      $cache
     *
     * @return void
     */
    public function __construct(AuthenticatorFactory $auth, Factory $cache = null)
    {
        $this->auth = $auth;
        $this->cache = $cache;
    }

    /**
     * Make a new bitbucket client.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \Bitbucket\Client
     */
    public function make(array $config)
    {
        $client = new Client($this->getBuilder($config));

        if (!array_key_exists('method', $config)) {
            throw new InvalidArgumentException('The bitbucket factory requires an auth method.');
        }

        if ($url = array_get($config, 'url')) {
            $client->setUrl($url);
        }

        if ($config['method'] === 'none') {
            return $client;
        }

        return $this->auth->make($config['method'])->with($client)->authenticate($config);
    }

    /**
     * Get the http client builder.
     *
     * @param string[] $config
     *
     * @return \Bitbucket\HttpClient\Builder
     */
    protected function getBuilder(array $config)
    {
        $builder = new Builder();

        if ($backoff = array_get($config, 'backoff')) {
            $builder->addPlugin(new RetryPlugin(['retries' => $backoff === true ? 2 : $backoff]));
        }

        if ($this->cache && class_exists(CacheItemPool::class) && $cache = array_get($config, 'cache')) {
            $builder->addCache(new CacheItemPool($this->cache->store($cache === true ? null : $cache)));
        }

        return $builder;
    }
}
