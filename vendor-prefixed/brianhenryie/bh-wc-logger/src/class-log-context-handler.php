<?php
/**
 * Functions to augment WC_Log_Handler.
 *
 * @see WC_Log_Handler
 *
 * @package brianhenryie/bh-wc-logger
 *
 * @license GPL-2.0-or-later
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\WC_Logger;

use Psr\Log\LoggerAwareTrait;
use WC_Log_Levels;

/**
 * Filters `woocommerce_format_log_entry` to print the context.
 */
class Log_Context_Handler {
	use LoggerAwareTrait;

	protected WC_Logger_Settings_Interface $settings;

	public function __construct( WC_Logger_Settings_Interface  $settings ) {
		$this->settings = $settings;
	}

	/**
	 * The standard WooCommerce logger does not record the $context.
	 *
	 * Add context when min log level is Debug, and for Errors and worse.
	 *
	 * @hooked woocommerce_format_log_entry
	 * @see \WC_Log_Handler::format_entry()
	 *
	 * @param string                                                                              $entry The log entry already built by WooCommerce.
	 * @param array{timestamp:int, level:string, message:string, context:array<int|string,mixed>} $log_data_array The log level, message, context and timestamp in an array.
	 *
	 * @return string
	 */
	public function add_context_to_logs( string $entry, array $log_data_array ): string {

		// Only act on logs for this plugin.
		if ( ! isset( $log_data_array['context']['plugin'] ) || $this->settings->get_plugin_slug() !== $log_data_array['context']['plugin'] ) {
			return $entry;
		}

		$log_level = $this->settings->get_log_level();

		// Always record the context when it's an error, or when loglevel is DEBUG.
		if ( WC_Log_Levels::get_level_severity( $log_data_array['level'] ) < WC_Log_Levels::get_level_severity( WC_Log_Levels::ERROR )
			&& WC_Log_Levels::DEBUG !== $log_level ) {
			return $entry;
		}

		$context = $log_data_array['context'];

		// The plugin slug.
		unset( $context['plugin'] );

		return $entry . "\n" . wp_json_encode( $context );
	}
}
