<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\WpOop\TransientCache;

use BrianHenryIE\WP_Autologin_URLs\Psr\SimpleCache\CacheInterface;

/**
 * A factory that can create cache pool.
 */
interface CachePoolFactoryInterface
{
    /**
     * Creates a new cache pool.
     *
     * @param string $poolName The unique pool name.
     *
     * @return CacheInterface The new pool.
     */
    public function createCachePool(string $poolName): CacheInterface;
}
