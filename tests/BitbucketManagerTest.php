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
use GrahamCampbell\Bitbucket\BitbucketFactory;
use GrahamCampbell\Bitbucket\BitbucketManager;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Illuminate\Contracts\Config\Repository;
use Mockery;

/**
 * This is the bitbucket manager test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class BitbucketManagerTest extends AbstractTestBenchTestCase
{
    public function testCreateConnection(): void
    {
        $config = ['token' => 'your-token'];

        $manager = self::getManager($config);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('bitbucket.default')->andReturn('main');

        self::assertSame([], $manager->getConnections());

        $return = $manager->connection();

        self::assertInstanceOf(Client::class, $return);

        self::assertArrayHasKey('main', $manager->getConnections());
    }

    public function testConnectionCache(): void
    {
        $config = ['token' => 'your-token', 'cache' => 'redis'];

        $cache = ['driver' => 'illuminate', 'connection' => 'redis', 'min' => 123, 'max' => 1234];

        $manager = self::getManagerWithCache($config, $cache);

        self::assertSame([], $manager->getConnections());

        $return = $manager->connection('oauth');

        self::assertInstanceOf(Client::class, $return);

        self::assertArrayHasKey('oauth', $manager->getConnections());
    }

    private static function getManager(array $config): BitbucketManager
    {
        $repo = Mockery::mock(Repository::class);
        $factory = Mockery::mock(BitbucketFactory::class);

        $manager = new BitbucketManager($repo, $factory);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('bitbucket.connections')->andReturn(['main' => $config]);

        $config['name'] = 'main';

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock(Client::class));

        return $manager;
    }

    private static function getManagerWithCache(array $config, array $cache): BitbucketManager
    {
        $repo = Mockery::mock(Repository::class);
        $factory = Mockery::mock(BitbucketFactory::class);
        $manager = new BitbucketManager($repo, $factory);

        $repo->shouldReceive('get')->once()
            ->with('bitbucket.connections')->andReturn(['oauth' => $config]);

        $repo->shouldReceive('get')->once()
            ->with('bitbucket.cache')->andReturn(['redis' => $cache]);

        $cache['name'] = 'redis';
        $config['name'] = 'oauth';
        $config['cache'] = $cache;

        $factory->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock(Client::class));

        return $manager;
    }
}
