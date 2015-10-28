<?php
/**
 * Created by Sahid.
 * User: ThemeDevisior
 * Date: 8/12/2015
 * Time: 6:11 PM
 */

class PricingAjax{

    public  function  _construct(){

    }
    function init(){
        add_action( 'wp_ajax_customize_feature_lists', array( $this, 'modifiy_feature_lists') );
        add_action( 'wp_ajax_nopriv_customize_feature_lists', array( $this, 'modifiy_feature_lists') );

        add_action( 'wp_ajax_add_metro_pricing_table', array( $this, 'pricing_table_actions') );
        add_action( 'wp_ajax_add_metro_pricing_table', array( $this, 'pricing_table_actions') );

    }

   public function modifiy_feature_lists() {

       if( isset( $_POST['action'])){
           $pricing = new PMB();
           $featurs = explode("\n", $_POST['data']);
           $number=1;
           foreach( $featurs as $feature ){
               $pricing->feature_loop( $feature, $number );
               $number++;
           }

       }
        die();
    }

    public function pricing_table_actions(){

        if( 'add' == $_POST[ 'pricing_action'] ) {
            $pricing = new PMB();
            // Get the previous element's column data
            parse_str(
                urldecode( stripslashes( $_POST[ 'instance' ] ) ),
                $data
            );

            $all_features = isset($_POST['all_feature']) ?  array_filter( explode("\n", $_POST['all_feature']) ) : NULL;

            if(count($all_features)>0) {
                for ($i = 0; $i <count($all_features); $i++) $numbers[] = $i;
            }

            //Grab all previouse table data
            if( isset($data['sm_'.$_POST['id_base']]) && isset( $_POST[ 'last_guid' ] )) {
                $instance = $data['sm_' . $_POST['id_base']][$_POST['post_id']]['columns'][$_POST['last_guid']];
            }else {
                $instance = NULL;
            }

            $pricing->metro_table_item( array('id_base'=>$_POST['id_base'], 'post_id'=>$_POST['post_id']), $instance, NULL, $all_features, $numbers );
        }

        die();
    }


}
function pricing_register_widget_ajax(){
    $widget_ajax = new PricingAjax();
    $widget_ajax->init();
}
add_action( 'init' , 'pricing_register_widget_ajax' );
