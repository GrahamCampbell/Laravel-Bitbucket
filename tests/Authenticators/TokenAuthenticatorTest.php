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
use GrahamCampbell\Bitbucket\Authenticators\TokenAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use Mockery;

/**
 * This is the token authenticator test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class TokenAuthenticatorTest extends AbstractTestCase
{
    public function testMakeWithMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);
        $client->shouldReceive('getClient')->once()->andReturn($http = Mockery::mock(ClientInterface::class));
        $http->shouldReceive('addListener')->once();

        $return = $authenticator->with($client)->authenticate([
            'token'  => 'your-token',
            'method' => 'token',
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
            'token' => 'your-token',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The token authenticator requires a token.
     */
    public function testMakeWithoutToken()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);

        $return = $authenticator->with($client)->authenticate([]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The client instance was not given to the token authenticator.
     */
    public function testMakeWithoutSettingClient()
    {
        $authenticator = $this->getAuthenticator();

        $return = $authenticator->authenticate([
            'token'  => 'your-token',
            'method' => 'token',
        ]);
    }

    protected function getAuthenticator()
    {
        return new TokenAuthenticator();
    }
}
