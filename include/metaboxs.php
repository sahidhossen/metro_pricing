<?php
/**
 * Created by PhpStorm.
 * User: ThemeDevisior
 * Date: 8/10/2015
 * Time: 4:36 PM
 */


class PMB extends FormElement{

    /*
     * plugins text-domain
     * */

    public $id_base = 'themedevisers';

    public $field_base = 'sm_themedevisers';
    /*
     * Form name holder
     * */
    public  $defaults = array();

    /*
     * Metabox information array
     * */
    public $metaboxelements = array();
    /*
     * Default items for pricing table
     * */

    public $default_items = array();
    /*
     * construct function for setting metabox
     * */

    /**
     *
     */
    public function __construct(){

        add_action( 'admin_menu', array( $this, 'add_meta_box' ) );

        add_action( 'save_post', array( $this, 'save' ), 1, 2 );

        /*
         * Metabox elements
         * */
        $this->metaboxelements =
            array(
                array(
                'id'=>'metro_pricing_column',
                'title'=>'Build Your Pricing Table',
                'call_back_fn'=>'pricing_table_column',
                'noance' => rand(0,255),
             ),
                array(
                    'id'=>'pricing_feature_box',
                    'title'=>'Type Pricing Feature',
                    'call_back_fn'=>'pricing_features',
                    'noance' => rand(0,255),
                ),

        );

        $this->defaults = array(
            'column_ids'=>rand(0,1000).','.rand(0,1000).','.rand(0,1000),
            'my_text_field'=>'This is fields',
            'feature_item'=>'',
            'default_items'=>'',
            'sm_themedevisers'=>'',
            'design'=>array(
                'price-radius'=>'on',
                'price-font-size'=>'18',
                'price-bg-color'=>'#2e890a',
                'feature_image'=>'',
                'background_image'=>'',
                'background_color'=>'#ffdddd',
                'background_repeate'=>'',
                'tooltip_activity'=>'on',
                'tooltip_bg_color'=>'#000000',
                'tooltip_position'=>'tooltip-bottom',
            ),
            'feature_list'=> "24/7  Tech Support \n Advance Option \n 100GB Storage \n 1 GB Bandwidth \n Something Else \n Another Feature Here",
        );
        $this->table_defaults = array(
            'title'=>"STARTER",
            'feature_serial_numbers'=>'',
            'badge_text'=>'Badge Text',
            'icons'=>'',
            //Pricing Section
            'price'=>'99.99',
            'sub-price'=>'55',
            'recurrence'=>'',
            'design'=>array(
                'price-radius'=>'off',
                'price-font-size'=>'18',
                'price-bg-color'=>'#2e890a',
                'feature_image'=>'',
                'background_image'=>'',
                'background_color'=>'#dddddd',
                'background_repeate'=>'',
            ),
            //Tooltip Information
            'tooltip'=>'',
            'tooltip_numbers'=>NULL,
            /*
             * Footer Elements
             * */
            'button_text'=>'Submit',
            'button_link'=>'',
            'payment_getway_link'=>'',
            /*
             * Design bar components
             * */

        );

        foreach(explode(',', $this->defaults['column_ids']) as $column_id ) {
            $this->defaults['columns'][$column_id] = $this->table_defaults;
        }

    }

    /*
     * Metabox field name collection
     * */
    public function get_field_name( $fieldname=null ) {

       foreach( array_keys($this->defaults) as $fields ){
           if( $fields == $fieldname ) {
               return $fieldname;
               break;
           }
       }
        return null;
    }


    /**
     * Hold metaboxes
     */
    public function add_meta_box(){
        $args = $this->metaboxelements;
        $defaults = array(
            'page'=>'metro_pricing_table',
        );

        foreach( $args as $arg ) {
            $box = (object) wp_parse_args( $arg, $defaults );
            add_meta_box( $box->id, __($box->title,$this->text_domain),array($this,$box->call_back_fn),$box->page );
        }
    }

