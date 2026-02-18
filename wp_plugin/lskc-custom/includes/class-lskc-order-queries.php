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
	 * Create blocks for LSKC.
	 *
	 * Blocks for querying orders/bookings such as pool bookings and then
         * rendering each of the items returned by the query.
	 *
	 * @since    1.0.0
	 */
        public static function create_order_query_blocks() {
                $path = plugin_dir_path( __DIR__ ) . '/build';
                $manifest = plugin_dir_path( __DIR__ ) . '/build/blocks-manifest.php';
				/**
				 * Use the appropriate function to register block types based on the WordPress version.
				 * - For WordPress 6.5 and above, use wp_register_block_types_from_metadata_collection.
				 * - For WordPress 6.4, use wp_register_block_metadata_collection and then register each block type individually.
				 * But going back to the old way on Wordpress 6.8 and above as the new function is causing issues with block registration. See -
				 * Suspecting wp_register_block_types_from_metadata_collection is not working with php 7.4.
				 */
				if (
					function_exists( 'wp_register_block_types_from_metadata_collection') and
					(version_compare(PHP_VERSION, '8.0.0') >= 0)
					) {
					wp_register_block_types_from_metadata_collection( $path, $manifest );
				} else {
						if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
								wp_register_block_metadata_collection( $path, $manifest );
						}
						$manifest_data = require $manifest;
						foreach ( array_keys( $manifest_data ) as $block_type ) {
								register_block_type( $path .  "/src/blocks/{$block_type}" );
								error_log( "Registered block type: {$block_type}" );
						}
				}
        }

	/**
	 * Create block category for LSKC.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
        public static function create_lskc_block_category( $block_categories, $post ) {
                array_unshift( $block_categories, array(
		        'slug' => 'lskc-blocks',
		        'title' => 'LSKC'
	        ) );

	        return $block_categories;
        }

	public static function activate() {

	}

}
