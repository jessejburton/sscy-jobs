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
  ==========================================================
  Exit if file is called directly
  ==========================================================
*/  
defined( 'ABSPATH' ) or die( 'Hey! Get outta here!' );

/* 
  ==========================================================
  Include the composer autoload. 
  Composer is a package/dependency manager for php (https://getcomposer.org/).
  ==========================================================
*/
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/* 
  ==========================================================
  Namespaces included through the composer autoload
  ==========================================================
*/  
use Inc\Activate;
use Inc\Deactivate;
  
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
    add_action( 'init', array( $this, 'job_custom_post_type' ) );    
  }  

  function sscy_save_jobs( $post_id ) {
    // Checks save status
    $is_autosave  = wp_is_post_autosave( $post_id );
    $is_revision  = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST['sscy_jobs_nonce'] ) && wp_verify_nonce( $_POST['sscy_jobs_nonce'], basename(__FILE__) ) ) ? 'true' : 'false';  
      
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      die();
      return; 
    }   

      update_post_meta( $post_id, 'responsibilities', $_POST['custom_editor_1'] );
      update_post_meta( $post_id, 'conditions', $_POST['custom_editor_2'] );    
      update_post_meta( $post_id, 'active', isset( $_POST['active'] ) );

  }

/* 
  ==========================================================
  Register the styles and scripts
  ==========================================================
*/ 
  function register(){
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );    
    add_action( 'add_meta_boxes', array( $this, 'custom_meta_boxes' ) );
    add_action( 'save_post', array( $this, 'sscy_save_jobs' ) );  
    add_filter( 'single_template',  array( $this, 'job_single_template' ) );
    add_filter( 'archive_template',  array( $this, 'job_archive_template' ) );
    //add_filter( "manage_jobs_posts_columns", "change_columns" );
    //add_action( "manage_posts_custom_column", "custom_columns", 10, 2 );
    //add_filter( "manage_edit-jobs_sortable_columns", "sortable_columns" );
    //add_filter( 'single_template', 'add_template', 10, 1 );
  }

  function job_custom_post_type(){
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
      'labels'            => $labels,
      'public'            => true,
      'has_archive'       => true,
      'publicly_queyable' => true,
      'query_var'         => true,
      'rewrite'           => true,
      'capability_type'   => 'post',
      'hierarchical'      => true,
      'supports'           => array (
        'title',
        'editor'
      ),
      'taxonimies'        => array('category', 'post_tag'),
      'menu_position'     => 5,
      'exlude_from_search'=> false
    );
    register_post_type( 'job', $args );
  }

  function custom_meta_boxes(){

      // Define the custom attachment for pages
      add_meta_box(
          'sscy_jobs_responsibilities_and_qualifications',   // Unique ID
          'Responsibilities & Qualifications',               // Box Title
          'sscy_jobs_responsibilities_and_qualifications',   // Content Callback
          'sscy_jobs'                                        // Post Type
      );

      // Define the custom attachment for pages
      add_meta_box(
          'sscy_jobs_working_conditions',
          'Working Conditions',
          'sscy_jobs_working_conditions',
          'sscy_jobs'
      );    
  
      // Define the custom attachment for pages
      add_meta_box(
          'sscy_jobs_options',
          'Options',
          'sscy_jobs_options',
          'sscy_jobs'
      );

      require_once( plugin_dir_path(__FILE__) . 'sscy-jobs-fields.php' );    
  }  

/* 
  ==========================================================
  Eneueue the styles and scripts for the plugins front end (on the site)
  ==========================================================
*/ 

  function enqueue(){
    wp_enqueue_style( 'jobsstyle', plugins_url( '/assets/css/sscy-jobs.css', __FILE__ ) );
    wp_enqueue_script( 'jobsscript', plugins_url( '/assets/js/sscy-jobs.js', __FILE__ ) );
  }
  // Enqueue the styles and scripts for the admin of the plugin (in the wp administrator)
  function admin_enqueue(){
    wp_enqueue_style( 'jobsadminstyle', plugins_url( '/assets/css/sscy-jobs-admin.css', __FILE__ ) );
    wp_enqueue_script( 'jobsadminscript', plugins_url( '/assets/js/sscy-jobs-admin.js', __FILE__ ) );
  }  

/* 
  ==========================================================
  Change the columns for the edit CPT screen
  ==========================================================

  function change_columns( $cols ) {
    $cols = array(
      'cb'       => '<input type="checkbox" />',
      'active'      => __( '',      'trans' ),    
      'title'     => __( 'Job Posting',      'trans' ),
    );
    return $cols;
  }

/* 
  ==========================================================
  Return the custom columns for the CPT screen
  ==========================================================
 
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

/* 
  ==========================================================
  Make these columns sortable
  ==========================================================
 
  function sortable_columns() {
    return array(
      'active'      => 'active',
    );
  }
*/  

/* 
  ==========================================================
  Add the single and archive page templates
  ==========================================================
*/

  function job_single_template( $single ) {

      global $wp_query, $post;

      /* Checks for single template by post type */
      if ( $post->post_type == 'job' ) {
          if ( file_exists( plugin_dir_path( __FILE__ ) . '/single-job.php' ) ) {
              return plugin_dir_path( __FILE__ ) . '/single-job.php';
          }
      }

      return $single;

  }

  function job_archive_template( $single ) {

        global $wp_query, $post;

        /* Checks for single template by post type */
        if ( $post->post_type == 'job' ) {
            if ( file_exists( plugin_dir_path( __FILE__ ) . '/archive-job.php' ) ) {
                return plugin_dir_path( __FILE__ ) . '/archive-job.php';
            }
        }

        return $single;

    }  

} // END SSCYJobs CLASS


/* 
  ==========================================================
  Instantiate the plugin class and run the register function
  ==========================================================
*/ 
if ( class_exists( 'SSCYJobs' )){
  $sscyJobs = new SSCYJobs();
  $sscyJobs->register();
  add_shortcode( 'sscy_current_jobs', array( $sscyJobs, 'sscy_current_jobs_shortcode' ) );
}


/* 
  ==========================================================
  Activating and Deactivating the plugin
  ==========================================================
*/ 
// Activation
register_activation_hook( __FILE__, array( 'Activate', 'activate' ) ); 

// Deactivation
register_deactivation_hook( __FILE__, array( 'Deactivate', 'deactivate' ) );


