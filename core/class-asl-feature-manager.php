<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    ASL_Feature_Manager
 * @subpackage ASL_Feature_Manager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ASL_Feature_Manager
 * @subpackage ASL_Feature_Manager/includes
 * @author     Your Name <email@example.com>
 */
class ASL_Feature_Manager {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $ASL_Feature_Manager    The string used to uniquely identify this plugin.
	 */
	protected $ASL_Feature_Manager; //plugin slug

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the path of this plugin
	 *
	 * @since 	1.0.0
	 * @access 	protected
	 */
	protected $path;

	/**
	 * Define the URL of this plugin
	 *
	 * @since 	1.0.0
	 * @access 	protected
	 */

	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->ASL_Feature_Manager = 'asl-feature-manager';
		$this->version = '0.0.1';

		$this->load_dependences();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependences() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-asl-feature-manager-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-asl-feature-manager-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/class-asl-feature-manager-loader.php';
		$this->loader = new ASL_Feature_Manager_Loader();

	}

	private function define_admin_hooks() {

		$admin = new ASL_Feature_Manager_Admin( $this->get_version() );

		$this->loader->add_action( 'init', $admin, 'create_the_feature_post_type' );
		$this->loader->add_action( 'add_meta_boxes', $admin, 'add_feature_meta_boxes' );
		$this->loader->add_action( 'save_post', $admin, 'save_feature_meta_boxes' );
		$this->loader->add_action( 'init', $admin, 'enqueue_admin_scripts' );


	}

	private function define_public_hooks() {
		$public = new ASL_Feature_Manager_Public( $this->get_version() );
		$this->loader->add_action( 'rest_api_init', $public, 'expose_feature_meta_to_api' );
		$this->loader->add_action( 'rest_api_init', $public, 'create_feature_api_route');
		$this->loader->add_action( 'init', $public, 'expose_library_audience_taxonomy_to_api' );
	}

	public function run() {

		$this->loader->run();

	}

	public function get_version() {
		return $this->version;
	}

}
