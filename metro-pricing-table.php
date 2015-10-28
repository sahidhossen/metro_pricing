<?php
/*
Plugin Name: Metro Pricing Table
Plugin URI: http://themedevisers.com
Description: WordPress Plugin for creating colorful pricing tables with drug and drops
Author: Sahid Hossen
Version: 1.0.1
Author URI: http://sahidhossen.com/
*/

/*
 * Add CMB metabox functions
 * */
//add_action( 'init', 'themedevisers_initialize_cmb_meta_boxes' );

//function themedevisers_initialize_cmb_meta_boxes() {
//    if ( !class_exists( 'cmb_Meta_Box' ) ) {
//        require_once( 'include/CMB/init.php' );
//    }
//}
/*
 * Include Form Element
 * */

require_once 'libs/forms.php';
/*
 * Wp enqueue script file
 * */
require_once 'libs/enquque_scripts.php';

/*
 * Require custom post type
 * */
require_once 'include/post-type.php';

/*
 * Metabox settings
 * */
require_once 'include/metaboxs.php';

/*
 * Setting Page for pricing table
 * */
require_once 'include/settings.php';


/*
 * Pricing Ajax methods
 * */
require_once 'include/pricing_ajax.php';

/*
 * Design bar
 * */
require_once 'libs/Metro_design_bar.php';
/*
 * Shortcode
 * */
require_once 'include/metro_shortcode.php';

