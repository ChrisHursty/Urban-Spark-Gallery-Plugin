<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Register Custom Post Type
function us_register_gallery_cpt() {
    $labels = array(
        'name'                  => _x( 'US Galleries', 'Post Type General Name', 'us-gallery' ),
        'singular_name'         => _x( 'US Gallery', 'Post Type Singular Name', 'us-gallery' ),
        'menu_name'             => __( 'US Galleries', 'us-gallery' ),
        'name_admin_bar'        => __( 'US Gallery', 'us-gallery' ),
        'archives'              => __( 'Gallery Archives', 'us-gallery' ),
        'attributes'            => __( 'Gallery Attributes', 'us-gallery' ),
        'parent_item_colon'     => __( 'Parent Gallery:', 'us-gallery' ),
        'all_items'             => __( 'All Galleries', 'us-gallery' ),
        'add_new_item'          => __( 'Add New Gallery', 'us-gallery' ),
        'add_new'               => __( 'Add New', 'us-gallery' ),
        'new_item'              => __( 'New Gallery', 'us-gallery' ),
        'edit_item'             => __( 'Edit Gallery', 'us-gallery' ),
        'update_item'           => __( 'Update Gallery', 'us-gallery' ),
        'view_item'             => __( 'View Gallery', 'us-gallery' ),
        'view_items'            => __( 'View Galleries', 'us-gallery' ),
        'search_items'          => __( 'Search Gallery', 'us-gallery' ),
        'not_found'             => __( 'Not found', 'us-gallery' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'us-gallery' ),
        'featured_image'        => __( 'Featured Image', 'us-gallery' ),
        'set_featured_image'    => __( 'Set featured image', 'us-gallery' ),
        'remove_featured_image' => __( 'Remove featured image', 'us-gallery' ),
        'use_featured_image'    => __( 'Use as featured image', 'us-gallery' ),
        'insert_into_item'      => __( 'Insert into gallery', 'us-gallery' ),
        'uploaded_to_this_item' => __( 'Uploaded to this gallery', 'us-gallery' ),
        'items_list'            => __( 'Galleries list', 'us-gallery' ),
        'items_list_navigation' => __( 'Galleries list navigation', 'us-gallery' ),
        'filter_items_list'     => __( 'Filter galleries list', 'us-gallery' ),
    );

    $args = array(
        'label'                 => __( 'US Gallery', 'us-gallery' ),
        'description'           => __( 'A custom post type for galleries', 'us-gallery' ),
        'labels'                => $labels,
        'supports'              => array( 'title' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-format-gallery',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'us-gallery' ),
    );

    register_post_type( 'us_gallery', $args );
}

add_action( 'init', 'us_register_gallery_cpt' );


// Add Shortcode Column to Admin Table
function us_add_shortcode_column( $columns ) {
    $columns['shortcode'] = __( 'Shortcode', 'us-gallery' );
    return $columns;
}

function us_display_shortcode_column( $column, $post_id ) {
    if ( 'shortcode' === $column ) {
        echo '<input type="text" readonly value="[us_gallery id=&quot;' . esc_attr( $post_id ) . '&quot;]" onfocus="this.select()" class="widefat">';
    }
}

add_filter( 'manage_us_gallery_posts_columns', 'us_add_shortcode_column' );
add_action( 'manage_us_gallery_posts_custom_column', 'us_display_shortcode_column', 10, 2 );
