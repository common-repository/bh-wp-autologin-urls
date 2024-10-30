<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\WpOop\TransientCache\Exception;

use Exception;
use BrianHenryIE\WP_Autologin_URLs\Psr\SimpleCache\CacheException as CacheExceptionInterface;

/**
 * @inheritDoc
 */
class CacheException extends Exception implements CacheExceptionInterface
{
}
