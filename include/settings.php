<?php
/**
 * Created by Themedevisers.
 * User: ThemeDevisior
 * Date: 8/9/2015
 * Time: 5:29 PM
 */

    /*
    * Setting Page action hook
    * */
class PricingTableSetting extends FormElement {

    /**
     * Option key, and option page slug
     * @var string
     */
    private $key = 'pricing_options';

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
        $this->title = __( 'Pricing Settings', 'themedevisers' );


        // Set our CMB fields

    }

    /**
     * Initiate our hooks
     * @since 0.1.0
     */
    public function hooks() {
        add_action( 'admin_init', array( $this, 'init' ) );
        add_action('admin_menu', array( $this, 'setting_page') );
    }



    /**
     * Register our setting to WP
     * @since  0.1.0
     */
    public function init() {
        register_setting( $this->key, $this->key );
    }

    /**
     * Add Settings page
     * @since 0.1.0
     */
    public function setting_page() {
        add_submenu_page('edit.php?post_type=metro_pricing_table', 'Pricing Table Settings', 'Settings', 'edit_posts', basename(__FILE__), array( $this, 'setting_page_display' ));

    }


    /**
     * Setting Page Display Callback function
     * @since  0.1.0
     */
    public function setting_page_display() {
        ?>
        <div class="wrap cmb_options_page <?php echo $this->key; ?>">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

        <div class="homeposts">

            <div id="themedevisers_tabs">
                <ul class="themedevisers_tabs_list">
                    <li><a href="#themedevisers_basic_settings">Basic Settings </a></li>
                    <li><a href="#themedevisers_pricing_buttons">Add Buttons</a></li>
                </ul>

                <div class="tab_elements" id="themedevisers_basic_settings">
                    <h3> This is basic settings </h3>
                </div>
                <div class="tab_elements" id="themedevisers_pricing_buttons">
                    <h3> Add your payment method and custom buttons from here </h3>
                    <div class="button_body">
                        <div class="button_header">
                            <a href="#" class="button button-primary button-small payment_method"> +Payment Method </a>
                            <a href="#" class="button button-primary button-small custom_method"> + Custom Button  </a>
                        </div>
                        <div class="button_content">
                            <h5> Here is our buttons </h5>
                            <ul class="btn-holder">
                                <li>
                                    <div class="pricing_buttons">
                                        <a href="#" class="button button-success button-large"> Custom Button </a>
                                        <span> <a href="#" class="button button-delete button-small"> Delete </a></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


        </div>
        <?php
    }

}

// Get it started
$settings = new PricingTableSetting();
$settings->hooks();

/**
 * Wrapper function around cmb_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
