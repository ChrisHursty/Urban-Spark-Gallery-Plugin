<?php
/**
 * Plugin Name: Urban Spark Gallery Plugin
 * Plugin URI: https://urbanwp.com/urban-spark-gallery-plugin
 * Description: A simple and accessible gallery plugin with responsive grids and Magnific Popup.
 * Version: 1.0.0
 * Author: Urban Spark
 * Author URI: https://urbanwp.com
 * Text Domain: us-gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define constants
define( 'US_GALLERY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'US_GALLERY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once US_GALLERY_PLUGIN_DIR . 'includes/cpt.php';
require_once US_GALLERY_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once US_GALLERY_PLUGIN_DIR . 'includes/shortcode.php';
require_once US_GALLERY_PLUGIN_DIR . 'includes/assets.php';

