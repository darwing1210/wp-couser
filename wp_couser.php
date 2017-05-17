<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/darwing1210/profile
 * @since             1.0.0
 * @package           Wp_couser
 *
 * @wordpress-plugin
 * Plugin Name:       WP Collaborative Users Management
 * Plugin URI:        https://github.com/darwing1210
 * Description:       This Plugin allows administrators delegate users management based on groups.
 * Version:           1.0.0
 * Author:            Darwing Medina
 * Author URI:        https://profiles.wordpress.org/darwing1210/profile
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp_couser
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp_couser-activator.php
 */
function activate_wp_couser() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp_couser-activator.php';
	Wp_couser_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp_couser-deactivator.php
 */
function deactivate_wp_couser() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp_couser-deactivator.php';
	Wp_couser_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_couser' );
register_deactivation_hook( __FILE__, 'deactivate_wp_couser' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp_couser.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_couser() {

	$plugin = new Wp_couser();
	$plugin->run();

}
run_wp_couser();
