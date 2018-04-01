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

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

/**
 * This is the bitbucket manager class.
 *
 * @method \Bitbucket\Api\Addon addon()
 * @method \Bitbucket\Api\CurrentUser currentUser()
 * @method \Bitbucket\Api\HookEvents hookEvents()
 * @method \Bitbucket\Api\Repositories repositories()
 * @method \Bitbucket\Api\Snippets snippets()
 * @method \Bitbucket\Api\Teams teams(string $username)
 * @method \Bitbucket\Api\Users users(string $username)
 * @method void authenticate(string $method, string $token, string|null $password)
 * @method \Psr\Http\Message\ResponseInterface|null getLastResponse()
 * @method \Http\Client\Common\HttpMethodsClient getHttpClient()
 *
 * @author Graham Campbell <graham@alt-three.com>
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
     * Get the factory instance.
     *
     * @return \GrahamCampbell\Bitbucket\BitbucketFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
