<?php
/**
 * Plugin Name:       Lskc Custom
 * Description:       Customisations needed by Lothian Sea Kayak Club
 * Version:           1.0.0
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
 * @package Lskc_Custom
 * @subpackage Lskc_Custom/includes
 */

/**
 * LSKC Customisations:
 * Custom post types: trip_reports
 * Order query blocks: Query Woocommerce orders in manner of WP Query used for -
 * pool bookings
 */

class Lskc_Custom {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Lskc_Custom_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $lskc_custom    The string used to uniquely identify this plugin.
	 */
	protected $lskc_custom;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LSKC_CUSTOM_VERSION' ) ) {
			$this->version = LSKC_CUSTOM_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'lskc-custom';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lskc-custom-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-lskc-custom-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-lskc-custom-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-lskc-custom-public.php';

		$this->loader = new Lskc_Custom_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Lskc_Custom_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Lskc_Custom_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Lskc_Custom_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Lskc_Custom_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
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
}

class LskcPlugin {
    public function __construct() {
    }

    // Add our WP admin hooks.
    public function run() {
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


    // Render our plugin's option page.
    public function render_admin_page() {
        }

    // Initialize our plugin's settings.
    public function add_plugin_settings() {
        return [];
    }
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lskc-custom-activator.php
 */
function activate_lskc_custom() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lskc-custom-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lskc-custom-deactivator.php
 */
function deactivate_lskc_custom() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lskc-custom-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
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
function run_LskcPlugin() {

        $plugin = new LskcPlugin();
        $plugin->run();

}
run_LskcPlugin();

