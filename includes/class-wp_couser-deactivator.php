<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://profiles.wordpress.org/darwing1210/profile
 * @since      1.0.0
 *
 * @package    Wp_couser
 * @subpackage Wp_couser/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_couser
 * @subpackage Wp_couser/includes
 * @author     Darwing Medina <darwingjavier31@yahoo.es>
 */
class Wp_couser_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$admin_role_key = 'c_user_group_admin_role';

		if ( get_role( $admin_role_key ) ){
			remove_role( $admin_role_key );
		}

	}

}
