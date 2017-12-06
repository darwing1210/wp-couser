<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/darwing1210/profile
 * @since      1.0.0
 *
 * @package    Wp_couser
 * @subpackage Wp_couser/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_couser
 * @subpackage Wp_couser/admin
 * @author     Darwing Medina <darwingjavier31@yahoo.es>
 */
class Wp_couser_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The group admin role slug.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $group_admin_role_slug 	The user groups admin role.
	 */
	private $group_admin_role_slug = 'c_user_group_admin_role';

	/**
	 * The meta admin_key.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $group_admins_meta_key 	The meta key for groups admin.
	 */
	private $group_admins_meta_key = 'c_user_group_admin';

	/**
	 * The meta user group key.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $user_group_meta_key 	The meta key for user group.
	 */
	private $user_group_meta_key = 'c_user_group';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register Post Type: User groups.
	 *
	 * @since    1.0.0
	 * @return   array of groups.
	 */
	public function get_c_user_groups() {
		$args = array(
			'posts_per_page'=> -1,
		    'post_type'=> $this->user_group_meta_key,
		    'order'    => 'ASC'
		);
		return get_posts( $args );
	}

	public function get_user_group_id( $user_id ) {
		return get_user_meta( $user_id, $this->user_group_meta_key, true);
	}

	public function get_user_group( $user_id ) {
		return get_post( $this->get_user_group_id( $user_id ) );
	}

	/**
	 * Helper that removes role from user only if you are admin
	 * @param  int $user_id
	 *         string $role. 
	 *
	 * @since     1.0.0
	 */
	public function set_user_role( $user_id, $role ) {
		if ( current_user_can( 'promote_users' ) ) {
			$user = new WP_User( $user_id );
        	$user->set_role( $role );
		}
	}

	/**
	 * Helper that removes role from user only if you are admin
	 * @param  int $user_id
	 *         string $role. 
	 *
	 * @since     1.0.0
	 */
	public function remove_user_from_role( $user_id, $role ) {
		if ( current_user_can( 'administrator' ) ) {
			$user = new WP_User( $user_id );
        	$user->remove_role( $role );
		}
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
		 * defined in Wp_couser_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_couser_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'vendor/chosen/chosen.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp_couser-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wp_couser_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_couser_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'chosen', plugin_dir_url( __FILE__ ) . 'vendor/chosen/chosen.jquery.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp_couser-admin.js', array( 'chosen', 'jquery' ), $this->version, false );

	}

	/**
	 * Allow the "c_user_group_admin" role access to the WordPress Admin and bar
	 * 
	 * @param  boolean $prevent_access
	 *         Woocommerce parameter. 
	 *         -true:  doesn't have access
	 *         -false: have access
	 *         
	 * @return boolean
	 *         If woocommerce prevent the access to the admin bar.
	 */
	public function allow_c_user_group_admin_wp_admin_access( $prevent_access )
	{
		// if( ! current_user_can( $this->group_admin_role_slug ) )
		// 	return $prevent_access;
		// return false;
		return false;
	}

	/**
	 * Register Post Type: User groups.
	 *
	 * @since    1.0.0
	 */
	public function register_c_user_group_cpt() {

		$labels = array(
			"name" => __( 'User groups', '' ),
			"singular_name" => __( 'User group', '' ),
			"all_items" => __( 'User Groups', '' ),
		);

		$args = array(
			"label" => __( 'User groups', '' ),
			"labels" => $labels,
			"description" => "Collaborate User group",
			"public" => true,
			"publicly_queryable" => false,
			"show_ui" => true,
			"show_in_rest" => false,
			"rest_base" => "",
			"has_archive" => false,
			"show_in_menu" => "users.php",
			"exclude_from_search" => true,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => array( "slug" => "c_user_group", "with_front" => true ),
			"query_var" => true,
			"supports" => array( "title", "editor", "thumbnail" ),
		);

		register_post_type( "c_user_group", $args );
	}

	/**
	 * User groups Metabox
	 * Adds a Metabox to select User groups
	 *
	 * @since     1.0.0
	 */
	public function add_user_group_admin_metabox() {
		
		add_meta_box( 
			'c_user_group_admin_metabox', 
			'Administrators', 
			array($this,'render_user_group_admins_field'),
			'c_user_group', 
			'side', 
			'high' 
		);	
	}
	
	/**
	 * User groups render
	 * Renders admin multiple select dropdown
	 *
	 * @since     1.0.0
	 */

	public function render_user_group_admins_field( $post ) {
		
		$group_admins_meta_key = $this->group_admins_meta_key;
 
 		wp_nonce_field( 'add_user_group_admin_metabox', 'user_group_admin_metabox_nonce' );
		$dropdown_users_args = array(
		    'meta_key'     => $this->user_group_meta_key,
            'meta_value'   => $post->ID,
		); // Only show users that belong to this group
		$users = get_users( $dropdown_users_args );
		
		$output = '<p>Only shows users that belong to this group</p>';

		$output .= sprintf( '<select multiple name="%1$s[]" id="%1$s" class="chosen">', $group_admins_meta_key );
		 
		$current_group_admins = get_post_meta( $post->ID, $group_admins_meta_key );

		foreach ( $users as $user ) {
			$_selected = '';
			
			if ( in_array( $user->ID, $current_group_admins ) ) {
				$_selected = 'selected';
			}
			$output .= sprintf( '<option value="%1$s" %2$s>%3$s</option>', $user->ID, $_selected, $user->user_login );
		}

		$output .= '</select>';

		if ( ! empty( $output ) ) {
			echo $output;
		}
	}
	
	/**
	 * Save Group callback
	 * Actions to execute when saving user group
	 *
	 * @since     1.0.0
	 */

	public function save_user_group_admins_callback( $post_id, $post, $update ) {
	    // TODO store groups admins meta_keys as serialized array

		$group_admins_meta_key = $this->group_admins_meta_key;
		// Verifying nonce
		if ( ! isset( $_POST['user_group_admin_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['user_group_admin_metabox_nonce'], 'add_user_group_admin_metabox' ) || ! current_user_can( 'administrator' ) ) {
	        return;
	    }

		$previous_admins = get_post_meta( $post_id, $group_admins_meta_key, false );
		
		if ( isset( $_POST[$group_admins_meta_key] ) ) {

			// Validating if previous users were selected if not, remove from group_admin_role
			foreach ( $previous_admins as $admin_id ) {
				if ( ! in_array( $admin_id, $_POST[$group_admins_meta_key] ) ) {
					$this->remove_user_from_role( $admin_id, $this->group_admin_role_slug );
					$this->set_user_role( $admin_id, 'subscriber' );
			        delete_post_meta( $post_id, $group_admins_meta_key, $admin_id );
				}
			}

	        foreach ( $_POST[$group_admins_meta_key] as $selected_user_id ) {
	        	$this->set_user_role( $selected_user_id, $this->group_admin_role_slug );
		        add_post_meta( $post_id, $group_admins_meta_key, $selected_user_id, false );
	        }
	    } else {
	        delete_post_meta( $post_id, $group_admins_meta_key );
	        foreach ( $previous_admins as $admin_id ) {
	        	$this->remove_user_from_role( $admin_id, $this->group_admin_role_slug );
	        	$this->set_user_role( $admin_id, 'subscriber' );
			}
	    }
	}

	/**
	 * Renders user group field to users create and update
	 *
	 * @since     1.0.0
	 */

	public function add_user_group_field( $user ) { 
	
		$user_group_meta_key = $this->user_group_meta_key;

	?>
		<hr>
		<h2>User group</h2>
		<table class="form-table">
	   	<tr>
	   		<th><label for="user-group">User group</label></th>
	   		<td>

	   			<?php

	   			if ( current_user_can( $this->group_admin_role_slug ) ) {
	   				$current_user_group = $this->get_user_group( get_current_user_id() );
	   				printf( '<select id="%1$s" name="%1$s" disabled="disabled">', $user_group_meta_key );
	   				printf( '<option value="%1$s" selected>%2$s</option>', $current_user_group->ID, $current_user_group->post_title );
	   			}
	   			else if ( current_user_can( 'administrator' ) ) {
	   				printf( '<select id="%1$s" name="%1$s">', $user_group_meta_key );
	   				echo '<option value>User group</option>'; // Empty Option
		   			if ( isset( $user->ID ) ) {
			   			$user_group = get_user_meta( $user->ID, $user_group_meta_key );
			   		}
			   		$groups = $this->get_c_user_groups();

					foreach ($groups as $group) {
			   			$_selected = '';	
						if ( isset( $user_group ) ) {
							if ( in_array( $group->ID, $user_group ) ) {
								$_selected = 'selected';
							}
						}
						printf( '<option value="%1$s" %2$s>%3$s</option>', $group->ID, $_selected, $group->post_title );
					}
				} 
				echo "</select>";
				?>
	   		</td>
	   	</tr>
	    </table>
	    <hr>
	<?php }

	/**
	 * Stores user group data in user meta
	 *
	 * @since     1.0.0
	 */

	public function save_c_user_group( $user_id ) {
		$user_group_meta_key = $this->user_group_meta_key;

		// Grant user as c_user_admin_group if admin set it from add/update user
		if ( isset( $_POST['role'] ) && isset( $_POST[$user_group_meta_key] ) ) {
			if ( $_POST['role'] == $this->group_admin_role_slug ) {
				if ( current_user_can( 'administrator' ) ) { // only admin can update groups admins
					$group_admins = get_post_meta( $_POST[$user_group_meta_key], $this->group_admins_meta_key, false );
					if ( ! in_array( $user_id, $group_admins ) ) {
						add_post_meta( $_POST[$user_group_meta_key], $this->group_admins_meta_key, $user_id );
					}
				}
			}
			else { // If change role, remove user from current group admins
				if ( $this->get_user_group_id( $user_id ) ) {
					delete_post_meta(  
						$this->get_user_group_id( $user_id ), 
						$this->group_admins_meta_key,
						$user_id
					);
				}
			}
		}
		
		// Validate if user added group admin without group
		if ( isset( $_POST['role'] ) && empty( $_POST[$user_group_meta_key] ) ) {
			if ( $_POST['role'] == $this->group_admin_role_slug ) { // Prevent add a group admin without group
				$this->set_user_role( $user_id, 'subscriber' );

				if ( $this->get_user_group_id( $user_id ) ) { // in case user had group
					delete_post_meta(  
						$this->get_user_group_id( $user_id ), 
						$this->group_admins_meta_key,
						$user_id
					);
				}
			}
		}
		
		if ( current_user_can( 'administrator' ) ) {
			if ( isset( $_POST[$user_group_meta_key] ) ) {
		   		update_user_meta( $user_id, $user_group_meta_key, $_POST[$user_group_meta_key] );
	   		}
	   		else {
	   			delete_user_meta( $user_id, $user_group_meta_key );
				$this->remove_user_from_role( $user_id, $this->group_admin_role_slug );
	   		}
   		} 
   		else if ( current_user_can( $this->group_admin_role_slug ) ) {
			update_user_meta( $user_id, $user_group_meta_key, $this->get_user_group_id( get_current_user_id() ) );
   		}
	}

	/**
	 * Remove user from group admin when delete
	 *
	 * @since     1.0.0
	 */
	public function delete_c_user_group( $user_id ) {
		if ( $this->get_user_group_id( $user_id ) ) {
			delete_post_meta(  
				$this->get_user_group_id( $user_id ), 
				$this->group_admins_meta_key,
				$user_id
			);
		}
	}

	/**
	 * Adds an extra column to users table in users.php
	 *
	 * @since     1.0.0
	 */

	public function add_c_user_group_column( $columns ) {
	    $columns['c_user_group'] = 'User Group';
	    return $columns;
	}

	/**
	 * Gets user group meta and show the value in the user group column
	 *
	 * @since     1.0.0
	 */

	public function show_c_user_group_column_content( $value, $column_name, $user_id ) {
	    $user_group_meta_key = $this->user_group_meta_key;
		$current_user_group = get_user_meta( $user_id, $user_group_meta_key, true);
		
		if ( isset( $current_user_group ) ) {
			$group = get_post( $current_user_group );
		}

		if ( 'c_user_group' == $column_name && isset( $group ) )
			return $group->post_title;
	    return $value;
	}
	
	/**
	 * set groups admin only can create subscribers 
	 *
	 * @since     1.0.0
	 */
	public function c_user_filter_roles( $roles ) {
	    if ( current_user_can( $this->group_admin_role_slug ) ) 
	    {
	        $tmp = array_keys( $roles );
	        foreach( $tmp as $r )
	        {
	            if( 'subscriber' == $r ) continue;
	            unset( $roles[$r] );
	        }
	    }
	    return $roles;
	}

	/**
	 * Prevent group admin edit or delete users that not belong to their group or subscriber role
	 *
	 * @since     1.0.0
	 */
	public function c_user_map_meta_cap( $caps, $cap, $user_id, $args ) {
		$current_user_group = $this->get_user_group_id( get_current_user_id() );
		switch( $cap ) {
			case 'edit_user':
			case 'remove_user':
			case 'promote_user':
				if ( isset( $args[0] ) && $args[0] == $user_id ) // If i'm editing myself
					break; // let him pass
				else if ( ! isset( $args[0]) )
					$caps[] = 'do_not_allow';
				$user = new WP_User( absint( $args[0] ) );
				$user_group = $this->get_user_group_id( $user->ID );

				if ( ! current_user_can( 'administrator' ) ) {
					if ( $user->has_cap( $this->group_admin_role_slug ) || $current_user_group != $user_group ) {
						$caps[] = 'do_not_allow';
					}
				}
				break;
			case 'delete_user':
			case 'delete_users':
				if ( ! isset($args[0]) )
					break;
				$user = new WP_User( absint($args[0]) );
				$user_group = $this->get_user_group_id( $user->ID );
				
				if ( ! current_user_can( 'administrator' ) ) {
					if ( $user->has_cap( $this->group_admin_role_slug ) || $current_user_group != $user_group ) {
						$caps[] = 'do_not_allow';
					}
				}
				break;
			default:
			break;
		}
		return $caps;
	}

	/**
	 * Only List users that belong to my group
	 *
	 * @since     1.0.0
	 */
	public function c_user_group_pre_user_query( $user_search ) {
	    $admin_group = $this->get_user_group_id( get_current_user_id() );
	    if ( current_user_can( $this->group_admin_role_slug ) ) {
	        global $wpdb;
	        $user_search->query_where = 
	            str_replace('WHERE 1=1', 
	            "WHERE 1=1 AND {$wpdb->users}.ID IN (
	                SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta 
	                WHERE {$wpdb->usermeta}.meta_key = '{$this->user_group_meta_key}'
	                AND {$wpdb->usermeta}.meta_value = '{$admin_group}')", 
	            $user_search->query_where
	        );
	    }
	}

	/**
	 * Hide roles tab for non-admin
	 */
	public function hide_user_count(){
	    if ( current_user_can( $this->group_admin_role_slug ) ) {
			printf('<style>ul.subsubsub { display: none; }</style>');
	    }
	}

	/**
	 * Admin menu to csv importer
	 */
	public function csv_group_importer_subpage_menu() {
		add_submenu_page(
			'users.php',
			__( 'User groups CSV importer' , 'wp_couser' ),
			__( 'User groups CSV importer' , 'wp_couser' ),
			'administrator',
			'c_user_group_importer',
			array( $this, 'csv_group_importer_subpage_html' )
		);
	}

	/**
	 * CSV importer
	 * also renders the importer page and the results
	 */
	public function csv_group_importer_subpage_html() {

		// check user capabilities
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		if ( isset( $_FILES[ 'csvfile' ] ) || isset( $_POST[ 'csv_group_importer_nonce' ] ) ) {
			if ( wp_verify_nonce( $_POST[ 'csv_group_importer_nonce' ], 'csv_group_importer') ) {

			    $file_valid = true;
			    $error_message = '';
			    $user_log_messages = '';
			    $update_groups = true;

				if ( ! ( $_FILES[ 'csvfile' ][ 'size' ] > 0 && ! $_FILES[ 'csvfile' ][ 'size' ] < wp_max_upload_size() ) ) {
				    $file_valid = false;
				    $error_message = 'The size of the file is too big, max size allowed: ' . size_format( wp_max_upload_size(), 2 );
                }

                if ( ! ( $_FILES[ 'csvfile' ][ 'type' ] === 'text/csv' ) ) {
	                $file_valid = false;
	                $error_message = 'The file you are trying to upload is not a valid CSV';
                }

                if ( $file_valid ) {
					
					$filename = $_FILES[ 'csvfile' ][ 'tmp_name' ];
					$file = fopen( $filename, 'r' );
					$row_number = 0;

					$users_created = 0;
					$users_updated = 0;
					$users_with_errors = 0;

					$columns_headers = array();

					$current_user = wp_get_current_user();
					$current_user_name = ( $current_user instanceof WP_User ? $current_user->user_login : 'importer' );

					while ( ( $data = fgetcsv( $file, 10000, "," ) ) !== false ) {
						if ( $row_number == 0 ) {
                            $columns_headers = array_flip( $data );

							if ( ! array_key_exists('username', $columns_headers) || ! array_key_exists('email', $columns_headers ) ) {
								$error_message = 'This CSV not contain neither email or username valid columns';
							    continue;
                            }

                            if ( ! array_key_exists('usergroup', $columns_headers) || ! array_key_exists('isgroupadmin', $columns_headers ) ) {
	                            $update_groups = false;
                            }

                        } else if ( ! empty( $data[0] ) ) {

							$username = ( isset( $data[ $columns_headers[ 'username' ] ] ) ? $data[ $columns_headers[ 'username' ] ] : null );
							$email = ( isset( $data[ $columns_headers[ 'email' ] ] ) ? $data[ $columns_headers[ 'email' ] ] : null );

							if ( $update_groups ) {
                                $usergroup = ( isset( $data[ $columns_headers[ 'usergroup' ] ] ) ? $data[ $columns_headers[ 'usergroup' ] ] : null );
                                $isgroupadmin = ( isset( $data[ $columns_headers[ 'isgroupadmin' ] ] ) && strtolower( $data[ $columns_headers[ 'isgroupadmin' ] ] ) === 'yes' );
							}

							if ( $username && $email ) {
								$user_id = username_exists( $username );
								if ( ! $user_id && email_exists( $email ) == false ) {
									$random_password = wp_generate_password( 
										$length = 12, 
										$include_standard_special_chars = false 
									);
									$user_id = wp_create_user( $username, $random_password, $email );

									$message  = __( 'Hi there,' ) . "\r\n\r\n";
									$message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option( 'blogname' ) ) . "\r\n\r\n";
									$message .= wp_login_url() . " \r\n\r\n";
									$message .= sprintf( __( 'Username: %s' ), $username ) . "\r\n\r\n";
									$message .= sprintf( __( 'Password: %s' ), $random_password ) . "\r\n\r\n";
									$message .= sprintf( __( 'If you have any problems, please contact us at %s.') , get_option( 'admin_email' ) ) . "\r\n\r\n";
									$message .= __( 'Thanks!' );

									wp_mail( $email, sprintf( __( '[%s] Your username and password' ), get_option( 'blogname' ) ), $message );

									$users_created++;
								} else if ( $update_groups ) {
									$users_updated++;
								}
								
								// Group stuff goes here
								if ( $user_id && ! is_wp_error( $user_id ) && $update_groups ) {
									
									// WP_Query arguments
									$args = array(
										'name'                   => sanitize_title_for_query( $usergroup ),
										'post_type'              => $this->user_group_meta_key,
										'posts_per_page'         => '1',
										'orderby'                => 'id',
									);

									// If group exist
									$query = new WP_Query( $args );
									if ( isset( $query->posts[0] ) ) {
										$group_id = $query->posts[0]->ID;
										update_user_meta( $user_id, $this->user_group_meta_key, $group_id );

									} else {
										$new_args = array(
										    'post_title'    => $usergroup,
										    'post_content'  => $usergroup . ' group created by ' . $current_user_name,
										    'post_status'   => 'publish',
										    'post_author'   => 1,
										    'post_type'     => $this->user_group_meta_key,
										);
										// Insert the group into the database.
										$group_id = wp_insert_post( $new_args );

										if ( ! is_wp_error( $group_id ) ) {
											update_user_meta( $user_id, $this->user_group_meta_key, $group_id );
										}
									}

									if ( ! is_wp_error( $group_id ) && $isgroupadmin ) { // IF is group admin
										$this->set_user_role( $user_id, $this->group_admin_role_slug );
										add_post_meta( $group_id, $this->group_admins_meta_key, $user_id, false );
                                    } else if ( ! is_wp_error( $group_id ) && ! $isgroupadmin ) {
										$this->remove_user_from_role( $user_id, $this->group_admin_role_slug );
										$this->set_user_role( $user_id, 'subscriber' );
										delete_post_meta( $group_id, $this->group_admins_meta_key, $user_id );
                                    }

								} else if ( is_wp_error( $user_id ) ) {
									$users_with_errors++;
								}
							} else {
								$users_with_errors++;
                            }
                        }
						$row_number++;
					}
	                $user_log_messages = 'Users created: ' . $users_created . ' | Users updated: ' . $users_updated . ' | Users with error: ' . $users_with_errors . '<br>';
				}

				// Notices

				if ( ! empty ( $user_log_messages ) ) {
					?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php echo $user_log_messages; ?></p>
                    </div>
					<?php
				}
				else if ( ! empty( $error_message ) ) {
					?>
                    <div class="notice notice-error is-dismissible">
                        <p><?php echo $error_message; ?></p>
                    </div>
					<?php
				}
			}
		}

		?>
		<div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form enctype="multipart/form-data" action="users.php?page=c_user_group_importer" method="post">
            	<p>Please upload a csv file containing user data. </p>
            	<p>Columns must be in the next order: username, email, usergroup, isgroupadmin.</p>
            	<p><img src="<?php echo plugins_url( 'assets/demo_csv.png', __FILE__ ) ?>" alt="CSV demo" style="max-width: 100%"></p>
                <p>If the usergroup or group doesn't exist it will be created</p>
                <p><a target="_blank" href="<?php echo plugins_url( 'assets/test_csv.csv', __FILE__ ) ?>">Download CSV demo file</a></p>
                
                <table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="csvfile">CSV file</label></th>
							<td>
								<?php wp_nonce_field( 'csv_group_importer', 'csv_group_importer_nonce' ); ?>
								<input name="csvfile" type="file" id="csvfile" class="regular-text" required>
                                <p class="description">Max file size <?php echo size_format( wp_max_upload_size(), 2 ); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Submit"></p>
            </form>
		</div>
		<?php
	}

}
