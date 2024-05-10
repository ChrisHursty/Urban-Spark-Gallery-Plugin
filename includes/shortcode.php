<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Register Shortcode
function us_gallery_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'id' => '',
            'columns' => 3,
            'captions' => 'true'
        ),
        $atts,
        'us_gallery'
    );

    $post_id = intval( $atts['id'] );
    if ( ! $post_id ) {
        return '';
    }

    // Fetch gallery images
    $images = get_post_meta( $post_id, '_us_gallery_images', true );
    if ( ! is_string( $images ) || empty( $images ) ) {
        return '';
    }

    $images = explode( ',', $images );
    $columns = max( 1, min( 6, intval( get_post_meta( $post_id, '_us_gallery_columns', true ) ) ) );
    $captions = filter_var( get_post_meta( $post_id, '_us_gallery_captions', true ), FILTER_VALIDATE_BOOLEAN );
    $style = get_post_meta( $post_id, '_us_gallery_style', true ) ?: 'default';
    $unique_id = 'us-gallery-' . uniqid();

    ob_start();
    ?>
    <div id="<?php echo esc_attr( $unique_id ); ?>" class="us-gallery us-gallery-<?php echo esc_attr( $style ); ?> us-gallery-columns-<?php echo esc_attr( $columns ); ?>" role="list" aria-label="<?php esc_attr_e( 'Image gallery', 'us-gallery' ); ?>">
        <?php foreach ( $images as $image_id ) : ?>
            <?php
            $image_url = wp_get_attachment_image_src( $image_id, 'medium_large' );
            $full_image_url = wp_get_attachment_image_src( $image_id, 'full' );
            $caption = wp_get_attachment_caption( $image_id );
            $alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
            $aria_label = $caption ? $caption : ( $alt_text ? $alt_text : __( 'Gallery image', 'us-gallery' ) );
            ?>
            <figure class="us-gallery-item" role="listitem">
                <a href="<?php echo esc_url( $full_image_url[0] ); ?>" class="us-gallery-link" data-mfp="image" data-gallery="<?php echo esc_attr( $unique_id ); ?>" aria-label="<?php echo esc_attr( $aria_label ); ?>" tabindex="0">
                    <img src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( $aria_label ); ?>" loading="lazy">
                </a>
                <?php if ( $captions && $caption ) : ?>
                    <figcaption class="us-gallery-caption"><?php echo esc_html( $caption ); ?></figcaption>
                <?php else : ?>
                    <figcaption class="us-gallery-caption" style="display: none;"></figcaption>
                <?php endif; ?>
            </figure>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode( 'us_gallery', 'us_gallery_shortcode' );
