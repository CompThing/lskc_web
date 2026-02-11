<?php

/**
 * Fired during plugin use
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Lskc_Custom
 * @subpackage Lskc_Custom/includes
 */

/**
 * Fired during plugin use
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Lskc_Custom
 * @subpackage Lskc_Custom/includes
 * @author     Your Name <email@example.com>
 */
class Lskc_Order_Queries {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
        public static function create_order_query_blocks() {
                $path = plugin_dir_path( __FILE__ ) . '/build';
                $manifest = __DIR__ . '/build/blocks-manifest.php';
                wp_register_block_types_from_metadata_collection($path, $manifest);
        }

	public static function activate() {

	}

}
