<?php
/**
 * @package SSCY
 */
/*
Plugin Name: Salt Spring Centre of Yoga - Job Postings Plugin
Plugin URI: https://github.com/jessejburton/sscy-jobss
Description: Manage job postings for the Salt Spring Centre of Yoga
Author: Jesse James Burton
Author URI: http://www.burtonmediainc.com
Version: 1.0
License: GPLv2 or later
Text Domain: sscy
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// Exit if called directly
if ( !function_exists( 'add_action' ) ) {
  echo 'Hey! Get outta here! :P';
  die;
}

class SSCYJobs 
{
  function __construct() {
    add_action( 'init', array( $this, 'custom_post_type' ) );
  }

  function activate(){
    // Generate Job Post Type
    $this->custom_post_type();
    // Flush the rewrite rules
    flush_rewrite_rules();
  }

  function deactivate(){
    // Flush the rewrite rules
    flush_rewrite_rules();
  }

  function custom_post_type(){
      $singular = "Job Posting";
      $plural = "Job Postings";
      
      $labels = array(
        'name'        => $plural,
        'singular_name'   => $singular,
        'add_name'      => 'Add New',
        'add_new_item'    => 'Add New ' . $singular,
        'edit'        => 'Edit',
        'edit_item'     => 'Edit ' . $singular,
        'new_item'      => 'New ' . $singular,
        'view'        => 'View ' . $singular,
        'view_item'     => 'View ' . $singular,
        'search_term'   => 'Search ' . $plural,
        'parent'      => 'Parent ' . $singular,
        'not_found'     => 'No ' . $plural . ' found',
        'not_found_in_trash'=> 'No ' . $plural . ' found in trash'
      );
      
      $args = array( 
        'labels'        => $labels,
        'public'        => true,
        'publicly_queryable'  => true,
        'exclude_from_search'   => false,
        'show_in_nav_menus'   => true,
        'show_ui'       => true,
        'show_in_menu'      => true,
        'show_in_admin_bar'   => true,
        'menu_position'     => 6,
        'menu_icon'       => 'dashicons-star-filled',
        'can_export'      => true,
        'delete_with_user'    => false,
        'hierarchical'      => false,
        'has_archive'     => true,
        'query_var'       => true,
        'capability_type'   => 'page',
        'map_meta_cap'      => true,
        'rewrite'       => array(
          'slug'      => 'jobs',
          'with_front'  => true,
          'pages'     => true,
          'feeds'     => false
        ),
        'supports'        => array(
          'title',
          'editor'
        )
      );
      
      register_post_type( 'jobs', $args );
  }
}

if ( class_exists( 'SSCYJobs' )){
  $sscyJobs = new SSCYJobs();
}

// Activation
register_activation_hook( __FILE__, array( $sscyJobs, 'activate' ) ); 

// Deactivation
register_deactivation_hook( __FILE__, array( $sscyJobs, 'deactivate' ) );


/*
require_once( plugin_dir_path(__FILE__) . 'sscy-jobs-fields.php' );

function sscy_admin_jobs_enqueue_scripts(){
	global $pagenow, $typenow;

	if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') || $pagenow == 'edit.php' && $typenow == 'jobs' ){
		wp_enqueue_style( 'sscy_admin_jobs_styles', plugins_url( 'css/sscy-jobs-admin.css', __FILE__ ) );
	};
}
add_action( 'admin_enqueue_scripts', 'sscy_admin_jobs_enqueue_scripts' );

// Include the styles for displaying the jobss
function sscy_jobs_enqueue_scripts(){
    wp_enqueue_style( 'bm_jobs_styles', plugins_url( 'css/bm-jobs.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'sscy_jobs_enqueue_scripts');

// Add the custom meta box for the files.
function add_jobs_custom_meta_boxes() {

    // Define the custom attachment for pages
    add_meta_box(
        'sscy_jobs_responsibilities_and_qualifications',
        'Responsibilities & Qualifications',
        'sscy_jobs_responsibilities_and_qualifications',
        'jobs'
    );

    // Define the custom attachment for pages
    add_meta_box(
        'sscy_jobs_working_conditions',
        'Working Conditions',
        'sscy_jobs_working_conditions',
        'jobs'
    );    
 
    // Define the custom attachment for pages
    add_meta_box(
        'sscy_jobs_options',
        'Options',
        'sscy_jobs_options',
        'jobs'
    );

 
} // end add_custom_meta_boxes
add_action('add_meta_boxes', 'add_jobs_custom_meta_boxes');

// Add support for page display 
function add_posttype_template( $single_template )
{
    $object = get_queried_object();
    $single_postType_template = plugin_dir_path( __FILE__ ) . "single-jobs.php";

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
add_filter( "manage_jobs_posts_columns", "change_columns" );

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
add_filter( "manage_edit-jobs_sortable_columns", "sortable_columns" );

*/