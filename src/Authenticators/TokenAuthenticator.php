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
 * This is the token authenticator class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class TokenAuthenticator extends AbstractAuthenticator implements AuthenticatorInterface
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
            throw new InvalidArgumentException('The client instance was not given to the token authenticator.');
        }

        if (!array_key_exists('token', $config)) {
            throw new InvalidArgumentException('The token authenticator requires a token.');
        }

        $this->client->getClient()->addListener(new OAuth2Listener(['access_token' => $config['token']]));

        return $this->client;
    }
}
