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
use GrahamCampbell\Bitbucket\Auth\Authenticator\PasswordAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use InvalidArgumentException;
use Mockery;

/**
 * This is the password authenticator test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class PasswordAuthenticatorTest extends AbstractTestCase
{
    public function testMakeWithMethod(): void
    {
        $authenticator = new PasswordAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('http_password', 'your-username', 'your-password');

        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
            'method'   => 'password',
        ]);

        self::assertInstanceOf(Client::class, $return);
    }

    public function testMakeWithoutMethod(): void
    {
        $authenticator = new PasswordAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('http_password', 'your-username', 'your-password');

        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
        ]);

        self::assertInstanceOf(Client::class, $return);
    }

    public function testMakeWithoutUsername(): void
    {
        $authenticator = new PasswordAuthenticator();

        $client = Mockery::mock(Client::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The password authenticator requires a username and password.');

        $authenticator->with($client)->authenticate([
            'password' => 'your-password',
        ]);
    }

    public function testMakeWithoutPassword(): void
    {
        $authenticator = new PasswordAuthenticator();

        $client = Mockery::mock(Client::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The password authenticator requires a username and password.');

        $authenticator->with($client)->authenticate([
            'username' => 'your-username',
        ]);
    }

    public function testMakeWithoutSettingClient(): void
    {
        $authenticator = new PasswordAuthenticator();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The client instance was not given to the authenticator.');

        $authenticator->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
            'method'   => 'password',
        ]);
    }
}
