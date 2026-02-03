<?php
/**
 * Plugin Name:       Lskc Custom
 * Description:       Customisations needed by Lothian Sea Kayak Club
 * Version:           0.1.0
 * Requires at least: 6.7
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

namespace Lskc;
require "order-query-block.php";

if ( !defined( "ABSPATH" ) ) {
    exit; // Exit if accessed directly
}

class LskcPlugin {
    public function __construct() {
    }

    // Add our WP admin hooks.
    public function load() {
        add_action('admin_menu', [$this, 'add_plugin_options_page']);
        add_action( 'init', [$this, 'lskc_custom_post_types']);
        add_action( 'init', 'create_block_lskc_custom_block_init' );
        add_action('admin_init', [$this, 'add_plugin_settings']);
    }

    // Add our plugin's option page to the WP admin menu.
    public function add_plugin_options_page() {
        add_options_page(
            'Example Plugin Settings',
            'Example Plugin Settings',
            'manage_options',
            'ex',
            [$this, 'render_admin_page']
        );
    }

	// Custom post types: attachment trip_reports
    public function lskc_custom_post_types() {
        register_post_type(
            'attachment',
            array(
                'labels'      => array(
                    'name'          => __('Attachments', 'textdomain'),
                    'singular_name' => __('Attachment', 'textdomain'),
                ),
                'public'      => true,
                'has_archive' => true,
                'show_in_rest' => true,
            )
        );
        register_post_type('trip_reports',
            array(
                'labels'      => array(
                    'name'          => __('Trips', 'textdomain'),
                    'singular_name' => __('Trip', 'textdomain'),
                ),
                'public'      => true,
                'has_archive' => true,
                'show_in_rest' => true,
            )
        );
    }

    // Render our plugin's option page.
    public function render_admin_page() {
        }

    // Initialize our plugin's settings.
    public function add_plugin_settings() {
        return [];
    }
}

// Load our plugin within the WP admin dashboard.
if (is_admin()) {
    $plugin = new LskcPlugin();
    $plugin->load();
}
