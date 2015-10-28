<?php
/**
 * Created by PhpStorm.
 * User: ThemeDevisior
 * Date: 8/10/2015
 * Time: 4:07 PM
 */


/*
 * Color picker helper file enqueue
 * */

add_action( 'admin_enqueue_scripts', 'sm_add_color_picker' );

add_action('wp_enqueue_scripts', 'add_metro_stylesheet');
add_action('wp_enqueue_scripts', 'add_metro_script');


function sm_add_color_picker( ) {

    if( is_admin() ) {
//        wp_enqueue_style('pricing-admin-bootstrap', plugins_url('../assets/css/bootstrap.min.css', __FILE__ ) );
//        wp_enqueue_style('pricing-admin-bootstrap-theme', plugins_url('../assets/css/bootstrap-theme.min.css', __FILE__ ) );
        wp_enqueue_style('jquery-ui-css', 'http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style('pricing-admin-style', plugins_url('../assets/css/pricing_admin.css', __FILE__ ) );
        wp_enqueue_style('sm-metabox-form', plugins_url('../assets/css/form.css', __FILE__ ) );
        wp_enqueue_style('pricing-fonts', plugins_url('../assets/css/font-awesome.min.css', __FILE__ ) );


        wp_enqueue_script( 'jquery-ui' );
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('jquery-ui-tabs');


        // Add the color picker css file
        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );

        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'metro-script-bootstrap', plugins_url( '../assets/js/bootstrap.min.js', __FILE__ ), array( 'bootstrap' ), false, true );
        wp_enqueue_script( 'metro-script-handler', plugins_url( '../assets/js/metro_script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

        wp_enqueue_media();

    }


}

function add_metro_stylesheet(){
    wp_enqueue_style('jquery-ui-css', 'http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style('pricing-admin-bootstrap', plugins_url('../assets/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('pricing-admin-bootstrap-theme', plugins_url('../assets/css/bootstrap-theme.min.css', __FILE__));
    wp_enqueue_style('metro-pricing-style', plugins_url('../assets/css/metro-pricing-style.css', __FILE__));

}
function add_metro_script(){
    wp_enqueue_script( 'jquery-ui' );
    wp_enqueue_script('jquery-ui-tooltip');
}
