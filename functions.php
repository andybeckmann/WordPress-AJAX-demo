<?php

/**
 * Enqueue styles and scripts
 */
function ajaxwp_scripts() {
	wp_enqueue_style( 'ajaxwp-styles', get_stylesheet_uri() );
	wp_enqueue_script( 'ajax', get_template_directory_uri() . '/events-ajax.js', array( 'jquery' ), NULL, true);
	wp_localize_script( 'ajax', 'wpAjax', 
		array( 
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'ajax-nonce' )
		) 
	);
}
add_action( 'wp_enqueue_scripts', 'ajaxwp_scripts' );

require_once( 'events-ajax.php' );

/**
 * Register custom post type 'events'
 */
function ajaxwp_events_custom_post_type() {
	register_post_type( 'events',
		array(
			'labels'	  		=> array(
				'name'		  	=> __( 'Events', 'textdomain' ),
				'singular_name' => __( 'Event', 'textdomain' ),
				'search_items'	=> __( 'Search Events' ),
				'all_items'		=> __( 'Events' ),
				'edit_item'		=> __( 'Edit Event' ),
				'update_item'	=> __( 'Update Event' ),
				'add_new_item'	=> __( 'Add New Event' ),
				'new_item_name'	=> __( 'New Event Title' ),
				'menu_name'		=> __( 'Events' ),
			),
			'public'	  		=> true,
			'has_archive' 		=> true,
			'supports' 	  		=> array ( 'title', 'editor', 'custom-fields' ),
			'menu_position'		=> __(4),
		)
	);
}
add_action( 'init', 'ajaxwp_events_custom_post_type' );

/**
 * Register taxonomy 'event-location'
 */
function ajaxwp_register_taxonomy_event_location() {
	$labels = [
		'name'			 => _x( 'Location', 'taxonomy general name' ),
		'singular_name'	 => _x( 'Location', 'taxonomy singular name' ),
		'search_items'	 => __( 'Search Locations' ),
		'all_items'		 => __( 'Locations' ),
		'edit_item'		 => __( 'Edit Location' ),
		'update_item'	 => __( 'Update Location' ),
		'add_new_item'	 => __( 'Add New Location' ),
		'new_item_name'	 => __( 'New Location' ),
		'menu_name'		 => __( 'Event Locations' ),
	];
	$args = [
		'hierarchical'	  	=> true,
		'labels'			=> $labels,
		'show_ui'		   	=> true,
		'show_admin_column' => true,
		'query_var'		 	=> true,
		'rewrite'		   	=> ['slug' => 'event-location'],
	];

	register_taxonomy( 'event-location', ['events'], $args);
}
add_action( 'init', 'ajaxwp_register_taxonomy_event_location' );

/**
 * Register taxonomy 'event-time'
 */
function ajaxwp_register_taxonomy_event_time() {
	$labels = [
		'name'			 => _x( 'Time', 'taxonomy general name' ),
		'singular_name'	 => _x( 'Time', 'taxonomy singular name' ),
		'search_items'	 => __( 'Search Times' ),
		'all_items'		 => __( 'Times' ),
		'edit_item'		 => __( 'Edit Time' ),
		'update_item'	 => __( 'Update Time' ),
		'add_new_item'	 => __( 'Add New Time' ),
		'new_item_name'	 => __( 'New Time' ),
		'menu_name'		 => __( 'Event Times' ),
	];
	$args = [
		'hierarchical'	  	=> true,
		'labels'			=> $labels,
		'show_ui'		   	=> true,
		'show_admin_column' => true,
		'query_var'		 	=> true,
		'rewrite'		   	=> ['slug' => 'event-time'],
	];

	register_taxonomy( 'event-time', ['events'], $args);
}
add_action( 'init', 'ajaxwp_register_taxonomy_event_time' );

/**
 * Register taxonomy 'event-length'
 */
function ajaxwp_register_taxonomy_event_length() {
	$labels = [
		'name'			 => _x( 'Length', 'taxonomy general name' ),
		'singular_name'	 => _x( 'Length', 'taxonomy singular name' ),
		'search_items'	 => __( 'Search Lengths' ),
		'all_items'		 => __( 'Length' ),
		'edit_item'		 => __( 'Edit Length' ),
		'update_item'	 => __( 'Update Length' ),
		'add_new_item'	 => __( 'Add New Length' ),
		'new_item_name'	 => __( 'New Length' ),
		'menu_name'		 => __( 'Event Lengths' ),
	];
	$args = [
		'hierarchical'	  	=> true,
		'labels'			=> $labels,
		'show_ui'		   	=> true,
		'show_admin_column' => true,
		'query_var'		 	=> true,
		'rewrite'		   	=> ['slug' => 'event-length'],
	];

	register_taxonomy( 'event-length', ['events'], $args);
}
add_action( 'init', 'ajaxwp_register_taxonomy_event_length' );