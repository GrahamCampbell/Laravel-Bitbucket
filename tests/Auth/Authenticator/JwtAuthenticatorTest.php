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
use GrahamCampbell\Bitbucket\Auth\Authenticator\JwtAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use InvalidArgumentException;
use Mockery;

/**
 * This is the jwt authenticator test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 * @author Lucas Michot <lucas@semalead.com>
 */
class JwtAuthenticatorTest extends AbstractTestCase
{
    public function testMakeWithMethod(): void
    {
        $authenticator = new JwtAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('your-token', 'jwt');

        $return = $authenticator->with($client)->authenticate([
            'token'  => 'your-token',
            'method' => 'jwt',
        ]);

        self::assertInstanceOf(Client::class, $return);
    }

    public function testMakeWithoutMethod(): void
    {
        $authenticator = new JwtAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('your-token', 'jwt');

        $return = $authenticator->with($client)->authenticate([
            'token'  => 'your-token',
        ]);

        self::assertInstanceOf(Client::class, $return);
    }

    public function testMakeWithoutToken(): void
    {
        $authenticator = new JwtAuthenticator();

        $client = Mockery::mock(Client::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The jwt authenticator requires a token.');

        $authenticator->with($client)->authenticate([]);
    }

    public function testMakeWithoutSettingClient(): void
    {
        $authenticator = new JwtAuthenticator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The client instance was not given to the authenticator.');

        $authenticator->authenticate([
            'token'  => 'your-token',
            'method' => 'jwt',
        ]);
    }
}
