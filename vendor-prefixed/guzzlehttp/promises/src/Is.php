<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     */
    public static function pending(PromiseInterface $promise): bool
    {
        return $promise->getState() === PromiseInterface::PENDING;
    }

    /**
     * Returns true if a promise is fulfilled or rejected.
     */
    public static function settled(PromiseInterface $promise): bool
    {
        return $promise->getState() !== PromiseInterface::PENDING;
    }

    /**
     * Returns true if a promise is fulfilled.
     */
    public static function fulfilled(PromiseInterface $promise): bool
    {
        return $promise->getState() === PromiseInterface::FULFILLED;
    }

    /**
     * Returns true if a promise is rejected.
     */
    public static function rejected(PromiseInterface $promise): bool
    {
        return $promise->getState() === PromiseInterface::REJECTED;
    }
}
