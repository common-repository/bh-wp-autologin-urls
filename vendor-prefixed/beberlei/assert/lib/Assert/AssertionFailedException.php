<?php

/**
 * Assert
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\Assert;

use Throwable;

interface AssertionFailedException extends Throwable
{
    /**
     * @return string|null
     */
    public function getPropertyPath();

    /**
     * @return mixed
     */
    public function getValue();

    public function getConstraints(): array;
}
