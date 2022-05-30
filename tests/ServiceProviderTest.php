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

namespace GrahamCampbell\Tests\Bitbucket;

use Bitbucket\Client;
use GrahamCampbell\Bitbucket\Auth\AuthenticatorFactory;
use GrahamCampbell\Bitbucket\HttpClient\BuilderFactory;
use GrahamCampbell\Bitbucket\BitbucketFactory;
use GrahamCampbell\Bitbucket\BitbucketManager;
use GrahamCampbell\Bitbucket\Cache\ConnectionFactory;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testHttpClientFactoryIsInjectable()
    {
        $this->assertIsInjectable(BuilderFactory::class);
    }

    public function testAuthFactoryIsInjectable()
    {
        $this->assertIsInjectable(AuthenticatorFactory::class);
    }

    public function testCacheFactoryIsInjectable()
    {
        $this->assertIsInjectable(ConnectionFactory::class);
    }

    public function testBitbucketFactoryIsInjectable()
    {
        $this->assertIsInjectable(BitbucketFactory::class);
    }

    public function testBitbucketManagerIsInjectable()
    {
        $this->assertIsInjectable(BitbucketManager::class);
    }

    public function testBindings()
    {
        $this->assertIsInjectable(Client::class);

        $original = $this->app['bitbucket.connection'];
        $this->app['bitbucket']->reconnect();
        $new = $this->app['bitbucket.connection'];

        $this->assertNotSame($original, $new);
        $this->assertEquals($original, $new);
    }
}
