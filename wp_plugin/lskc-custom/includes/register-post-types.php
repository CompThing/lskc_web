<?php
/**
 * Register the Trip Reports post type.
 *
 * @package lskc-custom
 * @since   1.0.0
 */

namespace LskcCustom;

defined( 'ABSPATH' ) || exit;

/**
 * Register the trip_reports and attachment post types.
 */
function register_lskc_custom_post_types() {
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

add_action( 'init', __NAMESPACE__ . '\register_lskc_custom_post_types' );
