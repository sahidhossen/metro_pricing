/**
 * Created by ThemeDevisior on 8/10/2015.
 */
(function( $ ) {

    $.fn.metro_trigger_change = function() {
         //   console.log($(this));
        // Trigger 'change' and 'blur' to reset the customizer
        $changed = $(this).trigger("change").trigger("blur");

        //console.log( $changed );
    };

    jQuery(document).ready(function() {
         get_all_feature_by_table_id();
    })

    metro_set_table_sortable();
    /*
    * wp Color text field
    * */
    $(function() {
        var myOptions = {
            // you can declare a default color here,
            // or in the data-default-color attribute on the input
            defaultColor: false,
            // a callback to fire whenever the color changes to a valid color
            change: function(event, ui){},
            // a callback to fire when the input is emptied or an invalid color
            clear: function() {},
            // hide the color picker controls on load
            hide: true,
            // show a group of common colors beneath the square
            // or, supply an array of colors to customize further
            palettes: true
        };

        // Add Color Picker to all inputs that have 'color-field' class
        $( '.sm-color-selector' ).wpColorPicker();

    });

    /*
    * Refresh the feature list with ajax request
    * */
    jQuery(document).on('change','#themedevisers_feature_list', function(e){

        var featureList = jQuery( this ).val();

        jQuery.post(
            ajaxurl,
            {
                'action': 'customize_feature_lists',
                'data':   featureList
            },
            function(response){
                jQuery( "#themedevisers_sortable_features").html( response );
                get_all_feature_by_table_id();
                metro_set_table_sortable();

            }
        );


    });

    jQuery(document).on('click','.add_pricing_column', function(e){

        //Hi Mom
        $that = $(this);

        $pricingTable = '#themedeviser_pricing_table_'+$that.data('post_id');

        $metroPricingTable = jQuery( $pricingTable );

        $columnInputBox = jQuery('#'+$metroPricingTable.data('id_base')+'_column_ids');

        /*
        * Feature List
        * */
        $all_features = $("#themedevisers_feature_list").val();

        // Serialize input data
        $serialized_inputs = [];

        $.each(
            $metroPricingTable.find( 'li.metro_pricing_table_list' ).last().find( 'textarea, input, select' ),
            function( i, input ){
                $serialized_inputs.push( $(input).serialize() );
            });

        $post_data = {
            action: 'add_metro_pricing_table',
            pricing_action: 'add',
            id_base: $metroPricingTable.data( 'id_base' ),
            post_id: $that.data('post_id'),
            instance: $serialized_inputs.join( '&' ),
            last_guid: ( 0 !== $metroPricingTable.find( 'li.metro_pricing_table_list' ).length ) ? $metroPricingTable.find( 'li.metro_pricing_table_list' ).last().data( 'guid' ) : false,
            all_feature: $all_features,

        };

        jQuery.post(
            ajaxurl,
            $post_data,

            function(response){

                $table = $(response);

                /*
                * Append this table into the table list
                * */
                $metroPricingTable.append($table);


                /*
                * Append tooltips random number for each new table
                * */
                var last_guid = $metroPricingTable.find('li.metro_pricing_table_list').last().data('guid');

                var tooltipBox = $metroPricingTable.find("#themedevisers_tooltip_numbers_"+last_guid);

                var current_table_features = $metroPricingTable.find('#sm_feature_list_'+last_guid);

                $column_tooltips = [];
                current_table_features.find("li").each(function(e){
                    $column_tooltips.push($(this).data('tooltip'));
                })

                tooltipBox.val($column_tooltips.join()).metro_trigger_change();



                // Append column IDs to the columns input
                $column_guids = [];

                $metroPricingTable.find( 'li.metro_pricing_table_list' ).each(function(){
                    $column_guids.push( $(this).data( 'guid' ) );
                });


                /*
                * Push the new element random key into the inputbox
                * trigger it with bluer effect
                * */
                $columnInputBox.val($column_guids.join()).metro_trigger_change();
                /*
                * run the sortable function again
                * */
                metro_set_table_sortable();
                /*
                * run the menus function for this columns
                * */
                popupMenu();
            }
        );
       // console.log($serialized_inputs);

        e.preventDefault();

    });

    jQuery(document).on('click','ul[id^="themedeviser_pricing_table_"] .icon-trash', function(e) {

        var that = jQuery(this);

        var tablelist = jQuery("#themedeviser_pricing_table_"+that.data('post_id'));

        var columnInputBox = jQuery("#themedevisers_column_ids");

        that.closest( '.metro_pricing_table_list' ).remove();

        var column_guids = [];
        tablelist.children(".metro_pricing_table_list").each(function(e){
            column_guids.push(jQuery(this).data('guid'));
        })

        columnInputBox.val(column_guids.join()).metro_trigger_change();

        e.preventDefault();
    })

    /*
    * Set all columns sortable for the pricing table feature and hole of the table too  :)
    * */
    function metro_set_table_sortable(){

        var feature_showcase_all_list =  jQuery( ".feature_item" );
        var fetaure_showcase_default = jQuery("#themedevisers_sortable_features_default li");
        var feature_showcase_list =  jQuery( "#themedevisers_sortable_features li" );

        var accept_header_items = jQuery(".header_item_list");
        //var accept_footer_items = jQuery(".footer_item_list");
        var accept_feature_list = jQuery(".accept_feature");

        /*
        * If feature item is empty then show the message
        * */

        fetaure_showcase_default.draggable({
            connectToSortable: '.header_item_list',
            helper: 'clone',
            revert: 'invalid',

        })
        feature_showcase_all_list.draggable({
            handle: '#feature_handler',
            containment:'#wpcontent'
        });

        feature_showcase_list.draggable({
            connectToSortable: '.accept_feature',
            helper: 'clone',
            revert: 'invalid',
            stop: function(event, ui) {
                var feature_no = ui.helper.context.attributes['1'].nodeValue;


                /*
                * Add empty message if accept_feature has no active item
                * */
                if($(".accept_feature").children('li').length<1){

                    $(this).append('<li class="empty-list"> <span> DRAGE FEATURE ITEM HERE </span></li>')
                }else {
                    jQuery(".accept_feature").find('.empty-list').remove();
                }

            },

        });


        /*
        * Sortable item for table header items
        * */
        accept_header_items.sortable({
            revert: true,
            cursor: "move",
            stop:function(){

            }
        })
        /*
        * Sortable item any of movement of the feature elements
        * */
        accept_feature_list.sortable({
            revert: true,
            cursor: "move",
            stop:function(e,u){
                /*
                * Update serial number each time when it is sortable or update
                * */

                var guid = $(this).data('guid');
                var post_id = $(this).data("post_id");
                var feature_no = u.item.context.attributes['1'].nodeValue;

                var featureIDs = [];
                var tooltipIDs = [];

                if( $(this).find(".item_"+feature_no).length>1){
                    u.item.remove();
                };

                $(this).children('li').each(function() {

                    if( $(this).find(".tooltip").length){
                        //console.log("I Found ")
                    }else {
                        var tooltipNo =  Math.floor((Math.random() * 1000) + 1);
                        $(this).attr('data-tooltip',tooltipNo);
                        $(this).append('<div class="dropbox-holder"> ' +
                            '<div class="themedevisers_dropbox"> ' +
                            '<label for="sm_themedevisers_'+post_id+'_columns__'+guid+'__tooltip__'+$(this).data('feature')+'_'+tooltipNo+'"> Tooltip </label>' +
                            '<input type="text" id="sm_themedevisers_'+post_id+'_columns__'+guid+'__tooltip__'+$(this).data('feature')+'_'+tooltipNo+'" name="sm_themedevisers['+post_id+'][columns]['+guid+'][tooltip]['+$(this).data('feature')+']['+tooltipNo+']" class="tooltip" value="Tooltip">' +
                            '</div>' +
                            '<a href="#" title="Tooltip" class="add-tooltip button button-primary button-small"> Tooltip </a>'+
                            '</div>');
                    }
                    featureIDs.push( $(this).data('feature'));
                    tooltipIDs.push($(this).data('tooltip'));
                })

                jQuery("#themedevisers_feature_serial_numbers_"+$(this).parents('li').data('guid')).val(featureIDs.join()).metro_trigger_change();
                jQuery("#themedevisers_tooltip_numbers_"+$(this).parents('li').data('guid')).val(tooltipIDs.join()).metro_trigger_change();

                /*
                * Check each time table feature list and
                * append an empty element when their have not item.
                * */
                if($(this).children('li').length<1){
                    $(this).append('<li class="empty-list"> <span> DRAGE FEATURE ITEM HERE </span></li>')
                }
            },
            receive: function(e,u){

                //var tooltipNo =  Math.floor((Math.random() * 1000) + 1);
                //console.log( "revice" );

            },

        });

        /*
        * Allow feature element draggable
        * */
        jQuery('.accept_feature').draggable({
            containment:'.table-holder',
            revert: true,

        });

        /*
        * Allow feature item droppable for deleting item from
        * table feature item to feature holder
        * */
        jQuery(".feature_item").droppable({
            accept: '.table_items li',
            drop: function( event, ui ) {
                ui.draggable.remove();
            },
        })




    }

    /*
     * Allow sortable tables for table assceding and descending
     * */
    $(".table_lists").sortable({
        cursor: "move",
        stop:function(e,u){
            //Mom
            var inputBox = jQuery("#themedevisers_column_ids");
            var that = jQuery(this);
           var randomID = [];
            that.children('li').each(function(e) {
                randomID.push(jQuery(this).data('guid'));
            })
            inputBox.val(randomID.join()).metro_trigger_change();
        }
    });

    /*
    * Hide and Show each of the table element...
    * */
    jQuery(document).on('click','.table_lists li h3.hndle, .table_lists li .handlediv', function(e){

      jQuery(this).next('.inside').toggle();

    })


    /*
    * Update each of the element when change the feature box
    * */
    function get_all_feature_by_table_id() {
        //Get the total table numbers


        var tableInputID = jQuery(document).find("#themedevisers_column_ids").val();

        if(typeof tableInputID !== 'undefined'){
            //console.log("sdfsadf");
        tableInputIDs = tableInputID.split(','); //Splite the item into an array

        featureIDs = [];


        jQuery.each(tableInputIDs, function (index, value) {

            /*
             * Grabe all of the table input box feature numbers
             * */
            var featureID = jQuery("#themedevisers_feature_serial_numbers_" + value);
            var tooltipBox = jQuery("#themedevisers_tooltip_numbers_" + value);

            featureIDs = featureID.val().split(',');

            var newFeatureIDs = [];
            var newTooltipIDs = [];
            /*
             * Push tooltips numbers into the text field on page load
             * */
            jQuery("#sm_feature_list_" + value).find('li').each(function (e) {

                newTooltipIDs.push($(this).data('tooltip'));
            })

            console.log(tooltipBox.val());

            if (!tooltipBox.val()) {
                tooltipBox.val(newTooltipIDs.join()).metro_trigger_change();
            }

            //console.log(newTooltipIDs);

            /*
             * Looping each of the table feature for grabing their information
             * */
            jQuery.each(featureIDs, function (i, v) {

                if (jQuery("#themedevisers_sortable_features").find('li').hasClass('item_' + v)) {
                    //Collect serial numbers if matching the item class
                    newFeatureIDs.push(v);

                    var data = jQuery("#themedevisers_sortable_features").find('li.item_' + v).find('span.data').html();
                    jQuery(".accept_feature").find("li.item_" + v).find('span.data').html(data);

                } else {
                    jQuery(".accept_feature").find(".item_" + v).remove();
                }

            })
            /*
             * Push value into input box
             * */
            featureID.val(newFeatureIDs.join()).metro_trigger_change();

        })
    }

    }

    /*
    * This is for settings menu show and hide
    * @if click on body then hide this menu
    * @input: visibility, opacity, left
    * @css, animate
    * */
    popupMenu();

    function popupMenu(){

        var selector = jQuery(".metro_component");

        selector.each( function(event) {
            var active = true;
            jQuery(this).find(".metro_component_icon").click(function(e){

                if( active == true ) {
                    jQuery(this).next().css('visibility','visible').animate({
                        opacity: 1,
                        left: "0",
                    },100);
                    active = false;
                }else {
                    jQuery(this).next().css('visibility','hidden').animate({
                        opacity: 0,
                        left: "-20",
                    }, 100, function () {

                    });
                    active=true;
                }

               jQuery(this).parent("li").nextAll().find(".component_holder").css("visibility",'hidden').animate({
                   opacity: 0,
                   left: "-20",
               });
                jQuery(this).parent("li").prevAll().find(".component_holder").css("visibility",'hidden').animate({
                    opacity: 0,
                    left: "-20",
                });
                active=true;
                e.preventDefault();
            })

        })
    /*
    * Hide the menu after click outsite of the menus
    * */
        jQuery('body').click(function(e){
            active=true;

           if((e.target.offsetParent.className!='component_holder')){//check this offset parent component holder or not
               selector.find(".component_holder").each(function(){
                   if(jQuery(this).css('visibility')=='visible') {
                       jQuery(this).css("visibility","hidden").animate({
                           opacity: 0,
                           left: "-20",
                       })

                   }
               })
           }


        })

    }

    /**
     * 3 - Media Uploaders
     */

    // 3.a - Image Remove Button
    var file_frame;
    $(document).on( 'click' , '.metro-image-container .metro-image-remove' , function(e){
        e.preventDefault();

        // "Hi Mom"
        $that = $(this);

        // Get the container
        $container = $that.closest( '.metro-image-container' );

        $that.siblings('img').remove();
        $container.removeClass( 'metro-has-image' );

        $container.find('input').val('').metro_trigger_change();
        $that.fadeOut();
        return false;
    });

    // 3.b - Image Upload Button
    $(document).on( 'click' , '.metro-image-upload-button' , function(e){
        e.preventDefault();

        // "Hi Mom"
        $that = $(this);

        // Get the container
        $container = $that.closest( '.metro-image-container' );

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.close();
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $that.data( 'title' ),
            button: {
                text: $that.data( 'button_text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            // Remove any old image
            $container.find('img').remove();

            // Fade in Remove button
            $container.find('.metro-image-remove').fadeIn();

            // Set attachment to the larege/medium size if they're defined
            if ( undefined !== attachment.sizes.medium )  {
                $attachment = attachment.sizes.medium;
            } else if( undefined !== attachment.sizes.large ) {
                $attachment = attachment.sizes.large;
            } else {
                $attachment = attachment;
            }

            // Create new image object
            var $image = $('<img />').attr({
                class: 'image-reveal',
                src:  $attachment.url,
                height:  $attachment.height,
                width: $attachment.width
            });

            $container.children('.metro-image-display').eq(0).append( $image );

            // Add 'Has Image' Class
            $container.addClass( 'metro-has-image' );

            // Trigger change event
            $container.find('input').val( attachment.id ).metro_trigger_change();

            return;
        });

        // Finally, open the modal
        file_frame.open();
    });


    /*
    * Show tooltip on btn hover
    *
    * */

    metro_tooltip();

    function metro_tooltip() {

        jQuery(".add-tooltip").tooltip({
            show: {
                effect: "bounce",
                delay: 250
            },
            tooltipClass: "metro-pricing-tooltip",
            position: {my: 'center top', at: 'center bottom+10'},
        });

        show_drop_box('.add-tooltip');

        jQuery(document).on('change', 'input.tooltip', function() {
            $(this).parent().next("a").attr('title', $(this).val());
        })
        /*
         * Hide the menu after click outsite of the menus
         * */

    }


    /*
    * Add button text and link for pricing table
    *
    * */
    change_button_info();
    function change_button_info(){

        show_drop_box(".btn-info");

        jQuery(".btn-info").parents('li.footer_item').find("input").each(function(e){
            var parent = jQuery(this).parents(".dropbox-holder");
            $(this).on('change', function(event){
                if(event.target.className=='button_text') {
                    parent.prev(".data").html($(this).val());
                }
            })
        })
    }

/*
*
* Add header boxes
* */
    change_header_info();

    function change_header_info(){

        show_drop_box(".btn-header");

        jQuery(".btn-header").parents('li.header_item').find("input").each(function(e){
            var parent = jQuery(this).parents(".dropbox-holder");
            $(this).on('change', function(event){
                console.log(event.target.className);
                if(event.target.className=='pricing_title') {
                    parent.prev(".data").html($(this).val());
                }
                if(event.target.className=="price"){
                    parent.prev(".data").find('span.price').html("Price: "+$(this).val()+"-");
                }
                if(event.target.className=="sub-price"){
                    parent.prev(".data").find('.sub-price').html("Sub Price: "+$(this).val()+"-");
                }
                if(event.target.className=="recurrence"){
                    parent.prev(".data").find('.recurrence').html(" ("+$(this).val()+")");
                }
            })
        })

    }


    //Show dropbox for each table elements
    function show_drop_box( className ){

        jQuery(document).on('click', className, function (e) {

            var parent = jQuery(this).parents('li');
            jQuery(this).prev(".themedevisers_dropbox").toggle()
            parent.prevAll().find(".themedevisers_dropbox").hide();
            parent.nextAll().find(".themedevisers_dropbox").hide();

            e.preventDefault();
        });

        jQuery('body').click(function (e) {
            if(e.target.offsetParent.className !='themedevisers_dropbox') { //Check if the class name match the target class name
                jQuery(".themedevisers_dropbox").hide();

            }
        })
    }

    /*
    * jQuery UI Tabs for the setting page
    * */

    settings_tabs();

    function settings_tabs(){

        var tds = jQuery(document).find("#themedevisers_tabs");
        tds.tabs();

    }
})( jQuery );