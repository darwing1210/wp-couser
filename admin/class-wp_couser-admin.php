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
		
		$role = 'administrator';
		$key = $this->group_admins_meta_key;
 
 		wp_nonce_field( 'add_user_group_admin_metabox', 'user_group_admin_metabox_nonce' );
		$dropdown_users_args = array(
		    'role' => $role,
		);
		$users = get_users( $dropdown_users_args );
		
		$output = sprintf( '<select multiple name="%1$s[]" id="%1$s" class="chosen">', $key );
		 
		$current_group_admins = get_post_meta( $post->ID, $key );

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
		$key = $this->group_admins_meta_key;
		// Verifying nonce
		if ( ! isset( $_POST['user_group_admin_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['user_group_admin_metabox_nonce'], 'add_user_group_admin_metabox' ) ) {
	        return;
	    }

		if( isset( $_POST[$key] ) ) {
			delete_post_meta( $post_id, $key ); // Cleaning before save
	        foreach ($_POST[$key] as $selected_user) {
		        add_post_meta( $post_id, $key, $selected_user );
	        }
	    } else {
	        delete_post_meta( $post_id, $key );
	    }
	}

	/**
	 * Renders user group field to users create and update
	 *
	 * @since     1.0.0
	 */

	public function add_user_group_field( $user ) { 
	
		$key = $this->user_group_meta_key;

	?>
		<hr>
		<h2>User group</h2>
		<table class="form-table">
	   	<tr>
	   		<th><label for="user-group">User group</label></th>
	   		<td>
	   			<select id="<?php echo $key ?>" name="<?php echo $key ?>">
	   			<?php 

	   			$groups = $this->get_c_user_groups();

	   			if ( isset( $user->ID ) ) {
		   			$current_user_group = get_user_meta( $user->ID, $key );
		   		}

	   			echo '<option value>User group</option>';
			
				foreach ($groups as $group) {
		   			$_selected = '';
					
					if ( isset( $current_user_group ) ) {
						if ( in_array( $group->ID, $current_user_group ) ) {
							$_selected = 'selected';
						}
					}

					printf( '<option value="%1$s" %2$s>%3$s</option>', $group->ID, $_selected, $group->post_title );
				} ?>
	   			</select>
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

	function save_c_user_group( $user_id ) {
		$key = $this->user_group_meta_key;

		if ( isset( $_POST[$key] ) ) {
	   		update_user_meta( $user_id, $key, $_POST[$key] );
   		}
   		else {
   			delete_post_meta( $post_id, $key );
   		}
	}

}
