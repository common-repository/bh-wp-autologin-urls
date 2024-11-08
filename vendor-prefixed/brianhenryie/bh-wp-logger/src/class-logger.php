<?php
/**
 * Instantiate the logger for your plugin.
 *
 * `$logger = \BrianHenryIE\WP_Autologin_URLs\WP_Logger\Logger::instance()`
 * better:
 * `$logger = \BrianHenryIE\WP_Autologin_URLs\WP_Logger\Logger::instance( $settings )`
 *
 * @see \BrianHenryIE\WP_Autologin_URLs\WP_Logger\Logger_Settings_Interface
 * @see \BrianHenryIE\WP_Autologin_URLs\WP_Logger\Logger_Settings_Trait
 * @see \BrianHenryIE\WP_Autologin_URLs\WP_Logger\WooCommerce_Logger_Settings_Interface
 *
 * @package brianhenryie/bh-wp-logger
 *
 * @license GPL-2.0-or-later
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\WP_Logger;

use BrianHenryIE\WP_Autologin_URLs\WC_Logger\Log_Context_Handler;
use BrianHenryIE\WP_Autologin_URLs\WC_Logger\WC_PSR_Logger;
use BrianHenryIE\WP_Autologin_URLs\WP_Logger\API\BH_WP_PSR_Logger;
use BrianHenryIE\WP_Autologin_URLs\WP_Logger\WP_Includes\Plugin_Logger_Actions;
use BrianHenryIE\WP_Autologin_URLs\WP_Private_Uploads\BH_WP_Private_Uploads_Hooks;
use BrianHenryIE\WP_Autologin_URLs\WP_Private_Uploads\Private_Uploads_Settings_Interface;
use BrianHenryIE\WP_Autologin_URLs\WP_Private_Uploads\Private_Uploads_Settings_Trait;
use BrianHenryIE\WP_Autologin_URLs\WP_Private_Uploads\Private_Uploads;
use BrianHenryIE\WP_Autologin_URLs\Katzgrau\KLogger\Logger as KLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Wraps parent class in a singleton so it only needs to be configured once.
 */
class Logger extends BH_WP_PSR_Logger implements API_Interface, LoggerInterface {

	/**
	 * Singleton.
	 *
	 * @var Logger
	 */
	protected static Logger $instance;

	/**
	 * Initialize the logger and store the instance in the singleton variable.
	 * Settings are used when provided, inferred when null.
	 * Ideally settings should be provided the first time the logger is instantiated, then they do not need
	 * to be provided when accessing the singleton later on.
	 *
	 * @param ?Logger_Settings_Interface $settings The loglevel, plugin name, slug, and basename.
	 *
	 * @return Logger
	 * @see Logger_Settings
	 * @see Plugins
	 */
	public static function instance( ?Logger_Settings_Interface $settings = null ): LoggerInterface {

		if ( ! isset( self::$instance ) ) {

			// Zero-config.
			$settings = $settings ?? new class() implements Logger_Settings_Interface {
				use Logger_Settings_Trait;
			};

			// TODO: This is wrong, the directory must be assumed to contain files and be kept private.
			if ( 'none' === $settings->get_log_level() ) {
				return new NullLogger();
			}

			$logger = new self( $settings );

			self::$instance = $logger;

			// Add the hooks.
			new Plugin_Logger_Actions( self::$instance, $settings, self::$instance );
		}

		return self::$instance;
	}

	/**
	 * If log level is 'none', use NullLogger.
	 * If Settings is WooCommerce_Logger_Settings_Interface use WC_Logger.
	 * Otherwise use KLogger.
	 *
	 * @param Logger_Settings_Interface $settings Basic settings required for the logger.
	 */
	public function __construct( Logger_Settings_Interface $settings ) {

		if ( $settings instanceof WooCommerce_Logger_Settings_Interface
			&& in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ), true ) ) {
			// Does not use `is_plugin_active()` here because "Call to undefined function" error (maybe an admin function).

			$logger = new WC_PSR_Logger( $settings );

			// Add context to WooCommerce logs.
			$wc_log_handler = new Log_Context_Handler( $settings );
			add_filter( 'woocommerce_format_log_entry', array( $wc_log_handler, 'add_context_to_logs' ), 10, 2 );

			// TODO: What's the log file name when it's a wc-log?

		} else {

			$log_directory       = wp_normalize_path( WP_CONTENT_DIR . '/uploads/logs' );
			$log_level_threshold = $settings->get_log_level();

			/**
			 * Add the `{context}` template string,
			 * then provide `'appendContext' => false` to Klogger (since it is already takes care of).
			 *
			 * @see \BrianHenryIE\WP_Autologin_URLs\Katzgrau\KLogger\Logger::formatMessage()
			 */
			$log_format = "{date} {level} {message}\n{context}";

			/**
			 * `c` is chosen to match WooCommerce's choice.
			 *
			 * @see WC_Log_Handler::format_time()
			 */
			$options = array(
				'extension'     => 'log',
				'prefix'        => "{$settings->get_plugin_slug()}-",
				'dateFormat'    => 'c',
				'logFormat'     => $log_format,
				'appendContext' => false,
			);

			$logger = new KLogger( $log_directory, $log_level_threshold, $options );

			// Make the logs directory inaccessible to the public.
			$private_uploads_settings = new class( $settings ) implements Private_Uploads_Settings_Interface {
				use Private_Uploads_Settings_Trait;

				/**
				 * The settings provided for the logger. We need the plugin slug as a uid for the private uploads instance.
				 *
				 * @var Logger_Settings_Interface
				 */
				protected Logger_Settings_Interface $logger_settings;

				/**
				 * Constructor.
				 *
				 * @param Logger_Settings_Interface $logger_settings The plugin logger settings, whose plugin slug we need.
				 */
				public function __construct( Logger_Settings_Interface $logger_settings ) {
					$this->logger_settings = $logger_settings;
				}

				/**
				 * This is used as a unique id for the Private Uploads instance.
				 */
				public function get_plugin_slug(): string {
					return $this->logger_settings->get_plugin_slug() . '_logger';
				}

				/**
				 * Use wp-content/uploads/logs as the logs directory.
				 */
				public function get_uploads_subdirectory_name(): string {
					return 'logs';
				}
			};

			// Don't use the Private_Uploads singleton in case the parent plugin also needs it.
			$private_uploads = new Private_Uploads( $private_uploads_settings, $this );
			new BH_WP_Private_Uploads_Hooks( $private_uploads, $private_uploads_settings, $this );

		}

		parent::__construct( $settings, $logger );
	}
}
