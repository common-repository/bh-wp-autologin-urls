<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\RateLimit;

use BrianHenryIE\WP_Autologin_URLs\RateLimit\Exception\LimitExceeded;

interface RateLimiter
{
    /**
     * @throws LimitExceeded
     */
    public function limit(string $identifier): void;
}
