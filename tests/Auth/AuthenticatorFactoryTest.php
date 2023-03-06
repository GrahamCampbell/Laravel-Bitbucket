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

namespace GrahamCampbell\Tests\Bitbucket\Auth;

use GrahamCampbell\Bitbucket\Auth\Authenticator\JwtAuthenticator;
use GrahamCampbell\Bitbucket\Auth\Authenticator\OauthAuthenticator;
use GrahamCampbell\Bitbucket\Auth\Authenticator\PasswordAuthenticator;
use GrahamCampbell\Bitbucket\Auth\Authenticator\PrivateKeyAuthenticator;
use GrahamCampbell\Bitbucket\Auth\AuthenticatorFactory;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use InvalidArgumentException;
use TypeError;

/**
 * This is the authenticator factory test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AuthenticatorFactoryTest extends AbstractTestCase
{
    public function testMakeOauthAuthenticator(): void
    {
        $factory = new AuthenticatorFactory();

        self::assertInstanceOf(OauthAuthenticator::class, $factory->make('oauth'));
    }

    public function testMakeJwtAuthenticator(): void
    {
        $factory = new AuthenticatorFactory();

        self::assertInstanceOf(JwtAuthenticator::class, $factory->make('jwt'));
    }

    public function testMakePasswordAuthenticator(): void
    {
        $factory = new AuthenticatorFactory();

        self::assertInstanceOf(PasswordAuthenticator::class, $factory->make('password'));
    }

    public function testMakePrivateKeyAuthenticator(): void
    {
        $factory = new AuthenticatorFactory();

        self::assertInstanceOf(PrivateKeyAuthenticator::class, $factory->make('private'));
    }

    public function testMakeInvalidAuthenticator(): void
    {
        $factory = new AuthenticatorFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported authentication method [foo].');

        $factory->make('foo');
    }

    public function testMakeNoAuthenticator(): void
    {
        $factory = new AuthenticatorFactory();

        $this->expectException(TypeError::class);

        $factory->make(null);
    }
}
