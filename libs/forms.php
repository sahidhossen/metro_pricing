<?php
/**
 * Created by PhpStorm.
 * User: ThemeDevisior
 * Date: 8/10/2015
 * Time: 6:03 PM
 */

class FormElement {

    /*
     * All form element store
     * */
    /**
     * @param array $args
     */
    public  function input(array $args ){

        $defaults = array(
            'type' => 'text',
            'name' => NULL ,
            'id' => NULL ,
            'placeholder' => NULL,
            'data' => NULL,
            'value' => NULL ,
            'class' => NULL,
            'options' => array()
        );

        // Turn $args into their own variables
        $input = (object) wp_parse_args( $args, $defaults );

        // If the value of this element is in fact a collection of inputs, turn it into an object, it's nicer to work with
        if( NULL != $input->value && is_array( $input->value ) ) $input->value = (object) $input->value;

        if( !is_object( $input->value ) ) $input->value = stripslashes( $input->value );

        // Create the input attributes
        $input_props = array();
        $input_props['id'] = ( NULL != $input->id && 'select-icons' != $input->type ) ? 'id="' .  $input->id . '"' : NULL ;
        $input_props['name'] = ( NULL != $input->name ) ? 'name="' .  $input->name . '"' : NULL ;
        $input_props['placeholder'] = ( NULL != $input->placeholder ) ? 'placeholder="' . esc_attr( $input->placeholder ) . '"' : NULL ;
        $input_props['class'] = ( NULL != $input->class ) ? 'class="' .  $input->class . '"' : NULL ;
        $input_props['disabled'] = isset( $input->disabled ) ? 'disabled="disabled"' : NULL ;

        // Switch our input type
        switch( $input->type ) {
            case 'text' : ?>
                <input type="text" <?php echo implode(' ', $input_props); ?>
                       value="<?php echo esc_attr($input->value); ?>"/>
                <?php break;
            /**
             * Color Selector
             */

            case 'color' : ?>
                <input type="text" <?php echo implode ( ' ' , $input_props ); ?> value="<?php echo $input->value; ?>" class="sm-color-selector" />
                <?php break;
            /**
             * Text areas
             */
            case 'textarea' : ?>
                <textarea <?php echo implode ( ' ' , $input_props ); ?> <?php if( isset( $input->rows ) ) echo 'rows="' , $input->rows , '"'; ?>><?php echo esc_textarea( $input->value ); ?></textarea>
                <?php break;
            /**
             * Select boxes
             */
            case 'select' : ?>
                <select size="1" <?php echo implode ( ' ' , $input_props ); ?> <?php if( isset( $input->multiple ) ) echo 'multiple="multiple"'; ?>>
                    <?php if( NULL != $input->placeholder ) { ?>
                        <option value=''><?php echo esc_html( $input->placeholder ); ?></option>
                    <?php } // if NULL != placeholder ?>
                    <?php foreach( $input->options as $value => $label ) { ?>
                        <option value='<?php echo esc_attr( $value ); ?>' <?php if( !is_object( $input->value ) ) selected( $input->value , $value, true ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php } // foreach options ?>
                </select>
                <?php break;
            /**
             * Image Uploader
             */
            case 'image' : ?>
                <section class="metro-image-container <?php if( isset( $input->value ) && NULL != $input->value ) echo 'metro-has-image'; ?>">
                    <div class="metro-image-display metro-image-upload-button">
                        <!-- Image -->
                        <?php if( isset( $input->value ) ) echo wp_get_attachment_image( $input->value , 'medium' ); ?>
                        <!-- Remove button -->
                        <a class="metro-image-remove" href=""> <?php _e( 'Remove' , 'Metro' ); ?></a>
                    </div>

                    <a href="#" class="metro-image-upload-button  metro-button btn-full <?php if( isset( $input->value ) && '' != $input->value ) echo 'metro-has-image'; ?>"
                       data-title="<?php _e( 'Select an Image' , 'metro' ); ?>"
                       data-button_text="<?php _e( 'Use Image' , 'metro' ); ?>">
                        <?php echo ( isset( $input->button_label ) ? $input->button_label : __( 'Choose Image' , 'metro' ) ); ?>
                    </a>

                    <?php echo $this->input(
                        array(
                            'type' => 'hidden',
                            'name' => $input->name,
                            'id' => $input->id,
                            'value' => ( isset( $input->value ) ) ? $input->value : NULL,
                            'data' => ( NULL != $input->data ) ? $input->data : NULL,
                        )
                    ); ?>
                </section>
                <?php break;
            /**
             * Checkboxes - here we look for on/NULL, that's how WP widgets save them
             */
            case 'checkbox' : ?>
                <input type="checkbox" <?php echo implode ( ' ' , $input_props ); ?> <?php checked( $input->value , 'on' ); ?>/>
                <?php if( isset( $input->label ) ) { ?>
                    <label for="<?php echo esc_attr( $input->id ); ?>"><?php echo esc_html( $input->label ); ?></label>
                <?php } // if isset label ?>
                <?php break;
            /**
            /**
             * Default to hidden field
             */
            default : ?>
                <input type="hidden" <?php echo implode ( ' ' , $input_props ); ?> value="<?php echo $input->value; ?>" />
            <?php
        }
    }


    /*
     * Get custom field name for each table
     * */
    function get_custom_field_name($default_settings=NULL, $level1='', $level2='', $field_name='' ){

        if( NULL == $default_settings ) return ;

        $final_field_name = 'sm_'.$default_settings->id_base."[".$default_settings->post_id."]";

        if( '' !=$level1 ) $final_field_name .='['.$level1.']';

        if( '' !=$level2 ) $final_field_name .='['.$level2.']';

        if( '' !=$field_name ) $final_field_name .='['.$field_name.']';

        return $final_field_name;

    }

    /*
    * Get custom field id for each table
    * */
    function get_custom_field_id($default_settings=NULL, $level1='', $level2='', $field_name='' ){

        if( NULL == $default_settings ) return ;

        $final_field_id = 'sm_'.$default_settings->id_base."_".$default_settings->post_id;

        if( '' !=$level1 ) $final_field_id .='_'.$level1.'_';

        if( '' !=$level2 ) $final_field_id .='_'.$level2.'_';

        if( '' !=$field_name ) $final_field_id .='_'.$field_name.'_';

        return $final_field_id;

    }



    /*
     * Design bar goes here
     * */

    /**
     * @param string $icon
     * @param $instance
     * @param $component
     * @return Metro_design_bar
     */
    public function design_bar($trash=true, $instance, $component_list, $component, $default_settings){

        $designbar = new Metro_design_bar($trash, $instance, $component_list, $component, $default_settings);

        return $designbar;

    }
}