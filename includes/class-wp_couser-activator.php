<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/darwing1210/profile
 * @since      1.0.0
 *
 * @package    Wp_couser
 * @subpackage Wp_couser/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_couser
 * @subpackage Wp_couser/includes
 * @author     Darwing Medina <darwingjavier31@yahoo.es>
 */
class Wp_couser_Activator {

	/**
	 * Create c_user_roles if not exists
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$admin_role_key = 'c_user_group_admin_role';
		$couser_admin_caps = array (
			'read' => true,
			'edit_users' => true,
			'list_users' => true,
			'create_users' => true,
			'add_users' => true,
			'delete_users' => true,
			'promote_users' => false,
		);
		if ( get_role( $admin_role_key ) ){
			remove_role( $admin_role_key );
		}
		add_role( $admin_role_key, __('Group admin' ), $couser_admin_caps);
	}

}