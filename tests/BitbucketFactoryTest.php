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
use GrahamCampbell\Bitbucket\BitbucketFactory;
use GrahamCampbell\Bitbucket\Cache\ConnectionFactory;
use GrahamCampbell\Bitbucket\HttpClient\BuilderFactory;
use GrahamCampbell\BoundedCache\BoundedCacheInterface;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory as GuzzlePsrFactory;
use Http\Client\Common\HttpMethodsClientInterface;
use Illuminate\Contracts\Cache\Factory;
use InvalidArgumentException;
use Mockery;

/**
 * This is the bitbucket factory test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class BitbucketFactoryTest extends AbstractTestBenchTestCase
{
    public function testMakeStandard()
    {
        $factory = $this->getFactory();

        $client = $factory[0]->make(['token' => 'your-token', 'method' => 'oauth']);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testMakeStandardExplicitCache()
    {
        $factory = $this->getFactory();

        $boundedCache = Mockery::mock(BoundedCacheInterface::class);
        $boundedCache->shouldReceive('getMaximumLifetime')->once()->with()->andReturn(42);

        $factory[1]->shouldReceive('make')->once()->with(['name' => 'main', 'driver' => 'illuminate'])->andReturn($boundedCache);

        $client = $factory[0]->make(['token' => 'your-token', 'method' => 'oauth', 'cache' => ['name' => 'main', 'driver' => 'illuminate']]);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testMakeStandardNamedCache()
    {
        $factory = $this->getFactory();

        $boundedCache = Mockery::mock(BoundedCacheInterface::class);
        $boundedCache->shouldReceive('getMaximumLifetime')->once()->with()->andReturn(42);

        $factory[1]->shouldReceive('make')->once()->with(['name' => 'main', 'driver' => 'illuminate', 'connection' => 'foo'])->andReturn($boundedCache);

        $client = $factory[0]->make(['token' => 'your-token', 'method' => 'oauth', 'cache' => ['name' => 'main', 'driver' => 'illuminate', 'connection' => 'foo']]);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testMakeStandardNoCacheOrBackoff()
    {
        $factory = $this->getFactory();

        $client = $factory[0]->make(['token' => 'your-token', 'method' => 'oauth', 'cache' => false, 'backoff' => false]);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testMakeStandardExplicitBackoff()
    {
        $factory = $this->getFactory();

        $client = $factory[0]->make(['token' => 'your-token', 'method' => 'oauth', 'backoff' => true]);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testMakeStandardExplicitUrl()
    {
        $factory = $this->getFactory();

        $client = $factory[0]->make(['token' => 'your-token', 'method' => 'oauth', 'url' => 'https://api.example.com']);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testMakeNoneMethod()
    {
        $factory = $this->getFactory();

        $client = $factory[0]->make(['method' => 'none']);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertInstanceOf(HttpMethodsClientInterface::class, $client->getHttpClient());
    }

    public function testMakeInvalidMethod()
    {
        $factory = $this->getFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported authentication method [bar].');

        $factory[0]->make(['method' => 'bar']);
    }

    public function testMakeEmpty()
    {
        $factory = $this->getFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The bitbucket factory requires an auth method.');

        $factory[0]->make([]);
    }

    protected function getFactory()
    {
        $psrFactory = new GuzzlePsrFactory();

        $builder = new BuilderFactory(
            new GuzzleClient(['connect_timeout' => 10, 'timeout' => 30]),
            $psrFactory,
            $psrFactory,
        );

        $cache = Mockery::mock(ConnectionFactory::class);

        return [new BitbucketFactory($builder, new AuthenticatorFactory(), $cache), $cache];
    }
}
