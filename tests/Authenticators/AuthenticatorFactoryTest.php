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

use GrahamCampbell\Bitbucket\Authenticators\AuthenticatorFactory;
use GrahamCampbell\Bitbucket\Authenticators\OauthAuthenticator;
use GrahamCampbell\Bitbucket\Authenticators\PasswordAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;

/**
 * This is the authenticator factory test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class AuthenticatorFactoryTest extends AbstractTestCase
{
    public function testMakeOauthAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('oauth');

        $this->assertInstanceOf(OauthAuthenticator::class, $return);
    }

    public function testMakePasswordAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('password');

        $this->assertInstanceOf(PasswordAuthenticator::class, $return);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unsupported authentication method [foo].
     */
    public function testMakeInvalidAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('foo');
    }

    /**
     * @expectedException TypeError
     */
    public function testMakeNoAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make(null);
    }

    protected function getFactory()
    {
        return new AuthenticatorFactory();
    }
}
