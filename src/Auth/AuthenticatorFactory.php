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
        return match ($method) {
            'jwt' => new Authenticator\JwtAuthenticator(),
            'oauth' => new Authenticator\OauthAuthenticator(),
            'password' => new Authenticator\PasswordAuthenticator(),
            'private' => new Authenticator\PrivateKeyAuthenticator(),
            default => throw new InvalidArgumentException("Unsupported authentication method [$method]."),
        }
    }
}
