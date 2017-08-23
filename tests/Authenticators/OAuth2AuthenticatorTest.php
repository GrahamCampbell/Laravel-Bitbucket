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
use GrahamCampbell\Bitbucket\Authenticators\OAuth2Authenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use Mockery;

/**
 * This is the OAuth authenticator test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class OAuth2AuthenticatorTest extends AbstractTestCase
{
    public function testMakeWithMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);
        $client->shouldReceive('getClient')->once()->andReturn($http = Mockery::mock(ClientInterface::class));
        $http->shouldReceive('addListener')->once();

        $return = $authenticator->with($client)->authenticate([
            'consumer_key'    => 'your-key',
            'consumer_secret' => 'your-secret',
            'method'          => 'oauth2',
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
            'consumer_key'    => 'your-key',
            'consumer_secret' => 'your-secret',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The OAuth authenticator requires a consumer key and secret.
     */
    public function testMakeWithoutKey()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);

        $return = $authenticator->with($client)->authenticate([
            'consumer_secret' => 'your-secret',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The OAuth authenticator requires a consumer key and secret.
     */
    public function testMakeWithoutSecret()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Api::class);
        $return = $authenticator->with($client)->authenticate([
            'consumer_key' => 'your-key',
        ]);

        $this->assertInstanceOf(Api::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The client instance was not given to the OAuth authenticator.
     */
    public function testMakeWithoutSettingClient()
    {
        $authenticator = $this->getAuthenticator();

        $return = $authenticator->authenticate([
            'consumer_key'    => 'your-key',
            'consumer_secret' => 'your-secret',
            'method'          => 'oauth2',
        ]);
    }

    protected function getAuthenticator()
    {
        return new OAuth2Authenticator();
    }
}
