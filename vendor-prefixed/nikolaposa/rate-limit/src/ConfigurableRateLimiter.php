<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\RateLimit;

abstract class ConfigurableRateLimiter
{
    protected Rate $rate;

    public function __construct(Rate $rate)
    {
        $this->rate = $rate;
    }
}
