<?php

/**
 * Created by PhpStorm.
 * User: ThemeDevisior
 * Date: 8/30/2015
 * Time: 2:49 PM
 */
class Metro_design_bar extends FormElement
{


    public function __construct( $trash=true, $instance=NULL, $component_list=array(), $component=array(), $metro_fields_settings=NULL ){

        if( NULL == $instance) {
           $this->value = array();
       }else{
           $this->value = $instance;
       }

        $this->trash = $trash;

        $this->component_list = $component_list;

        $this->components = $component;

        $this->metro_fields_settings = $metro_fields_settings;

        $this->components();

    }

    public function components(){

       ?>
        <ul class="topbar_list">
            <?php if(!empty($this->component_list)):
                foreach($this->component_list as $component ):
                    switch( $component ){
                        case "feature_image":
                    ?>
                            <li class="metro_component">
                                <a href="#" class="metro_component_icon">
                                    <span class="metro_icon fa fa-camera fa-1x"> </span>
                                    <span class="metro_icon_description"> Icon Description </span>
                                </a>

                                <div class="component_holder">
                                    <div class="metro_component_item">
                                        <p>
                                            <?php echo $this->input(
                                                array(
                                                    'type'=>'image',
                                                    'name'=>$this->metro_fields_settings['name'].'[feature_image]',
                                                    'id'=>$this->metro_fields_settings['id'].'_feature_image',
                                                    'value'=>(isset( $this->value['feature_image'] )) ? $this->value['feature_image'] : '',
                                                )
                                            ) ?>
                                        </p>
                                    </div>
                                </div>
                            </li>

                    <?php
                        break;
                        case 'background':
                            ?>
                            <li class="metro_component">
                                <a href="#" class="metro_component_icon">
                                    <span class="metro_icon fa fa-image fa-1x"> </span>
                                    <span class="metro_icon_description"> Icon Description </span>
                                </a>
                                <div class="component_holder">
                                    <div class="metro_component_item">
                                        <p>
                                            <label for="<?php echo $this->metro_fields_settings['id'].'_background_image'; ?>"> <?php  _e( 'Background Image' , 'metro' ); ?></label>
                                        </p>
                                            <?php echo $this->input(
                                                array(
                                                    'type'=>'image',
                                                    'name'=>$this->metro_fields_settings['name'].'[background_image]',
                                                    'id'=>$this->metro_fields_settings['id'].'_background_image',
                                                    'value'=>(isset( $this->value['background_image'] )) ? $this->value['background_image'] : '',
                                                )
                                            ) ?>

                                        <p>
                                            <label for="<?php echo $this->metro_fields_settings['id'].'_background_color'; ?>"> <?php  _e( 'Background Color' , 'metro' ); ?> </label>
                                        </p>
                                            <?php echo $this->input(
                                                array(
                                                    'type'=>'color',
                                                    'name'=>$this->metro_fields_settings['name'].'[background_color]',
                                                    'id'=>$this->metro_fields_settings['id'].'_background_color',
                                                    'value'=>(isset( $this->value['background_color'] )) ? $this->value['background_color'] : '',
                                                )
                                            ) ?>

                                    </div>
                                </div>
                            </li>

                            <?php
                        break;
                        case 'price_settings':
                            ?>
                            <li class="metro_component">
                                <a href="#" class="metro_component_icon">
                                    <span class="metro_icon fa fa-dollar fa-1x"> </span>
                                    <span class="metro_icon_description"> Icon Description </span>
                                </a>
                                <div class="component_holder">
                                    <div class="metro_component_item">
                                        <p>
                                   <?php
                                            echo $this->input( array(
                                                    'type'=>'checkbox',
                                                    'label'=>'Price Radius',
                                                    'name'=>$this->metro_fields_settings['name'].'[price-radius]',
                                                    'id'=>$this->metro_fields_settings['id'].'_price-radius',
                                                    'value'=>(isset($this->value['price-radius'])) ? $this->value['price-radius'] : NULL,
                                                )
                                            )
                                        ?>
                                        </p>

                                    </div>
                                </div>
                            </li>

                            <?php
                            break;
                        case 'custom':
                            foreach($this->components as $component ) {
                                ?>
                                <li class="metro_component">
                                    <a href="#" class="metro_component_icon">
                                        <span class="metro_icon <?php echo $component['icon-css']; ?> fa-1x"> </span>
                                        <span class="metro_icon_description"> <?php echo $component['label']; ?> </span>
                                    </a>
                                    <div class="component_holder">
                                        <div class="metro_component_item">
                                            <?php foreach($component['elements'] as $element ){  ?>
                                                <p>
                                                    <?php if( $element['type'] !='checkbox'){ ?>
                                                        <label for="<?php echo $element['id']; ?>"> <?php echo $element['label']; ?></label>
                                                    <?php } ?>
                                                    <?php echo $this->input( $element); ?>
                                                </p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                            break;
                    }
                ?>

                <?php endforeach; ?>
            <?php endif; ?>
            <?php if( $this->trash==true ){  ?>
            <li class="metro_component pull-right">
                <a href="#" class="metro_component_icon">
                    <span class="metro_icon fa fa-trash fa-1x icon-trash" data-guid="<?php echo $this->default_settings->column_id ?>" data-post_id="<?php echo $this->default_settings->post_id; ?>"> </span>
                    <span class="metro_icon_description"> Icon Description </span>
                </a>
            </li>
            <?php } ?>
        </ul>
    <?php
    }

}

