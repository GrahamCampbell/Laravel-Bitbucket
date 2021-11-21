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
    public function testCreateConnection()
    {
        $config = ['token' => 'your-token'];

        $manager = $this->getManager($config);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('bitbucket.default')->andReturn('main');

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection();

        $this->assertInstanceOf(Client::class, $return);

        $this->assertArrayHasKey('main', $manager->getConnections());
    }

    public function testConnectionCache()
    {
        $config = ['token' => 'your-token', 'cache' => 'redis'];

        $cache = ['driver' => 'illuminate', 'connection' => 'redis', 'min' => 123, 'max' => 1234];

        $manager = $this->getManagerWithCache($config, $cache);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('oauth');

        $this->assertInstanceOf(Client::class, $return);

        $this->assertArrayHasKey('oauth', $manager->getConnections());
    }

    protected function getManager(array $config)
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

    protected function getManagerWithCache(array $config, array $cache)
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
