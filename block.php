<?php
/*
Plugin Name: Admin Maison du Boeuf
Description: Maison du Boeuf Administration
Author: Adam Parent (InitialCrow)
Author URI: https://www.adam-parent.com
*/


/*---------------------*/

/*-----CALL ACTIONS----*/

/*---------------------*/
register_deactivation_hook( __FILE__, 'remove_plugin' );
register_activation_hook( __FILE__, 'initPlugin' );
add_action( 'admin_menu', 'my_plugin_menu' );
add_action( 'admin_enqueue_scripts', 'load_script' );




/*---------------------*/

/*------FUNCTIONS------*/

/*---------------------*/


/**
 * [Function to delete role and change capabilites]
 * @return none
*/
function initRole(){
  remove_role("editor");
  remove_role("author");
  remove_role("contributor");
  remove_role("subscriber");
  $perms = [
    "promote_users"=>false,
    "edit_theme_options"=>true,
    "list_users"=>true,
    "manage_options"=>true,
    "read"=>true,
    "remove_users"=>true,
    "switch_temes"=>true,
    "upload_files"=>true,
    "edit_posts"=>true,
    "edit_others_posts"=>true,
    "edit_published_posts"=>true,
    "publish_posts"=>true,
    "delete_posts"=>true,
    "delete_published_posts"=>true,
    "activate_plugins"=>true
  ];
  remove_role('manager');
  add_role('manager', 'Manager',$perms);
}

/**
 * [Function to load js script]
 * @param  [worpress hook]
 * @return [none]
 */
function load_script($hook){
  if( $hook != 'modifier_page_block-times')
    return;

  wp_enqueue_style("stylecss",  plugins_url( 'wordpress-plugin-mdb/css/style.css' , dirname(__FILE__)));
  wp_enqueue_script("mainjs",  plugins_url( 'wordpress-plugin-mdb/js/main.js' , dirname(__FILE__)));
  wp_enqueue_script("execjs",  plugins_url( 'wordpress-plugin-mdb/js/exec.js' , dirname(__FILE__)));
  wp_localize_script( 'mainjs', 'postHandling', array(
    'timesUrl' => plugins_url( 'wordpress-plugin-mdb/block_times_query.php' , dirname(__FILE__)),

  ));
}
/**
 * [Function call function on activation plugin]
 * @return [none]
 */
function initPlugin(){
  initRole();
  createTable();
}

/**
 * [Function call to create plugin table on activation]
 * @return [none]
 */
function createTable(){

  global $wpdb;
  $table_name1 = $wpdb->prefix . "hours_mdb";
  $table_name2 = $wpdb->prefix . "post_like";
  $charset_collate = $wpdb->get_charset_collate();

  $sql[] = "CREATE TABLE $table_name1 (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    openH int NOT NULL,
    closeH int NOT NULL,
    openM int NOT NULL,
    closeM int NOT NULL,
    day varchar(25),
    PRIMARY KEY (id)
  ) $charset_collate;";

  $sql[]="CREATE TABLE $table_name2 (
    id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    post_id bigint(20)  unsigned NOT NULL ,
    ulike int,
    udislike int,
    index(post_id),
    CONSTRAINT fk_posts_k
    FOREIGN KEY (post_id)
        REFERENCES m_posts(ID) ON DELETE CASCADE
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

}
/**
 * [Function to create menu for the plugin]
 * @return [none]
 */
function my_plugin_menu() {
  $user = wp_get_current_user();
  if($user->roles[0] == "manager"){
    remove_menu_page( 'tools.php' );
    remove_menu_page( 'edit-comments.php' );
  }
  add_menu_page( "block", __("Modifier"), "manage_options", "edit.php?post_type=wp_block", "", '', 2 );
  add_submenu_page("edit.php?post_type=wp_block","block-times",__("Horraires"),"manage_options","block-times","times");

}
/**
 * [function to show the time manager]
 * @return [none]
 */
function times() {

  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  require_once(__DIR__.'/block_times.php' );
}


/**
 * [function to remove db on plugin desactivation]
 * @return [none]
 */
function remove_plugin() {

 global $wpdb;
 $table_name = $wpdb->prefix . 'hours_mdb';
 $sql = "DROP TABLE IF EXISTS $table_name";
 $wpdb->query($sql);
 $table_name = $wpdb->prefix . 'post_like';
 $sql = "DROP TABLE IF EXISTS $table_name";
 $wpdb->query($sql);
 delete_option("my_plugin_db_version");
}