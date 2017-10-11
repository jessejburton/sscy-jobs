<?php

// Add the custom post type for document
function sscy_register_position_type(){
	
	$singular = "Job Posting";
	$plural = "Job Postings";
	
	$labels = array(
		'name'				=> $plural,
		'singular_name'		=> $singular,
		'add_name'			=> 'Add New',
		'add_new_item'		=> 'Add New ' . $singular,
		'edit'				=> 'Edit',
		'edit_item'			=> 'Edit ' . $singular,
		'new_item'			=> 'New ' . $singular,
		'view'				=> 'View ' . $singular,
		'view_item'			=> 'View ' . $singular,
		'search_term'		=> 'Search ' . $plural,
		'parent'			=> 'Parent ' . $singular,
		'not_found'			=> 'No ' . $plural . ' found',
		'not_found_in_trash'=> 'No ' . $plural . ' found in trash'
	);
	
	$args = array( 
		'labels'				=> $labels,
		'public'				=> true,
		'publicly_queryable'	=> true,
		'exclude_from_search' 	=> false,
		'show_in_nav_menus'		=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'show_in_admin_bar'		=> true,
		'menu_position'			=> 6,
		'menu_icon'				=> 'dashicons-star-filled',
		'can_export'			=> true,
		'delete_with_user'		=> false,
		'hierarchical'			=> false,
		'has_archive'			=> true,
		'query_var'				=> true,
		'capability_type'		=> 'page',
		'map_meta_cap'			=> true,
		'rewrite'				=> array(
			'slug'			=> 'position',
			'with_front'	=> true,
			'pages'			=> true,
			'feeds'			=> false
		),
		'supports'				=> array(
			'title',
			'editor'
		)
	);
	
	register_post_type( 'position', $args );
		
}
add_action( 'init', 'sscy_register_position_type' );