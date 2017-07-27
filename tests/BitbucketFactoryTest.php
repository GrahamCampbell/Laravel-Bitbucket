<?php

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
use Bitbucket\API\Http\ClientInterface;
use GrahamCampbell\Bitbucket\Authenticators\AuthenticatorFactory;
use GrahamCampbell\Bitbucket\BitbucketFactory;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Mockery;
use Psr\Log\LoggerInterface;

/**
 * This is the bitbucket factory test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class BitbucketFactoryTest extends AbstractTestBenchTestCase
{
    public function testMakeStandard()
    {
        $factory = $this->getFactory();

        $client = $factory->make(['token' => 'your-token', 'method' => 'token']);

        $this->assertInstanceOf(Api::class, $client);
        $this->assertInstanceOf(ClientInterface::class, $client->getClient());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported authentication method [bar].
     */
    public function testMakeInvalidMethod()
    {
        $factory = $this->getFactory();

        $factory->make(['method' => 'bar']);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The bitbucket factory requires an auth method.
     */
    public function testMakeEmpty()
    {
        $factory = $this->getFactory();

        $factory->make([]);
    }

    protected function getFactory()
    {
        return new BitbucketFactory(Mockery::mock(LoggerInterface::class), new AuthenticatorFactory(), __DIR__);
    }
}
