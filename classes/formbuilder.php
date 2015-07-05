<?php

class formbuilder {
	public function __construct() {
		add_action('init' , array($this , 'create_form_post_type'));
	}



	public function create_form_post_type() {
	///////////////////////////////////////////////////////
	///Forms
	///////////////////////////////////////////////////////	
		$labels = array(
			'name'               => _x( 'Forms', 'post type general name', 'wobble' ),
			'singular_name'      => _x( 'Form', 'post type singular name', 'wobble' ),
			'menu_name'          => _x( 'Forms', 'admin menu', 'wobble' ),
			'name_admin_bar'     => _x( 'Form', 'add new on admin bar', 'wobble' ),
			'add_new'            => _x( 'Add New', 'Form', 'wobble' ),
			'add_new_item'       => __( 'Add New Form', 'wobble' ),
			'new_item'           => __( 'New Form', 'wobble' ),
			'edit_item'          => __( 'Edit Form', 'wobble' ),
			'view_item'          => __( 'View Form', 'wobble' ),
			'all_items'          => __( 'All Forms', 'wobble' ),
			'search_items'       => __( 'Search Forms', 'wobble' ),
			'parent_item_colon'  => __( 'Parent Forms:', 'wobble' ),
			'not_found'          => __( 'No Forms found.', 'wobble' ),
			'not_found_in_trash' => __( 'No Forms found in Trash.', 'wobble' )
			);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'form' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'menu_icon'			 => 'dashicons-welcome-widgets-menus'
			);

		register_post_type( 'form', $args );	
	}
}
?>