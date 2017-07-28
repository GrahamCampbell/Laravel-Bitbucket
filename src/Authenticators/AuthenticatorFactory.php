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

namespace GrahamCampbell\Bitbucket\Authenticators;

use InvalidArgumentException;

/**
 * This is the authenticator factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
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
     * @return \GrahamCampbell\Bitbucket\Authenticators\AuthenticatorInterface
     */
    public function make(string $method)
    {
        switch ($method) {
            case 'basic':
                return new BasicAuthenticator();
            case 'token':
                return new TokenAuthenticator();
            case 'oauth':
                return new OAuthAuthenticator();
        }

        throw new InvalidArgumentException("Unsupported authentication method [$method].");
    }
}
