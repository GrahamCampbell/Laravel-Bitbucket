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

/**
 * This is the authenticator interface.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
interface AuthenticatorInterface
{
    /**
     * Set the client to perform the authentication on.
     *
     * @param \Bitbucket\Client $client
     *
     * @return self
     */
    public function with(Client $client): AuthenticatorInterface;

    /**
     * Authenticate the client, and return it.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \Bitbucket\Client
     */
    public function authenticate(array $config): Client;
}
