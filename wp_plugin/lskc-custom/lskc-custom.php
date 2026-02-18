<?php
/**
 * Plugin Name:       Lskc Custom
 * Description:       Customisations needed by Lothian Sea Kayak Club
 * Version:           1.0.0
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author: Michael Wilkinson
 * Author URI: https://computething.co.uk
 * License: MIT
 * References: https://keygen.sh/docs/integrations/wordpress/
 * Plugin URI: https://github.com/CompThing/lskc_web/tree/main/wp_plugin/lskc-custom
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lskc-custom
 *
 * @package CreateBlock
 */

/**
 * LSKC Customisations:
 * Custom post types: trip_reports
 * Order query blocks: Query Woocommerce orders in manner of WP Query used for -
 * pool bookings
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LSKC_CUSTOM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lskc-custom-activator.php
 */
function activate_lskc_custom() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lskc-custom-activator.php';
	Lskc_Custom_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lskc-custom-deactivator.php
 */
function deactivate_lskc_custom() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lskc-custom-deactivator.php';
	Lskc_Custom_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lskc_custom' );
register_deactivation_hook( __FILE__, 'deactivate_lskc_custom' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lskc-custom.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lskc_custom() {

        $plugin = new Lskc_Custom();
        $plugin->run();

}
run_lskc_custom();

