<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Lskc_Custom
 * @subpackage Lskc_Custom/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lskc_Custom
 * @subpackage Lskc_Custom/admin
 * @author     Your Name <email@example.com>
 */
class Lskc_Custom_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $lskc_custom    The ID of this plugin.
	 */
	private $lskc_custom;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $lskc_custom       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $lskc_custom, $version ) {

		$this->lskc_custom = $lskc_custom;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lskc_Custom_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lskc_Custom_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lskc_custom, plugin_dir_url( __FILE__ ) . 'css/lskc-custom-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lskc_Custom_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lskc_Custom_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lskc_custom, plugin_dir_url( __FILE__ ) . 'js/lskc-custom-admin.js', array( 'jquery' ), $this->version, false );

	}


        /**
        * Add our plugin's option page to the WP admin menu.
        */
        public function add_plugin_options_page() {
                add_options_page(
                        'Example Plugin Settings',
                        'Example Plugin Settings',
                        'manage_options',
                        'ex',
                        [$this, 'render_admin_page']
                );
        }
}
