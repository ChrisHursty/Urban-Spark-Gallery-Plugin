<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Enqueue admin scripts
function us_enqueue_admin_scripts( $hook ) {
    global $post_type;

    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        if ( 'us_gallery' === $post_type ) {
            wp_enqueue_media();
            wp_enqueue_script( 'us-gallery-admin', US_GALLERY_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0.1', true );
            wp_enqueue_style( 'us-gallery-admin', US_GALLERY_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0' );
        }
    }
}

add_action( 'admin_enqueue_scripts', 'us_enqueue_admin_scripts' );

// Add Meta Box
function us_add_gallery_meta_box() {
    add_meta_box(
        'us_gallery_images',
        __( 'Gallery Images', 'us-gallery' ),
        'us_gallery_images_meta_box_callback',
        'us_gallery',
        'normal',
        'high'
    );
    add_meta_box(
        'us_gallery_settings',
        __( 'Gallery Settings', 'us-gallery' ),
        'us_gallery_settings_meta_box_callback',
        'us_gallery',
        'side',
        'default'
    );
}

add_action( 'add_meta_boxes', 'us_add_gallery_meta_box' );

// Meta Box Callback: Images
function us_gallery_images_meta_box_callback( $post ) {
    wp_nonce_field( 'us_save_gallery', 'us_gallery_nonce' );

    $images = get_post_meta( $post->ID, '_us_gallery_images', true );
    if ( ! is_string( $images ) ) {
        $images = '';
    }

    ?>
    <div id="us-gallery-images-container">
        <ul class="us-gallery-images">
            <?php
            if ( ! empty( $images ) ) {
                foreach ( explode( ',', $images ) as $image_id ) {
                    $img_src = wp_get_attachment_image_src( $image_id, 'thumbnail' );
                    ?>
                    <li data-attachment-id="<?php echo esc_attr( $image_id ); ?>">
                        <img src="<?php echo esc_url( $img_src[0] ); ?>" alt="">
                        <a href="#" class="us-remove-image">x</a>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
    </div>
    <input type="hidden" id="us-gallery-images" name="us_gallery_images" value="<?php echo esc_attr( $images ); ?>">
    <button type="button" class="button button-primary us-add-gallery-images"><?php _e( 'Add Images', 'us-gallery' ); ?></button>
    <?php
}

// Meta Box Callback: Settings
function us_gallery_settings_meta_box_callback( $post ) {
    $columns = get_post_meta( $post->ID, '_us_gallery_columns', true ) ?: 3;
    $captions = get_post_meta( $post->ID, '_us_gallery_captions', true ) ?: 'true';
    $style = get_post_meta( $post->ID, '_us_gallery_style', true ) ?: 'default';

    ?>
    <p>
        <label for="us-gallery-columns"><?php _e( 'Columns', 'us-gallery' ); ?></label>
        <input type="number" name="us_gallery_columns" id="us-gallery-columns" value="<?php echo esc_attr( $columns ); ?>" min="1" max="6" step="1">
    </p>
    <p>
        <label for="us-gallery-captions"><?php _e( 'Show Captions', 'us-gallery' ); ?></label>
        <select name="us_gallery_captions" id="us-gallery-captions">
            <option value="true" <?php selected( $captions, 'true' ); ?>><?php _e( 'Yes', 'us-gallery' ); ?></option>
            <option value="false" <?php selected( $captions, 'false' ); ?>><?php _e( 'No', 'us-gallery' ); ?></option>
        </select>
    </p>
    <p>
        <label for="us-gallery-style"><?php _e( 'Gallery Style', 'us-gallery' ); ?></label>
        <select name="us_gallery_style" id="us-gallery-style">
            <option value="default" <?php selected( $style, 'default' ); ?>><?php _e( 'Default', 'us-gallery' ); ?></option>
            <option value="masonry" <?php selected( $style, 'masonry' ); ?>><?php _e( 'Masonry', 'us-gallery' ); ?></option>
            <option value="hover-effect" <?php selected( $style, 'hover-effect' ); ?>><?php _e( 'Hover Effect', 'us-gallery' ); ?></option>
        </select>
    </p>
    <?php
}

// Save Gallery Data
function us_save_gallery( $post_id ) {
    if ( ! isset( $_POST['us_gallery_nonce'] ) || ! wp_verify_nonce( $_POST['us_gallery_nonce'], 'us_save_gallery' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save Images
    if ( isset( $_POST['us_gallery_images'] ) && is_string( $_POST['us_gallery_images'] ) ) {
        update_post_meta( $post_id, '_us_gallery_images', sanitize_text_field( $_POST['us_gallery_images'] ) );
    } else {
        delete_post_meta( $post_id, '_us_gallery_images' );
    }

    // Save Columns
    if ( isset( $_POST['us_gallery_columns'] ) ) {
        update_post_meta( $post_id, '_us_gallery_columns', intval( $_POST['us_gallery_columns'] ) );
    }

    // Save Captions
    if ( isset( $_POST['us_gallery_captions'] ) ) {
        update_post_meta( $post_id, '_us_gallery_captions', sanitize_text_field( $_POST['us_gallery_captions'] ) );
    }

    // Save Style
    if ( isset( $_POST['us_gallery_style'] ) ) {
        update_post_meta( $post_id, '_us_gallery_style', sanitize_text_field( $_POST['us_gallery_style'] ) );
    }
}

add_action( 'save_post', 'us_save_gallery' );