    public function pricing_features(){
        global $post;

        foreach( array_keys($this->defaults) as $field ) {
            $data = get_post_meta($post->ID, $field, true);

            if( $data ) {
                $value[$field] = $data;
            }else {
                $value[$field] = $this->defaults[$field];
            }
        }
        $value = array_filter( $value );

        ?>
        <div class="row">
            <label class="col-md-3 metro-label" for="Select Color">  Select Color </label>
            <div class="col-md-8 feature_list_box">
                <p>
                    <?php
                    echo $this->input(
                        array(
                            'type' => 'textarea',
                            'name' => $this->get_field_name('feature_list'),
                            'id' => $this->id_base.'_feature_list',
                            'value'=>$value['feature_list'],
                            'rows'=>'12',
                            'label' => __( 'Feature List' , 'themedeviser' )
                        )
                    );
                   ?>
                </p>

            </div>
            <div class="feature_item">
                <div class="metro_handler" id="feature_handler"> Pricing Feature Item </div>
                <ul class="sortable" id="<?php echo $this->id_base ?>_sortable_features_default" data-id_base="<?php echo $this->id_base; ?>">
                    <li class="f_item ui-draggable ui-draggable-handle"> <span class="icon-bar"> </span> This is check icon
                    </li>
                    <li class="f_item ui-draggable ui-draggable-handle"> This is cross icon  </li>
                </ul>
                <ul class="sortable" id="<?php echo $this->id_base ?>_sortable_features">
                <?php
                $features = ( !empty($value['feature_list']) ) ? array_filter( explode("\n", $value['feature_list']) ) : NULL;
                $number = 1;
                foreach( $features as $feature ) {
                    $this->feature_loop($feature, $number );
                    $number++;
                }
                ?>
                </ul>
            </div>
        </div>
        <?php
    }

    /*
     * Metabox section 2
     * @ Add pricing table and return it in admin section
     * */
    /**
     *
     */
    public function pricing_table_column(){
        global $post;

        foreach( array_keys($this->defaults) as $field ) {
            $data = get_post_meta($post->ID, $field, true);
            if( $data ) {
                $value[$field] = $data;
            }else {
                $value[$field] = $this->defaults[$field];
            }
        }

        $value = array_filter( $value );

        $default_settings = array(
            'id_base'=>$this->id_base,
            'post_id'=>$post->ID
        );
        $default_settings = (object)$default_settings;
        $metro_fields_settings = array(
            'name'=>$this->get_field_name('design'),
            'id'=>$this->id_base.'_design',
        );

        ?>

        <div class="row">
            <div class="col-md-12 pricing-top-bar">
                <div class="topbar">
                    <?php $this->design_bar(
                        false,
                        $value['design'],
                        array(
                            'feature_image',
                            'background',
                            'price_settings',
                            'custom',
                        ),
                        array(
                            'tooltip_activity'=>array(
                                'icon-css' => 'fa fa-adjust',
                                'label' => __( 'List Style', 'layerswp' ),
                                'wrapper-class' => 'layers-small to layers-pop-menu-wrapper layers-animate',
                                'elements' => array(
                                    'tooltip_activity'=>array(
                                        'type'=>'checkbox',
                                        'label'=>__('Show/Hide Tooltip','themedevisers'),
                                        'name'=>$this->get_field_name('design').'[tooltip_activity]',
                                        'id'=>$this->id_base.'_design_tooltip_activity',
                                        'value'=>(isset($value['design']['tooltip_activity'])) ? $value['design']['tooltip_activity'] : NULL,
                                    ),
                                    'tooltip_bg_color'=>array(
                                        'type'=>'color',
                                        'label'=>__('Background Color ', 'themedevisers'),
                                        'name'=>$this->get_field_name('design').'[tooltip_bg_color]',
                                        'id'=>$this->id_base.'_design_tooltip_activity',
                                        'value'=>(isset($value['design']['tooltip_bg_color'])) ? $value['design']['tooltip_bg_color'] : NULL,
                                    ),
                                    'tooltip_position'=>array(
                                        'type'=>'select',
                                        'label'=>__('Tooltip Position', 'themedevisers'),
                                        'name'=>$this->get_field_name('design').'[tooltip_position]',
                                        'id'=>$this->id_base.'_design_tooltip_position',
                                        'value'=>(isset($value['design']['tooltip_position'])) ? $value['design']['tooltip_position'] : NULL,
                                        'options'=>array(
                                            'tooltip-top'=>__('Tooltip Top', 'themedevisers'),
                                            'tooltip-right'=>__('Tooltip Right', 'themedevisers'),
                                            'tooltip-bottom'=>__('Tooltip Bottom', 'themedevisers'),
                                            'tooltip-left'=>__('Tooltip Left', 'themedevisers'),
                                        )
                                    ),
                                )
                            ),
                        ),
                        $metro_fields_settings
                    ); ?>
                </div>
            </div>
        </div>
        <?php
        echo $this->input(
            array(
                'type' => 'hidden',
                'name' => $this->get_field_name('column_ids') ,
                'id' => $this->id_base.'_column_ids',
                'value'=>$value['column_ids'],
            )
        );
        ?>
        <div class="row table-holder">

            <ul id="themedeviser_pricing_table_<?php echo $post->ID; ?>" class="table_lists" data-id_base="<?php echo $this->id_base; ?>">
            <?php

            $columns = ( !empty( $value['column_ids'])) ? $value['column_ids'] : NULL;

            $columns = array_filter( explode(',', $columns));

            //Grabe all data from the root index

            $custom_field_data = get_post_meta($post->ID, 'sm_themedevisers', true);

         //   var_dump($custom_field_data);
            $features = ( !empty($value['feature_list']) ) ? array_filter( explode("\n", $value['feature_list']) ) : NULL;
            if(count($features)>0) {
                for ($i = 0; $i <count($features); $i++) $numbers[] = $i;
            }

            foreach( $columns as $column_id ){

                /*
                * Get each column data from the custom field data
                * @exmp: sm_themedevisers[post_id][columns][column_id][field_name]
                * */
                $this->metro_table_item (
                    array(
                        'id_base'=>$this->id_base,
                        'post_id'=>$post->ID
                    ), (isset($custom_field_data[$post->ID]['columns'][$column_id])) ? $custom_field_data[$post->ID]['columns'][$column_id] : NULL, $column_id, $features, $numbers);
            }
            ?>
            </ul>
        </div>
        <div class="row">
            <p class="text-left">
                <a href="#" class="button button-primary button-large add_pricing_column" data-post_id="<?php echo $post->ID; ?>" data-id_base="<?php echo $this->id_base; ?>"> +Add </a>
            </p>
        </div>
        <?php
    }

