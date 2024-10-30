<?php
/**
 * @license MIT
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\GuzzleHttp;

use BrianHenryIE\WP_Autologin_URLs\Psr\Http\Message\MessageInterface;

final class BodySummarizer implements BodySummarizerInterface
{
    /**
     * @var int|null
     */
    private $truncateAt;

    public function __construct(int $truncateAt = null)
    {
        $this->truncateAt = $truncateAt;
    }

    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string
    {
        return $this->truncateAt === null
            ? \BrianHenryIE\WP_Autologin_URLs\GuzzleHttp\Psr7\Message::bodySummary($message)
            : \BrianHenryIE\WP_Autologin_URLs\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
