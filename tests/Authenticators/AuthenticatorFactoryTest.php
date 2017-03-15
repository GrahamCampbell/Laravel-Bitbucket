<?php

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
use GrahamCampbell\Bitbucket\Authenticators\BasicAuthenticator;
use GrahamCampbell\Bitbucket\Authenticators\TokenAuthenticator;
use GrahamCampbell\Bitbucket\Authenticators\OAuthAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;

/**
 * This is the authenticator factory test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class AuthenticatorFactoryTest extends AbstractTestCase
{
    public function testMakeBasicAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('basic');

        $this->assertInstanceOf(BasicAuthenticator::class, $return);
    }

    public function testMakeTokenAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('token');

        $this->assertInstanceOf(TokenAuthenticator::class, $return);
    }

    public function testMakeOAuthAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('oauth');

        $this->assertInstanceOf(OAuthAuthenticator::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported authentication method [foo].
     */
    public function testMakeInvalidAuthenticator()
    {
        $factory = $this->getFactory();

        $return = $factory->make('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported authentication method [].
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
