<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\GuzzleHttp;

use BrianHenryIE\WP_Autologin_URLs\Psr\Http\Message\RequestInterface;
use BrianHenryIE\WP_Autologin_URLs\Psr\Http\Message\ResponseInterface;

interface MessageFormatterInterface
{
    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface       $request  Request that was sent
     * @param ResponseInterface|null $response Response that was received
     * @param \Throwable|null        $error    Exception that was received
     */
    public function format(RequestInterface $request, ResponseInterface $response = null, \Throwable $error = null): string;
}
