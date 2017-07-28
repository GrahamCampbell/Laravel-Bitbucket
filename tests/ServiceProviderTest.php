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

namespace GrahamCampbell\Tests\Bitbucket;

use Bitbucket\API\Api;
use GrahamCampbell\Bitbucket\Authenticators\AuthenticatorFactory;
use GrahamCampbell\Bitbucket\BitbucketFactory;
use GrahamCampbell\Bitbucket\BitbucketManager;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testAuthFactoryIsInjectable()
    {
        $this->assertIsInjectable(AuthenticatorFactory::class);
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
        $this->assertIsInjectable(Api::class);

        $original = $this->app['bitbucket.connection'];
        $this->app['bitbucket']->reconnect();
        $new = $this->app['bitbucket.connection'];

        $this->assertNotSame($original, $new);
        $this->assertEquals($original, $new);
    }
}
