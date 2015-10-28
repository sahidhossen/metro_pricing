<?php
/**
 * Created by PhpStorm.
 * User: ThemeDevisior
 * Date: 8/9/2015
 * Time: 4:43 PM
 */

class Pricing_post_type {

    /*
     * Text domain for this plugins
     * */
    public $text_domain = 'themedevisers';

    /*
     * Prefix name
     * */
    private $prefix = 'sm_';

    function __construct() {
        /* Hook into the 'init' action so that the function
        * Containing our post type registration is not
        * unnecessarily executed.
        */
        add_action( 'init', array( $this,'pricing_table_post_field'), 0 );
    }

    /*
* Creating a function to create our CPT
*/

   public function pricing_table_post_field() {

// Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'Metro Pricing Table', 'Metro Pricing Table', $this->text_domain ),
            'singular_name'       => _x( 'Pricing Table', 'Metro Pricing Table',  $this->text_domain ),
            'menu_name'           => __( 'Pricing Table', $this->text_domain ),
            'parent_item_colon'   => __( 'Parent Table', $this->text_domain ),
            'all_items'           => __( 'All Pricing Tables', $this->text_domain ),
            'view_item'           => __( 'View Pricing Table', $this->text_domain ),
            'add_new_item'        => __( 'Add Pricing Table', $this->text_domain ),
            'add_new'             => __( 'Add New', $this->text_domain ),
            'edit_item'           => __( 'Edit Pricing Table', $this->text_domain ),
            'update_item'         => __( 'Update Pricing Table', $this->text_domain ),
            'search_items'        => __( 'Search Pricing Table', $this->text_domain ),
            'not_found'           => __( 'Not Found', $this->text_domain ),
            'not_found_in_trash'  => __( 'Not found in Trash', $this->text_domain ),
        );

// Set other options for Custom Post Type

        $args = array(
            'label'               => __( 'pricing_table', $this->text_domain ),
            'description'         => __( 'Pricing Table information', $this->text_domain ),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title', ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies'          => array( 'pricing_table' ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );

        // Registering your Custom Post Type
        register_post_type( 'metro_pricing_table', $args );

    }


}


$pricing_post_type = new Pricing_post_type();