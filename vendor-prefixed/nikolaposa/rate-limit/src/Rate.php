<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

declare(strict_types=1);

namespace BrianHenryIE\WP_Autologin_URLs\RateLimit;

use BrianHenryIE\WP_Autologin_URLs\Assert\Assertion;

class Rate
{
    protected int $operations;
    protected int $interval;

    final protected function __construct(int $operations, int $interval)
    {
        Assertion::greaterThan($operations, 0, 'Quota must be greater than zero');
        Assertion::greaterThan($interval, 0, 'Seconds interval must be greater than zero');

        $this->operations = $operations;
        $this->interval = $interval;
    }

    public static function perSecond(int $operations)
    {
        return new static($operations, 1);
    }

    public static function perMinute(int $operations)
    {
        return new static($operations, 60);
    }

    public static function perHour(int $operations)
    {
        return new static($operations, 3600);
    }

    public static function perDay(int $operations)
    {
        return new static($operations, 86400);
    }

    public static function custom(int $operations, int $interval)
    {
        return new static($operations, $interval);
    }

    public function getOperations(): int
    {
        return $this->operations;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }
}
