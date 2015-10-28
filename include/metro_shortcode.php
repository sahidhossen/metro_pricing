<?php
/**
 * Created by PhpStorm.
 * User: ThemeDevisior
 * Date: 9/10/2015
 * Time: 5:45 PM
 */

add_shortcode('metro_pricing', 'metro_shortcode' );

/**
 * @param $atts
 * @param null $content
 * @return string
 */
function metro_shortcode( $atts ){

     ob_start();
        // define attributes and their defaults
    extract(shortcode_atts( array (
        'post_no'=>'',
    ), $atts ));

        // define query parameters based on attributes
    $options = array(
        'p'=>$post_no,
        'post_type' => 'metro_pricing_table',
    );

    //Run the wp_query method
    $tables = new WP_Query( $options );

    //If has the post then run this
    if ( $tables->have_posts() ) { $tables->the_post();

        //Get All Columns IDs

        $metro = get_post_meta(get_the_ID(),'sm_themedevisers', true);
        $design = get_post_meta(get_the_ID(),'design', true);
        $features = get_post_meta(get_the_ID(),'feature_item',true);
        $metro = $metro[get_the_ID()];

       ?>
        <pre>
            <?php var_dump($design); ?>
        </pre>
        <div class="row metro-row">
            <?php

            $columns = explode(',', get_post_meta(get_the_ID(), 'column_ids', true ));
            $column_class = 'col-md-'.ceil(12/count($columns));
            foreach( $columns as $column_key ){
                //Get each columns information into column variable
                $column = $metro['columns'][$column_key];
                $feature_serial = $column['feature_serial_numbers'];
                $tooltip_numbers_list = explode(',',$column['tooltip_numbers']);

                $tooltips = $column['tooltip'];

                ?>
                <div class="<?php echo $column_class; ?> metro-column">
                    <?php
                    $inner_class = array();
                    $inner_class[] = 'metro-pricing-table';
                    $inner_class[] = ('' != $column['feature_image'] ) ? 'has-image' : '';
                    $inner_class = implode(' ', $inner_class);

                    ?>
                    <div class="<?php echo $inner_class ?>">
                        <div class="inner-column">
                            <div class="pricing-header">
                                <div class="pricing-badge"> Popular </div>
                                <h3 class="metro-title text-center"> <?php echo $column['title']; ?></h3>
                                <div class="price-section">
                                    <div class="price-inner price-round">
                                        <span class="currency">$ </span>
                                        <span class="price"><?php echo $column['price']; ?> </span>
                                        <p> per month</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pricing-body">
                                <div class="free"> &nbsp; </div>
                                <?php
                                $i=0;
                                foreach(explode(',',$feature_serial) as $number ){

                                    if(isset($features[$number])){
                                    ?>
                                    <div class="features has-tooltip <?php echo ($i%2==0) ? 'odd' : 'even'; ?>">
                                        <p><a href="#" title="<?php echo $tooltips[$number][$tooltip_numbers_list[$i]]; ?>"><?php echo $features[$number]; ?></a> </p>
                                    </div>
                                <?php $i++; } }?>
                            </div>
                            <div class="pricing-footer">

                            </div>
                             <?php //var_dump($column); ?>
                        </div>
                    </div>

                </div>

            <?php } ?>
        </div>
        <!-- Script Area -->
        <script>

            function metro_tooltip() {

                jQuery(".has-tooltip a").tooltip({
                    show: {
                        effect: "bounce",
                        delay: 250
                    },
                    tooltipClass: "metro-pricing-tooltip <?php echo $design['tooltip_position']; ?>",
                    <?php if($design['tooltip_position']=='tooltip-right'){ ?>
                    position: {my: 'left center', at: 'right+10 center'},
                    <?php }elseif( $design['tooltip_position']=='tooltip-left'){ ?>
                    position: {my: 'right center', at: 'left-10 center'},
                    <?php }elseif( $design['tooltip_position']=='tooltip-top'){ ?>
                    position: {my: 'center bottom', at: 'center top-10'},
                    <?php }elseif( $design['tooltip_position']=='tooltip-bottom'){ ?>
                    position: {my: 'center top', at: 'center bottom+10'},
                    <?php } ?>
                });
            }

            jQuery(document).ready(function() {
                <?php if( $design['tooltip_activity'] =='on' ){  ?>
                        metro_tooltip();
                <?php } ?>
            })

        </script>

        <?php
        $myvariable = ob_get_clean();

        return $myvariable;

    }

     wp_reset_query();

}


function metro_live_style() {
    wp_enqueue_style(
        'custom-style',
        get_template_directory_uri() . '/css/custom_script.css'
    );
    $color = get_theme_mod( 'my-custom-color' ); //E.g. #FF0000
    $custom_css = "
                .mycolor{
                        background: {$color};
                }";
    wp_add_inline_style( 'custom-style', $custom_css );
}
//add_action( 'wp_enqueue_scripts', 'metro_live_style' );
