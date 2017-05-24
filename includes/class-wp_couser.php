<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/darwing1210/profile
 * @since      1.0.0
 *
 * @package    Wp_couser
 * @subpackage Wp_couser/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_couser
 * @subpackage Wp_couser/includes
 * @author     Darwing Medina <darwingjavier31@yahoo.es>
 */
class Wp_couser {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_couser_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

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

		$this->plugin_name = 'wp_couser';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_couser_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_couser_i18n. Defines internationalization functionality.
	 * - Wp_couser_Admin. Defines all hooks for the admin area.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp_couser-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp_couser-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp_couser-admin.php';

		$this->loader = new Wp_couser_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_couser_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_couser_i18n();

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

		$plugin_admin = new Wp_couser_Admin( $this->get_plugin_name(), $this->get_version() );
		
		// Styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// Post Type
		$this->loader->add_action( 'init', $plugin_admin, 'register_c_user_group_cpt' );
		
		// Metabox
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_user_group_admin_metabox' );
		$this->loader->add_action( 'save_post_c_user_group', $plugin_admin, 'save_user_group_admins_callback', 10, 3);

		// User profile
		$this->loader->add_filter( 'woocommerce_disable_admin_bar', $plugin_admin, 'allow_c_user_group_admin_wp_admin_access', 10, 1);
		$this->loader->add_filter( 'woocommerce_prevent_admin_access', $plugin_admin, 'allow_c_user_group_admin_wp_admin_access', 10, 1);
		
		$this->loader->add_action( 'user_new_form', $plugin_admin, 'add_user_group_field', 10, 1 );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'add_user_group_field', 10, 1 );
		$this->loader->add_action( 'edit_user_profile', $plugin_admin, 'add_user_group_field', 10, 1 );

		$this->loader->add_action( 'user_register', $plugin_admin, 'save_c_user_group', 10, 1 );
		$this->loader->add_action( 'profile_update', $plugin_admin, 'save_c_user_group', 10, 1 );

		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'add_c_user_group_column', 10, 1 );
		$this->loader->add_action( 'manage_users_custom_column', $plugin_admin, 'show_c_user_group_column_content', 10, 3 );
		$this->loader->add_filter( 'editable_roles', $plugin_admin, 'c_user_filter_roles', 10, 1 );
		$this->loader->add_filter( 'map_meta_cap', $plugin_admin, 'c_user_map_meta_cap', 10, 4 );
		$this->loader->add_action( 'pre_user_query', $plugin_admin, 'c_user_group_pre_user_query', 10, 1 );
		$this->loader->add_action( 'delete_user', $plugin_admin, 'delete_c_user_group', 10, 1 );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'hide_user_count' );

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
	 * @return    Wp_couser_Loader    Orchestrates the hooks of the plugin.
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

}
