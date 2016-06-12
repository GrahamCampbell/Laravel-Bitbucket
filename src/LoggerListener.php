<?php

/*
 * This file is part of Laravel Bitbucket.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Bitbucket;

use Bitbucket\API\Http\Listener\ListenerInterface;
use Buzz\Listener\LoggerListener as BaseListener;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * This is the logger listener class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LoggerListener extends BaseListener implements ListenerInterface
{
    /**
     * Create a new logger listener instance.
     *
     * @param \Psr\Log\LoggerInterface $log
     *
     * @return void
     */
    public function __construct(LoggerInterface $log)
    {
        parent::__construct(function ($message) use ($log) {
            $log->log(LogLevel::DEBUG, $message);
        });
    }

    /**
     * Get the listener name.
     *
     * @return string
     */
    public function getName()
    {
        return 'logger';
    }
}
