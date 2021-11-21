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

namespace GrahamCampbell\Tests\Bitbucket\Facades;

use GrahamCampbell\Bitbucket\BitbucketManager;
use GrahamCampbell\Bitbucket\Facades\Bitbucket;
use GrahamCampbell\TestBenchCore\FacadeTrait;
use GrahamCampbell\Tests\Bitbucket\AbstractTestCase;

/**
 * This is the bitbucket facade test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class BitbucketTest extends AbstractTestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'bitbucket';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return Bitbucket::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return BitbucketManager::class;
    }
}
