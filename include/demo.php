<?php
/**
 * Created by PhpStorm.
 * User: ThemeDevisior
 * Date: 8/9/2015
 * Time: 6:14 PM
 */
/**
 * CMB Theme Options
 * @version 0.1.0
 */
class myprefix_Admin {

    /**
     * Option key, and option page slug
     * @var string
     */
    private $key = 'myprefix_options';

    /**
     * Array of metaboxes/fields
     * @var array
     */
    protected $option_metabox = array();

    /**
     * Options Page title
     * @var string
     */
    protected $title = '';

    /**
     * Options Page hook
     * @var string
     */
    protected $options_page = '';

    /**
     * Constructor
     * @since 0.1.0
     */
    public function __construct() {
        // Set our title
        $this->title = __( 'Site Options', 'myprefix' );

        // Set our CMB fields
        $this->fields = array(
            array(
                'name' => __( 'Test Text', 'myprefix' ),
                'desc' => __( 'field description (optional)', 'myprefix' ),
                'id'   => 'test_text',
                'type' => 'text',
            ),
            array(
                'name'    => __( 'Test Color Picker', 'myprefix' ),
                'desc'    => __( 'field description (optional)', 'myprefix' ),
                'id'      => 'test_colorpicker',
                'type'    => 'colorpicker',
                'default' => '#ffffff'
            ),
        );
    }

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function hooks() {
        add_action( 'admin_init', array( $this, 'init' ) );
        add_action('admin_menu', array( $this, 'demo_setting') );
    }

    public function demo_setting() {
        add_submenu_page('edit.php?post_type=metro_pricing_table', 'Custom Post Type Admin', 'Settings Demo', 'edit_posts', basename(__FILE__), array( $this, 'admin_page_display' ));

    }


    /**
     * Register our setting to WP
     * @since  0.1.0
     */
    public function init() {
        register_setting( $this->key, $this->key );
    }

    /**
     * Add menu options page
     * @since 0.1.0
     */
    public function add_options_page() {
        $this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
    }

    /**
     * Admin page markup. Mostly handled by CMB
     * @since  0.1.0
     */
    public function admin_page_display() {
        ?>
        <div class="wrap cmb_options_page <?php echo $this->key; ?>">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <?php cmb_metabox_form( $this->option_metabox(), $this->key ); ?>
        </div>
        <?php
    }

    /**
     * Defines the theme option metabox and field configuration
     * @since  0.1.0
     * @return array
     */
    public function option_metabox() {
        return array(
            'id'         => 'option_metabox',
            'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
            'show_names' => true,
            'fields'     => $this->fields,
        );
    }

    /**
     * Public getter method for retrieving protected/private variables
     * @since  0.1.0
     * @param  string  $field Field to retrieve
     * @return mixed          Field value or exception is thrown
     */
    public function __get( $field ) {

        // Allowed fields to retrieve
        if ( in_array( $field, array( 'key', 'fields', 'title', 'options_page' ), true ) ) {
            return $this->{$field};
        }
        if ( 'option_metabox' === $field ) {
            return $this->option_metabox();
        }

        throw new Exception( 'Invalid property: ' . $field );
    }

}

// Get it started
$myprefix_Admin = new myprefix_Admin();
$myprefix_Admin->hooks();

/**
 * Wrapper function around cmb_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function myprefix_get_option( $key = '' ) {
    global $myprefix_Admin;
    return cmb_get_option( $myprefix_Admin->key, $key );
}