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

use Bitbucket\Client;
use GrahamCampbell\Bitbucket\Authenticators\PasswordAuthenticator;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;
use Mockery;

/**
 * This is the password authenticator test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class PasswordAuthenticatorTest extends AbstractTestCase
{
    public function testMakeWithMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('http_password', 'your-username', 'your-password');

        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
            'method'   => 'password',
        ]);

        $this->assertInstanceOf(Client::class, $return);
    }

    public function testMakeWithoutMethod()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('authenticate')->once()
            ->with('http_password', 'your-username', 'your-password');

        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf(Client::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The password authenticator requires a username and password.
     */
    public function testMakeWithoutUsername()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Client::class);

        $return = $authenticator->with($client)->authenticate([
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf(Client::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The password authenticator requires a username and password.
     */
    public function testMakeWithoutPassword()
    {
        $authenticator = $this->getAuthenticator();

        $client = Mockery::mock(Client::class);
        $return = $authenticator->with($client)->authenticate([
            'username' => 'your-username',
        ]);

        $this->assertInstanceOf(Client::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The client instance was not given to the password authenticator.
     */
    public function testMakeWithoutSettingClient()
    {
        $authenticator = $this->getAuthenticator();

        $return = $authenticator->authenticate([
            'username' => 'your-username',
            'password' => 'your-password',
            'method'   => 'password',
        ]);
    }

    protected function getAuthenticator()
    {
        return new PasswordAuthenticator();
    }
}
