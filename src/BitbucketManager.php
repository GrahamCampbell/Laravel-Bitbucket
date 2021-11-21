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

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Arr;

/**
 * This is the bitbucket manager class.
 *
 * @method \Bitbucket\Client                              connection(string|null $name = null)
 * @method \Bitbucket\Client                              reconnect(string|null $name = null)
 * @method void                                           disconnect(string|null $name = null)
 * @method array<string,\Bitbucket\Client>                getConnections()
 * @method \Bitbucket\Api\Addon                           addon()
 * @method \Bitbucket\Api\CurrentUser                     currentUser()
 * @method \Bitbucket\Api\HookEvents                      hookEvents()
 * @method \Bitbucket\Api\PullRequests                    pullRequests()
 * @method \Bitbucket\Api\Repositories                    repositories()
 * @method \Bitbucket\Api\Snippets                        snippets()
 * @method \Bitbucket\Api\Users                           users(string $username)
 * @method \Bitbucket\Api\Workspaces                      workspaces(string $workspace)
 * @method void                                           authenticate(string $method, string $token, string|null $password = null)
 * @method void                                           setUrl(string $url)
 * @method \Psr\Http\Message\ResponseInterface|null       getLastResponse()
 * @method \Http\Client\Common\HttpMethodsClientInterface getHttpClient()
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class BitbucketManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var \GrahamCampbell\Bitbucket\BitbucketFactory
     */
    protected $factory;

    /**
     * Create a new bitbucket manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository    $config
     * @param \GrahamCampbell\Bitbucket\BitbucketFactory $factory
     *
     * @return void
     */
    public function __construct(Repository $config, BitbucketFactory $factory)
    {
        parent::__construct($config);
        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return \Bitbucket\Client
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'bitbucket';
    }

    /**
     * Get the configuration for a connection.
     *
     * @param string|null $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getConnectionConfig(string $name = null)
    {
        $config = parent::getConnectionConfig($name);

        if (is_string($cache = Arr::get($config, 'cache'))) {
            $config['cache'] = $this->getNamedConfig('cache', 'Cache', $cache);
        }

        return $config;
    }

    /**
     * Get the factory instance.
     *
     * @return \GrahamCampbell\Bitbucket\BitbucketFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
