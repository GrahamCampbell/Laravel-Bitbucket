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
    public function testMakeOauthAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('oauth');

        $this->assertInstanceOf(OauthAuthenticator::class, $return);
    }

    public function testMakeJwtAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('jwt');

        $this->assertInstanceOf(JwtAuthenticator::class, $return);
    }

    public function testMakePasswordAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('password');

        $this->assertInstanceOf(PasswordAuthenticator::class, $return);
    }

    public function testMakePrivateKeyAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('private');

        $this->assertInstanceOf(PrivateKeyAuthenticator::class, $return);
    }

    public function testMakeInvalidAuthenticator()
    {
        $factory = $this->getFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported authentication method [foo].');

        $factory->make('foo');
    }

    public function testMakeNoAuthenticator()
    {
        $factory = $this->getFactory();

        $this->expectException(TypeError::class);

        $factory->make(null);
    }

    protected function getFactory()
    {
        return new AuthenticatorFactory();
    }
}
