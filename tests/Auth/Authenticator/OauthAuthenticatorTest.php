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

namespace GrahamCampbell\Tests\Bitbucket\Auth\Authenticators;

use Bitbucket\Client;
use GrahamCampbell\Bitbucket\Auth\Authenticator\OauthAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use InvalidArgumentException;
use Mockery;

/**
 * This is the oauth authenticator test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class OauthAuthenticatorTest extends AbstractTestCase
{
    public function testMakeWithMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('oauth_token', 'your-token');

        $return = $authenticator->with($client)->authenticate([
            'token'  => 'your-token',
            'method' => 'token',
        ]);

        $this->assertInstanceOf(Client::class, $return);
    }

    public function testMakeWithoutMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('oauth_token', 'your-token');

        $return = $authenticator->with($client)->authenticate([
            'token'  => 'your-token',
        ]);

        $this->assertInstanceOf(Client::class, $return);
    }

    public function testMakeWithoutToken()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Client::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The oauth authenticator requires a token.');

        $authenticator->with($client)->authenticate([]);
    }

    public function testMakeWithoutSettingClient()
    {
        $authenticator = $this->getAuthenticator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The client instance was not given to the oauth authenticator.');

        $authenticator->authenticate([
            'token'  => 'your-token',
            'method' => 'token',
        ]);
    }

    protected function getAuthenticator()
    {
        return new OauthAuthenticator();
    }
}
