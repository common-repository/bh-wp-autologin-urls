<?php
/**
 * Implement this interface in the settings object to indicate the plugin is a WooCommerce plugin.
 *
 * @package brianhenryie/bh-wp-logger
 *
 * @license GPL-2.0-or-later
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\WP_Logger;

use BrianHenryIE\WP_Autologin_URLs\WC_Logger\WC_Logger_Settings_Interface;

interface WooCommerce_Logger_Settings_Interface extends Logger_Settings_Interface, WC_Logger_Settings_Interface {}
