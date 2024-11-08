<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\GuzzleHttp\Promise;

interface TaskQueueInterface
{
    /**
     * Returns true if the queue is empty.
     */
    public function isEmpty(): bool;

    /**
     * Adds a task to the queue that will be executed the next time run is
     * called.
     */
    public function add(callable $task): void;

    /**
     * Execute all of the pending task in the queue.
     */
    public function run(): void;
}
