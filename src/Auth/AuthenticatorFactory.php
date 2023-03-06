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

namespace GrahamCampbell\Bitbucket\Auth;

use GrahamCampbell\Bitbucket\Auth\Authenticator\AuthenticatorInterface;
use InvalidArgumentException;

/**
 * This is the authenticator factory class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class AuthenticatorFactory
{
    /**
     * Make a new authenticator instance.
     *
     * @param string $method
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Bitbucket\Auth\Authenticator\AuthenticatorInterface
     */
    public function make(string $method): AuthenticatorInterface
    {
        switch ($method) {
            case 'jwt':
                return new Authenticator\JwtAuthenticator();
            case 'oauth':
                return new Authenticator\OauthAuthenticator();
            case 'password':
                return new Authenticator\PasswordAuthenticator();
            case 'private':
                return new Authenticator\PrivateKeyAuthenticator();
        }

        throw new InvalidArgumentException("Unsupported authentication method [$method].");
    }
}
