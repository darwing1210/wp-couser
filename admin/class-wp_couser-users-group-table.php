<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WP_Users_Group_Table extends WP_List_Table {

    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'c_user_group',
            'plural'    => 'c_user_groups',
            'ajax'      => false
        ) );
        
    }

    function column_default( $user_object, $column_name ){
        switch($column_name){
            case 'name':
                 return $user_object->first_name . " " .$user_object->last_name;
            case 'email':
                return "<a href='" . esc_url( "mailto:$user_object->user_email" ) . "'>$user_object->user_email</a>";
            default:
                return print_r($user_object,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_username( $item ){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&user=%s">Edit</a>',$_REQUEST['page'],'edit',$item->ID),
            'delete'    => sprintf('<a href="?page=%s&action=%s&user=%s">Delete</a>',$_REQUEST['page'],'delete',$item->ID),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item->display_name,
            /*$2%s*/ $item->ID,
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    function column_cb( $item ){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->ID                //The value of the checkbox should be the record's id
        );
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'username'     => 'Username',
            'name'    => 'Name',
            'email'  => 'Email'
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'username'     => array('title',false),     //true means it's already sorted
            'email'    => array('rating',false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
    
        // $data = $this->example_data;
        
        $args = array(
            'meta_key'     => 'c_user_group',
            'meta_value'   => '2044', // test
        );

        $data = new WP_User_Query( $args );

        // function usort_reorder($a,$b){
        //     $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
        //     $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
        //     $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
        //     return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        // }
        // usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();

        
        $total_items = count($data);
        
        // $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        $this->items = $data->get_results();
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                 
            'per_page'    => $per_page,                     
            'total_pages' => ceil($total_items/$per_page)   
        ) );
    }
}

function tt_add_menu_items(){
    add_submenu_page( 'users.php', 'User Group', 'My Group', 'activate_plugins', 'c_user_group_menu', 'tt_render_list_page' );
}
add_action('admin_menu','tt_add_menu_items');

function tt_render_list_page(){

    //Fetch, prepare, sort, and filter our data...
    $group_table = new WP_Users_Group_Table();
    $group_table->prepare_items();
    
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2>My Group</h2>
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $group_table->display() ?>
        </form>
        
    </div>
    <?php
}
