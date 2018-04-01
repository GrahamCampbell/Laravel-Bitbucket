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

use Bitbucket\Client;

/**
 * This is the authenticator interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
interface AuthenticatorInterface
{
    /**
     * Set the client to perform the authentication on.
     *
     * @param \Bitbucket\Client $client
     *
     * @return \GrahamCampbell\Bitbucket\Authenticators\AuthenticatorInterface
     */
    public function with(Client $client);

    /**
     * Authenticate the client, and return it.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \Bitbucket\Client
     */
    public function authenticate(array $config);
}
