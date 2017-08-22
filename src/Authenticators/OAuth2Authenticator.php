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

use Bitbucket\API\Http\Listener\OAuth2Listener;
use InvalidArgumentException;

/**
 * This is the basic authenticator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class OAuth2Authenticator extends AbstractAuthenticator implements AuthenticatorInterface
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
            throw new InvalidArgumentException('The client instance was not given to the OAuth authenticator.');
        }

        if (!array_key_exists('consumer_key', $config) || !array_key_exists('consumer_secret', $config)) {
            throw new InvalidArgumentException('The OAuth authenticator requires a consumer key and secret.');
        }

        $this->client->getClient()->addListener(new OAuth2Listener([
            'client_id'     => $config['consumer_key'],
            'client_secret' => $config['consumer_secret'],
        ]));

        return $this->client;
    }
}
