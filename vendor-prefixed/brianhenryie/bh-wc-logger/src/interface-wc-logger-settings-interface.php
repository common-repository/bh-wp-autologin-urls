<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by Brian Henry on 26-May-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

namespace BrianHenryIE\WP_Autologin_URLs\WC_Logger;

interface WC_Logger_Settings_Interface
{
	public function get_plugin_slug();

	public function get_log_level();
}