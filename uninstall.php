<?php

/**
* Trigger this file on plugin uninstal
* 
* @package SSCY
*/

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

// Clear database stored data
//$jobs = get_posts( array( 'post_type' => 'jobs', 'numberposts' => -1 ) );

//foreach( $jobs as $job ) {
//	wp_delete_post( $job->ID, true );
//}

// Access the database via SQL
global $wpdb;

$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'job'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );