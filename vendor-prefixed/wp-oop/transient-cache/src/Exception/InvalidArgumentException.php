<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\WpOop\TransientCache\Exception;

use InvalidArgumentException as NativeInvalidArgumentException;
use BrianHenryIE\WP_Autologin_URLs\Psr\SimpleCache\InvalidArgumentException as PsrInvalidArgumentException;

/**
 * @inheritDoc
 */
class InvalidArgumentException extends NativeInvalidArgumentException implements PsrInvalidArgumentException
{
}
