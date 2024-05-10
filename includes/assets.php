<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Enqueue Front-End Scripts and Styles
function us_enqueue_frontend_assets() {
    if ( is_singular() ) {
        wp_enqueue_style( 'us-gallery-magnific-popup', US_GALLERY_PLUGIN_URL . 'assets/css/magnific-popup.css', array(), '1.1.0' );
        wp_enqueue_style( 'us-gallery-frontend', US_GALLERY_PLUGIN_URL . 'assets/css/frontend.css', array(), '1.0.0' );

        wp_enqueue_script( 'us-gallery-magnific-popup', US_GALLERY_PLUGIN_URL . 'assets/js/magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
        wp_enqueue_script( 'us-gallery-frontend', US_GALLERY_PLUGIN_URL . 'assets/js/frontend.js', array( 'jquery', 'us-gallery-magnific-popup' ), '1.0.0', true );
    }
}

add_action( 'wp_enqueue_scripts', 'us_enqueue_frontend_assets' );

// Automatically compress images on upload
function us_compress_gallery_images( $image_data, $post_data ) {
    $image_path = get_attached_file( $image_data['ID'] );

    // Compress the image
    $quality = 75; // Adjust the quality as needed
    $image = wp_get_image_editor( $image_path );

    if ( ! is_wp_error( $image ) ) {
        $image->set_quality( $quality );
        $image->save( $image_path );
    }

    return $image_data;
}

add_filter( 'wp_generate_attachment_metadata', 'us_compress_gallery_images', 10, 2 );
