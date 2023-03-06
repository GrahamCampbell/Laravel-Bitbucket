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

namespace GrahamCampbell\Bitbucket\Auth\Authenticator;

use Bitbucket\Client;
use InvalidArgumentException;

/**
 * This is the password authenticator class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class PasswordAuthenticator extends AbstractAuthenticator
{
    /**
     * Authenticate the client, and return it.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \Bitbucket\Client
     */
    public function authenticate(array $config): Client
    {
        $client = $this->getClient();

        if (!array_key_exists('username', $config) || !array_key_exists('password', $config)) {
            throw new InvalidArgumentException('The password authenticator requires a username and password.');
        }

        $client->authenticate(Client::AUTH_HTTP_PASSWORD, $config['username'], $config['password']);

        return $client;
    }
}
