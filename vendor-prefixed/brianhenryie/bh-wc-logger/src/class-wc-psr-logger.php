<?php
/**
 * A PSR wrapper for WC_Logger.
 *
 * Attempts to run wc_get_logger(), if unavailable, tries again on `woocommerce_loaded` hook.
 * If a message is logged before then, it is queued for later.
 *
 * WooCommerce takes care of deleting old logs.
 * Log files are visible inside the WooCommerce logs viewer.
 *
 * @package brianhenryie/bh-wc-logger
 *
 * @license GPL-2.0-or-later
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\WC_Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use WC_Log_Levels;
use WC_Logger_Interface;

/**
 * PSR LoggerInterface passing log messages to wc_logger().
 *
 * @see wc_get_logger()
 * @see \WC_Logger
 */
class WC_PSR_Logger implements LoggerInterface {
	use LoggerTrait;

	protected WC_Logger_Settings_Interface $settings;

	/**
	 * The wc_logger instance that takes care of saving the logs.
	 */
	protected ?WC_Logger_Interface $wc_logger = null;

	/**
	 * Wraps wc_logger in a PSR compatible logger.
	 *
	 * Attempts to instantiate a wc_logger immediately. if it is too early, adds an action to do so
	 * after WooCommerce has loaded.
	 */
	public function __construct( WC_Logger_Settings_Interface $settings ) {
		$this->settings = $settings;
		if ( ! did_action( 'woocommerce_loaded' ) ) {
			$instantiate_wc_logger = function (): void {
				$this->wc_logger = wc_get_logger();
			};
			add_action( 'woocommerce_loaded', $instantiate_wc_logger, 1 );
		} else {
			$this->wc_logger = wc_get_logger();
		}
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * If woocommerce_loaded hook has not run, a closure is added and executed later to record the log.
	 *
	 * @see WC_Log_Levels
	 * @see \PSR\Log\LogLevel
	 *
	 * @param string  $level The severity.
	 * @param string  $message The human-readable message.
	 * @param mixed[] $context An array of data to be logged alongside the message.
	 *
	 * @return void
	 */
	public function log( $level, $message, array $context = array() ) {

		// If this is true we are before woocommerce_loaded.
		if ( ! isset( $this->wc_logger ) ) {

			// Re-run this same function after the logger has been initialized.
			$re_run_log = function() use ( $level, $message, $context ) {
				$this->log( $level, $message, $context );
			};
			add_action( 'woocommerce_loaded', $re_run_log, 2 );

			return;
		}

		$log_level = $this->settings->get_log_level();

		if ( WC_Log_Levels::get_level_severity( $level ) < WC_Log_Levels::get_level_severity( $log_level ) ) {
			return;
		}

		$context['plugin'] = $this->settings->get_plugin_slug();

		$this->wc_logger->$level( $message, $context );
	}
}
