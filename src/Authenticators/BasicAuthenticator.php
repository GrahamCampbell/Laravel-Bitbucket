<?php

/*
 * This file is part of Laravel Bitbucket.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Bitbucket\Authenticators;

use Bitbucket\API\Http\Listener\BasicAuthListener;
use InvalidArgumentException;

/**
 * This is the basic authenticator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class BasicAuthenticator extends AbstractAuthenticator implements AuthenticatorInterface
{
    /**
     * Authenticate the client, and return it.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \Bitbucket\API\Api
     */
    public function authenticate(array $config)
    {
        if (!$this->client) {
            throw new InvalidArgumentException('The client instance was not given to the basic authenticator.');
        }

        if (!array_key_exists('username', $config) || !array_key_exists('password', $config)) {
            throw new InvalidArgumentException('The basic authenticator requires a username and password.');
        }

        $this->client->getClient()->addListener(new BasicAuthListener($config['username'], $config['password']));

        return $this->client;
    }
}