    /**
     * @param array $default_settings
     * @param null $instance
     * @param $column_id
     * @param array $features
     * @param array $numbers
     */
    public function metro_table_item($default_settings = array(), $instance=NULL, $column_id, $features=array(), $numbers=array()){

        $features_keys = array_unique(array_keys($features));
        /*
         * Get table field for each text fields
         * */
        $default_fields = $this->table_defaults;

        /*
         * Get marge the default fields and instance value for easily retrive the values
         * */
        $instance = wp_parse_args($instance, $default_fields );
        //   extract( $instance, EXTR_SKIP );

        $default_settings = (object)$default_settings;

        if( !isset( $column_id )) $column_id = rand(1,1000);

        $default_settings->column_id = $column_id;

        $metro_fields_settings = array(
            'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'design' ),
            'id' => $this->get_custom_field_id( $default_settings, 'columns',  $column_id, 'design' ),
        )

        ?>
        <li class="col-md-8 metro_pricing_table_list" data-guid="<?php echo $column_id?>" id="sm_pricing_table_<?php echo $column_id ?>">
            <div class="handlediv" title="Click to toggle"><br /></div>
            <h3 class='hndle'><span>Section Label <?php echo $column_id; ?></span></h3>
            <div class="inside pricingtble">
                <div class="topbar">
                    <?php $this->design_bar(
                        'icon_class',
                        $instance['design'],
                        array(
                            'feature_image',
                            'background',
                            'price_settings',
                        ),
                        array(
                            'inputbox'=>"Here is input box",
                        ),
                        $metro_fields_settings
                    ); ?>
                </div>
                <?php
                /*
                 * Primary Feature Lists
                 * */
                ?>
                <p>
                    <?php

                    echo $this->input(
                        array(
                            'type'=>"hidden",
                            'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'feature_serial_numbers' ),
                            'id'=>$default_settings->id_base."_feature_serial_numbers_".$column_id,
                            'value'=>(isset( $instance['feature_serial_numbers'] )) && strlen($instance['feature_serial_numbers'])>0 ? $instance['feature_serial_numbers'] : implode(',',$numbers),
                        )
                    );
                    /*
                     * Tooltips random numbers holder
                     * */
                    echo $this->input(
                        array(
                            'type'=>"hidden",
                            'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'tooltip_numbers' ),
                            'id'=>$default_settings->id_base."_tooltip_numbers_".$column_id,
                            'value'=>(isset( $instance['tooltip_numbers'] )) ? $instance['tooltip_numbers'] : NULL,
                        )
                    );

                    ?>

                </p>



                <!--   header section -->
                <p class="pricing-section-title"> Header section </p>
                <ul class="table_items header_item_list">
                    <li class="icon header_item" data-field="title">
                        <span class="data"> <?php echo (isset($instance['title'])) ? $instance['title'] : 'Pricing Title'; ?> </span>
                        <div class="dropbox-holder">

                            <div class="themedevisers_dropbox">
                                <label for="<?php echo $this->get_custom_field_id($default_settings, 'columns', $column_id,'title' ); ?>"> <?php _e('Title','themedevisers'); ?></label>
                                <?php
                                echo $this->input(
                                    array(
                                        'type'=>"text",
                                        'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'title' ),
                                        'id'=>$this->get_custom_field_id($default_settings, 'columns', $column_id,'title' ),
                                        'value'=>(isset( $instance['title'] )) ? $instance['title'] : '',
                                        'class'=>'pricing_title',
                                    )
                                );
                                ?>
                            </div>
                            <a href="#" class="button button-primary button-small btn-header"> Change </a>
                        </div>
                    </li>
                    <li class="icon header_item" data-field="pricing">
                        <span class="data">
                            <span class="price"> Price: <?php echo (isset($instance['price'])) ? $instance['price']."/" : ''; ?></span>
                            <span class="sub-price"> Sub Price:  <?php echo (!empty($instance['sub-price'])) ? $instance['sub-price']."/" : '---' ?></span>
                            <span class="recurrence"> (<?php echo (isset($instance['recurrence'])) ? $instance['recurrence'] : ''; ?>)</span>

                        </span>
                        <div class="dropbox-holder">

                            <div class="themedevisers_dropbox">
                                <label for="<?php echo $this->get_custom_field_id($default_settings, 'columns', $column_id,'price' ); ?>"> <?php _e('Price','themedevisers'); ?></label>
                                <?php
                                echo $this->input(
                                    array(
                                        'type'=>"text",
                                        'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'price' ),
                                        'id'=>$this->get_custom_field_id($default_settings, 'columns', $column_id,'price' ),
                                        'value'=>(isset( $instance['price'] )) ? $instance['price'] : '',
                                        'class'=>'price',
                                    )
                                );
                                ?>
                                <label for="<?php echo $this->get_custom_field_id($default_settings, 'columns', $column_id,'sub-price' ); ?>"> <?php _e('Sub Price','themedevisers'); ?></label>
                                <?php
                                echo $this->input(
                                    array(
                                        'type'=>"text",
                                        'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'sub-price' ),
                                        'id'=>$this->get_custom_field_id($default_settings, 'columns', $column_id,'sub-price' ),
                                        'value'=>(isset( $instance['sub-price'] )) ? $instance['sub-price'] : '',
                                        'class'=>'sub-price',
                                    )
                                );
                                ?>
                                <label for="<?php echo $this->get_custom_field_id($default_settings, 'columns', $column_id,'recurrence' ); ?>"> <?php _e('Recurrence','themedevisers'); ?></label>
                                <?php
                                echo $this->input(
                                    array(
                                        'type'=>"text",
                                        'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'recurrence' ),
                                        'id'=>$this->get_custom_field_id($default_settings, 'columns', $column_id,'recurrence' ),
                                        'value'=>(isset( $instance['recurrence'] )) ? $instance['recurrence'] : '',
                                        'class'=>'recurrence',
                                    )
                                );
                                ?>
                            </div>
                            <a href="#" class="button button-primary button-small btn-header"> Change </a>
                        </div>

                    </li>
                    <li class="icons header_item">
                        <?php echo $this->input(
                            array(
                                'type'=>'select',
                                'label' => __( '' , 'themedevisers' ),
                                'name' =>$this->get_custom_field_name($default_settings,'columns',$column_id,'icons' ),
                                'id'=>$this->id_base."_icons_".$column_id,
                                'value'=>(isset($instance['icons'])) ? $instance['icons'] : NULL,
                                'options'=> array(
                                    '1' => __( '1 of 12 columns' , 'themedevisers' ),
                                    '2' => __( '2 of 12 columns' , 'themedevisers' ),
                                    '3' => __( '3 of 12 columns' , 'themedevisers' ),
                                    '4' => __( '4 of 12 columns' , 'themedevisers' ),
                                    '5' => __( '5 of 12 columns' , 'themedevisers' ),
                                    '6' => __( '6 of 12 columns' , 'themedevisers' ),
                                    '7' => __( '7 of 12 columns' , 'themedevisers' ),
                                    '8' => __( '8 of 12 columns' , 'themedevisers' ),
                                    '9' => __( '9 of 12 columns' , 'themedevisers' ),
                                    '10' => __( '10 of 12 columns' , 'themedevisers' ),
                                    '11' => __( '11 of 12 columns' , 'themedevisers' ),
                                    '12' => __( '12 of 12 columns' , 'themedevisers' )
                                ),
                            )
                        ) ?>
                    </li>
                </ul>


                <!-- body section-->
                <p class="pricing-section-title"> Body section </p>
                <ul id="sm_feature_list_<?php echo $column_id ?>" class="table_items accept_feature" data-guid="<?php echo $column_id; ?>" data-post_id="<?php echo $default_settings->post_id;?>">
                    <?php

                    $randoms =  (!empty($instance['tooltip_numbers'])) ? explode(',',$instance['tooltip_numbers']) : NULL;

                    $fieldFeatureItem =  explode(',',$instance['feature_serial_numbers']);

                    /*
                     * Add array key for tooltip to random
                     * */
                    $toolTip_keys =array();
                    if($instance['tooltip'] !='' ) {
                        $toolTip_keys = array_keys($instance['tooltip']);
                        $i = 0;
                        foreach ($randoms as $rand) {
                            $final_ran[$toolTip_keys[$i++]] = $rand;

                        }
                    }

                    /*
                     * Error: If apply array_filter then its escape the 0
                     * So I just escape the empty space from here
                     * */
                    foreach($fieldFeatureItem as $field ) {
                        if ( $field == "" )
                            $fieldFeatureItem = array();
                    }

                    $fieldFeatureItem = (count($fieldFeatureItem)>0) ?  $fieldFeatureItem : $features_keys;

                    $itemExtract = array();
                    for($i=0;$i<count($fieldFeatureItem);$i++ ){
                        for($j=0;$j<count($features_keys); $j++ ){
                            if( $features_keys[$j] == $fieldFeatureItem[$i])
                                $itemExtract[] = $fieldFeatureItem[$i];
                        }
                    }


                    if( count(array_filter($itemExtract)) > 0 ) {

                        foreach ($itemExtract as $item) {

                            /*
                           * @param Get Random number for tooltips
                           * */
                            $random =  ($final_ran[$item]!=NULL) ? $final_ran[$item] : rand(1,1000);
                            ?>
                            <li data-feature="<?php echo $item; ?>" class="item_<?php echo $item; ?>" data-tooltip="<?php echo $random; ?>">
                                <span class="data"> <?php echo '<span>' . $item . '</span>' . ' : ' . $features[$item]."= ". $item; ?></span>
                                <div class="dropbox-holder">
                                    <div class="themedevisers_dropbox">
                                        <label for="<?php $this->get_custom_field_id( $default_settings,'columns',$column_id,'tooltip')."_".$item."_".$random ?>"> <?php _e('Tooltip ', 'themedevisers') ?> </label>
                                        <?php
                                        echo $this->input(
                                            array(
                                                'type'=>'text',
                                                'name'=>$this->get_custom_field_name( $default_settings,'columns',$column_id,'tooltip').'['.$item.']['.$random.']',
                                                'id'=>$this->get_custom_field_id( $default_settings,'columns',$column_id,'tooltip')."_".$item."_".$random,
                                                'value'=>(isset($instance['tooltip'][$item][$random])) ? $instance['tooltip'][$item][$random] : 'Tooltip',
                                                'class'=>'tooltip',
                                            )
                                        )
                                        ?>
                                    </div>
                                    <a href="#" title="<?php echo  (isset($instance['tooltip'][$item][$random])) ? $instance['tooltip'][$item][$random] : 'Tooltip'; ?>" class="add-tooltip button button-primary button-small"> Tooltip </a>

                                </div>


                            </li>
                            <?php
                        }
                        $itemExtract = array();
                        $instance['tooltip'] = array();
                    }else{
                        ?>
                        <li class="empty-list"> <span> DRAGE FEATURES ITEM HERE </span> </li>

                        <?php
                    }
                    ?>
                </ul>

                <!-- footer section-->
                <p class="pricing-section-title"> Footer section </p>
                <ul class="table_items footer_item_list">
                    <li class="footer_item">
                        <span class="data">  <?php echo (isset($instance['button_text'])) ? $instance['button_text'] : 'Submit' ?> </span>
                        <div class="dropbox-holder">
                            <div class="themedevisers_dropbox">
                                <label for="<?php echo $this->get_custom_field_id($default_settings, 'columns', $column_id,'button_text' ); ?>"> Button Text </label>
                                <?php
                                    echo $this->input(
                                        array(
                                            'type'=>'text',
                                            'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'button_text' ),
                                            'id'=>$this->get_custom_field_id($default_settings, 'columns', $column_id,'button_text' ),
                                            'value'=>(isset($instance['button_text'])) ? $instance['button_text'] : '',
                                            'class'=>'button_text',
                                        )
                                    )
                                ?>
                                <label for="<?php echo $this->get_custom_field_id($default_settings, 'columns', $column_id,'button_link' ); ?>"> Button link </label>
                                <?php
                                echo $this->input(
                                    array(
                                        'type'=>'text',
                                        'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'button_link' ),
                                        'id'=>$this->get_custom_field_id($default_settings, 'columns', $column_id,'button_link' ),
                                        'value'=>(isset($instance['button_link'])) ? $instance['button_link'] : '',
                                        'class'=>'button_link',
                                    )
                                )
                                ?>
                                <label for="<?php echo $this->get_custom_field_id($default_settings, 'columns', $column_id,'payment_getway_link' ); ?>"> Payment Getway link </label>
                                <?php
                                echo $this->input(
                                    array(
                                        'type'=>'textarea',
                                        'name'=>$this->get_custom_field_name($default_settings, 'columns', $column_id,'payment_getway_link' ),
                                        'id'=>$this->get_custom_field_id($default_settings, 'columns', $column_id,'payment_getway_link' ),
                                        'value'=>(isset($instance['payment_getway_link'])) ? $instance['payment_getway_link'] : '',
                                        'class'=>'payment_getway_link',
                                        'rows' => 6
                                    )
                                )
                                ?>

                            </div>
                            <a href="#" class="button button-primary button-small btn-info"> Change </a>
                        </div>
                    </li>
                </ul>
            </div>

        </li>
        <?php
    }

