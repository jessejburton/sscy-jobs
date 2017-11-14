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
defined( 'ABSPATH' ) or die( 'Hey! Get outta here!' );

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}
  
class SSCYJobs 
{
  public $plugin; 

  function __construct() {
    // Store the name of the plugin.
    $this->create_post_type();
    $this->plugin = plugin_basename( __FILE__ );
  }

// Add the custom post type
  function create_post_type(){
    add_action( 'init', array( $this, 'custom_post_type' ) );    
  }  

function sscy_save_jobs( $post_id ) {
  // Checks save status
  $is_autosave  = wp_is_post_autosave( $post_id );
  $is_revision  = wp_is_post_revision( $post_id );
  $is_valid_nonce = ( isset( $_POST['sscy_jobs_nonce'] ) && wp_verify_nonce( $_POST['sscy_jobs_nonce'], basename(__FILE__) ) ) ? 'true' : 'false';  
  $sscy_stored_meta = get_post_meta( $post_id );  // Get the data to know if a file already exists for this post 
    
  if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
    die();
    return; 
  }   

    if ( isset( $_POST['custom_editor_1'] ) )
        update_post_meta( $post_id, 'responsibilities', $_POST['custom_editor_1'] );

    if ( isset( $_POST['custom_editor_2'] )  )
        update_post_meta( $post_id, 'conditions', $_POST['custom_editor_2'] );    

    // Checks for input and saves
    if( isset( $_POST[ 'active' ] ) ) {
        update_post_meta( $post_id, 'active', 'active' );
    } else {
        update_post_meta( $post_id, 'active', 'inactive' );
    }
}

// Register the styles and scripts
  function register(){
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );    
    add_action( 'add_meta_boxes', array( $this, 'custom_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'sscy_save_jobs' ) );  
    add_filter( 'single_template', array( $this, 'add_template' ) );
  }

  function activate(){
    $this->create_post_type();
    flush_rewrite_rules( false );
  }

  function deactivate(){
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
        'has_archive'     => false,
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

  function custom_meta_boxes(){

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

      require_once( plugin_dir_path(__FILE__) . 'sscy-jobs-fields.php' );    
  }  

  // Add support for page display 
  function add_template( $single_template )
  {
    global $wp_query, $post;

    /* Checks for single template by post type */
    if ( $post->post_type == 'jobs' ) {
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'single-job.php' ) ) {
            return plugin_dir_path( __FILE__ ) . 'single-job.php';
        }
    }

    return $single_template;
  }  

  // ENQUEUE STYLES AND SCRIPTS
  // Eneueue the styles and scripts for the plugins front end (on the site)
  function enqueue(){
    wp_enqueue_style( 'jobsstyle', plugins_url( '/assets/css/sscy-jobs.css', __FILE__ ) );
    wp_enqueue_script( 'jobsscript', plugins_url( '/assets/js/sscy-jobs.js', __FILE__ ) );
  }
  // Enqueue the styles and scripts for the admin of the plugin (in the wp administrator)
  function admin_enqueue(){
    wp_enqueue_style( 'jobsadminstyle', plugins_url( '/assets/css/sscy-jobs-admin.css', __FILE__ ) );
    wp_enqueue_script( 'jobsadminscript', plugins_url( '/assets/js/sscy-jobs-admin.js', __FILE__ ) );
  }  
}

if ( class_exists( 'SSCYJobs' )){
  $sscyJobs = new SSCYJobs();
  $sscyJobs->register();
}

// Activation
register_activation_hook( __FILE__, array( $sscyJobs, 'activate' ) ); 

// Deactivation
register_deactivation_hook( __FILE__, array( $sscyJobs, 'deactivate' ) );



/*


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