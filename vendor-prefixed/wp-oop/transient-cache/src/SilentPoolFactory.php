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
 * Creates cache pools that throw only PSR-legal exceptions.
 */
class SilentPoolFactory implements CachePoolFactoryInterface
{
    /**
     * @var CachePoolFactoryInterface
     */
    protected $factory;

    /**
     * @param CachePoolFactoryInterface $factory A factory of possibly non-compliant cache pools.
     */
    public function __construct(CachePoolFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritDoc
     */
    public function createCachePool(string $poolName): CacheInterface
    {
        return new SilentPool($this->factory->createCachePool($poolName));
    }
}