    /*
     * Save the metaboxes with all fields
     * */

    public function save(){
        global $post_id;
       //  exit();
        /*
         * Restore all of the data into sm_themedevisers parent array index
         * @exm: sm_themedevisers[post_id][columns][column_id][field_name]
         * */
        $this->defaults['sm_themedevisers'] = $_POST['sm_themedevisers'];
            // If this is an autosave, our form has not been submitted,
            //     so we don't want to do anything.
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;

            // Check the user's permissions.
            if ('page' == $_POST['post_type']) {

                if (!current_user_can('edit_page', $post_id))
                    return $post_id;

            } else {

                if (!current_user_can('edit_post', $post_id))
                    return $post_id;
            }

            $data =array();
            /* OK, its safe for us to save the data now. */
            foreach( array_keys($this->defaults) as $field ) {
                $data[$field] = $_POST[$field];
                if ( get_post_meta($post_id, $field, FALSE ) ) {
                    update_post_meta($post_id, $field, $_POST[$field]);

                } else {
                    add_post_meta($post_id, $field, $_POST[$field]);
                }
                if ( $_POST[$field] == '' ) {
                    delete_post_meta($post_id, $field);
                }
            }

        }


    /*
     * Loop for the list of features
     * */

    public function feature_loop( $feature=NULL, $field_number=NULL ){
        $field_number = $field_number-1;
        ?>
        <li class="f_item ui-draggable ui-draggable-handle item_<?php echo $field_number; ?>"  data-feature="<?php echo $field_number; ?>">
            <span class="icon-trash"> </span>
            <span class="data"><?php echo $field_number." : ". $feature ?></span>

            <input type="hidden" value="<?php echo esc_html($feature);?>" name="feature_item[<?php echo $field_number?>]" id="feature_item_<?php echo $field_number; ?>">

        </li>
        <?php
    }
   // }
}

 $metbox = new PMB();