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

namespace GrahamCampbell\Tests\Bitbucket\Authenticators;

use Bitbucket\API\Api;
use Bitbucket\API\Http\ClientInterface;
use GrahamCampbell\Bitbucket\Authenticators\BasicAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use Mockery;

/**
 * This is the basic authenticator test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class BasicAuthenticatorTest extends AbstractTestCase
{
    public function testMakeWithMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);
        $client->shouldReceive('getClient')->once()->andReturn($http = Mockery::mock(ClientInterface::class));
        $http->shouldReceive('addListener')->once();

        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
            'method'   => 'basic',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    public function testMakeWithoutMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);
        $client->shouldReceive('getClient')->once()->andReturn($http = Mockery::mock(ClientInterface::class));
        $http->shouldReceive('addListener')->once();

        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The basic authenticator requires a username and password.
     */
    public function testMakeWithoutUsername()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);

        $return = $authenticator->with($client)->authenticate([
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The basic authenticator requires a username and password.
     */
    public function testMakeWithoutBasic()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);
        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The client instance was not given to the basic authenticator.
     */
    public function testMakeWithoutSettingClient()
    {
        $authenticator = $this->getAuthenticator();

        $return = $authenticator->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
            'method'   => 'basic',
        ]);
    }

    protected function getAuthenticator()
    {
        return new BasicAuthenticator();
    }
}
