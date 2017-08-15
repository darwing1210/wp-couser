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
		    'post_type'=> 'c_user_group',
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
		if( ! current_user_can( $this->group_admin_role_slug ) )
			return $prevent_access;
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
	 * Admin Top Level Menu
	 * render functions
	 */
	public function csv_group_importer_subpage_html() {

		// check user capabilities
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		if ( isset( $_FILES['csvfile'] ) || isset( $_POST['csv_group_importer_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_POST['csv_group_importer_nonce'], 'csv_group_importer') ) {
				echo "<h1>send file</h1>";
				var_dump($_FILES);
			}
		}

		?>
		<div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form enctype="multipart/form-data" action="users.php?page=c_user_group_importer" method="post">
            	<p>Please upload a csv file containing user data. </p>
            	<p>Columns must be in the next order: username, email, usergroup, groupadmin.</p>
            	<p>If the usergroup doesn't exist it will be created</p>
                <table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="csvfile">CSV file</label></th>
							<td>
								<?php wp_nonce_field( 'csv_group_importer', 'csv_group_importer_nonce' ); ?>
								<input name="csvfile" type="file" id="csvfile" class="regular-text" required>
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
