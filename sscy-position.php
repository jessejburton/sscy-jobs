<?php
/**
* Plugin Name: Salt Spring Centre of Yoga - Job Postings Plugin
* Plugin URI: http://www.burtonmediainc.com/SSCY/plugins/positions.html
* Description: Manage job postings for the Salt Spring Centre of Yoga
* Author: Jesse James Burton
* Author URI: http://www.burtonmediainc.com
* Version: 0.0
* License: GPLv2
*/

// Exit if called directly
if( !defined('ABSPATH') ){
	exit;
};

require_once( plugin_dir_path(__FILE__) . 'sscy-position-register.php' );
require_once( plugin_dir_path(__FILE__) . 'sscy-position-fields.php' );

function sscy_admin_position_enqueue_scripts(){
	global $pagenow, $typenow;

	if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') || $pagenow == 'edit.php' && $typenow == 'position' ){
		wp_enqueue_style( 'sscy_admin_position_styles', plugins_url( 'css/sscy-position-admin.css', __FILE__ ) );
	};
}
add_action( 'admin_enqueue_scripts', 'sscy_admin_position_enqueue_scripts' );

// Include the styles for displaying the positions
function sscy_position_enqueue_scripts(){
    wp_enqueue_style( 'bm_position_styles', plugins_url( 'css/bm-position.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'sscy_position_enqueue_scripts');

// Add the custom meta box for the files.
function add_position_custom_meta_boxes() {
 
    // Define the custom attachment for pages
    add_meta_box(
        'sscy_position_options',
        'Options',
        'sscy_position_options',
        'position'
    );

 
} // end add_custom_meta_boxes
add_action('add_meta_boxes', 'add_position_custom_meta_boxes');

// Add support for page display 
function add_posttype_template( $single_template )
{
    $object = get_queried_object();
    $single_postType_template = plugin_dir_path( __FILE__ ) . "single-position.php";

    if( file_exists( $single_postType_template ) )
    {
        return $single_postType_template;
    } else {
        return $single_template;
    }
}
add_filter( 'single_template', 'add_posttype_template', 10, 1 );

// Change the columns for the edit CPT screen
function change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
    'active'      => __( '',      'trans' ),    
    'title'     => __( 'Job Posting',      'trans' ),
  );
  return $cols;
}
add_filter( "manage_position_posts_columns", "change_columns" );

function custom_columns( $column, $post_id ) {
  switch ( $column ) {
    case "title":
      echo get_post_meta( $post_id, 'title', true);
      break;
    case "active":
      echo get_post_meta( $post_id, 'active', true);
      break;
  }
}
add_action( "manage_posts_custom_column", "custom_columns", 10, 2 );

// Make these columns sortable
function sortable_columns() {
  return array(
    'active'      => 'active',
  );
}
add_filter( "manage_edit-position_sortable_columns", "sortable_columns" );