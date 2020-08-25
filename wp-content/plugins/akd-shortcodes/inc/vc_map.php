<?php





//shortcodes for layout 01

function vcas_addons()
{
	global $my_admin_page;
	$postID       = '';
	$templatename = "default";
	if ( (isset( $_GET['post'] ) && ! empty( $_GET['post'])) || (isset( $_REQUEST['post_id'] ) && ! empty( $_REQUEST['post_id']))) {
		$postID       = isset($_GET['post'])?$_GET['post']:$_REQUEST['post_id'];
		$templatename = basename( get_post_meta( $postID, '_wp_page_template', true ) );
	}

	if ( $templatename == 'hostiko3.php' ) {


		vc_map( array(

			"name" => __( "Testimonial" ),

			"base" => "testimonial_layout03",

			"as_parent" => array( 'only' => 'testimonial_child_layout03' ),

			"content_element" => true,

			"show_settings_on_create" => true,

			"is_container" => true,

			"params" => array(

				array(

					"type" => "textfield",

					"heading" => __( "Name", 'hosting' ),

					"param_name" => "name",

					'value' => __( 'Jhone', 'hosting' ),

					"description" => __( "Enter Name", 'hosting' )

				),

				array(

					"type" => "textfield",

					"heading" => __( "URL", 'hosting' ),

					"param_name" => "url",

					'value' => __( 'abc.com', 'hosting' ),

					"description" => __( "Enter URL", 'hosting' )

				),

				array(

					"type" => "textfield",

					"heading" => __( "Testimonial Content", 'hosting' ),

					"param_name" => "testimonial_content",

					'value' => __( 'ABC', 'hosting' ),

					"description" => __( "Enter Testimonial Content", 'hosting' )

				),

			),

			"js_view" => 'VcColumnView',

			"category" => array( 'Hosting Element', 'Content' )

		) );


		vc_map( array(

			"name" => __( "Testimonial Child" ),

			"base" => "testimonial_child_layout03",

			"content_element" => true,

			"as_child" => array( 'only' => 'testimonial_layout03' ),

			"show_settings_on_create" => true,

			"params" => array(

				array(

					"type" => "textfield",

					"heading" => __( "Name", 'hosting' ),

					"param_name" => "namechild",

					'value' => __( 'Jhone', 'hosting' ),

					"description" => __( "Enter Name", 'hosting' )

				),

				array(

					"type" => "textfield",

					"heading" => __( "URL", 'hosting' ),

					"param_name" => "url_child",

					'value' => __( 'abc.com', 'hosting' ),

					"description" => __( "Enter URL", 'hosting' )

				),

				array(

					"type" => "textfield",

					"heading" => __( "Testimonial Content", 'hosting' ),

					"param_name" => "testimonial_content_child",

					'value' => __( 'ABC', 'hosting' ),

					"description" => __( "Enter Testimonial Content", 'hosting' )

				),

			),

		) );


		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

			class WPBakeryShortCode_testimonial_layout03 extends WPBakeryShortCodesContainer {

			}

		}

		if ( class_exists( 'WPBakeryShortCode' ) ) {

			class WPBakeryShortCode_testimonial_child_layout03 extends WPBakeryShortCode {

			}

		}
	}

    if ( $templatename == 'hostiko9.php' ) {


        vc_map( array(

            "name" => __( "Testimonial" ),

            "base" => "testimonial_layout03",

            "as_parent" => array( 'only' => 'testimonial_child_layout03' ),

            "content_element" => true,

            "show_settings_on_create" => true,

            "is_container" => true,

            "params" => array(

                array(

                    "type" => "textfield",

                    "heading" => __( "Name", 'hosting' ),

                    "param_name" => "name",

                    'value' => __( 'Jhone', 'hosting' ),

                    "description" => __( "Enter Name", 'hosting' )

                ),

                array(

                    "type" => "textfield",

                    "heading" => __( "URL", 'hosting' ),

                    "param_name" => "url",

                    'value' => __( 'abc.com', 'hosting' ),

                    "description" => __( "Enter URL", 'hosting' )

                ),

                array(

                    "type" => "textfield",

                    "heading" => __( "Testimonial Content", 'hosting' ),

                    "param_name" => "testimonial_content",

                    'value' => __( 'ABC', 'hosting' ),

                    "description" => __( "Enter Testimonial Content", 'hosting' )

                ),

            ),

            "js_view" => 'VcColumnView',

            "category" => array( 'Hosting Element', 'Content' )

        ) );


        vc_map( array(

            "name" => __( "Testimonial Child" ),

            "base" => "testimonial_child_layout03",

            "content_element" => true,

            "as_child" => array( 'only' => 'testimonial_layout03' ),

            "show_settings_on_create" => true,

            "params" => array(

                array(

                    "type" => "textfield",

                    "heading" => __( "Name", 'hosting' ),

                    "param_name" => "namechild",

                    'value' => __( 'Jhone', 'hosting' ),

                    "description" => __( "Enter Name", 'hosting' )

                ),

                array(

                    "type" => "textfield",

                    "heading" => __( "URL", 'hosting' ),

                    "param_name" => "url_child",

                    'value' => __( 'abc.com', 'hosting' ),

                    "description" => __( "Enter URL", 'hosting' )

                ),

                array(

                    "type" => "textfield",

                    "heading" => __( "Testimonial Content", 'hosting' ),

                    "param_name" => "testimonial_content_child",

                    'value' => __( 'ABC', 'hosting' ),

                    "description" => __( "Enter Testimonial Content", 'hosting' )

                ),

            ),

        ) );


        if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

            class WPBakeryShortCode_testimonial_layout03 extends WPBakeryShortCodesContainer {

            }

        }

        if ( class_exists( 'WPBakeryShortCode' ) ) {

            class WPBakeryShortCode_testimonial_child_layout03 extends WPBakeryShortCode {

            }

        }
    }

	if ( $templatename == 'hostiko5.php' ) {


		vc_map( array(

			"name" => __( "Testimonial" ),

			"base" => "testimonial_layout05",

			"as_parent" => array( 'only' => 'testimonial_child_layout05' ),

			"content_element" => true,

			"show_settings_on_create" => true,

			"is_container" => true,

			"params" => array(
				array(

					"type" => "attach_image",

					"heading" => __("Testimonial ICon Image", 'hosting'),

					"holder" => "img",

					"class" => "",

					"param_name" => "testimonial_icon",

					"value" => __("", "hosting"),

					"description" => __("Testimonial ICon Image", "hosting")

				),


				array(

					"type" => "textfield",

					"heading" => __( "Custom Class", 'hosting' ),

					"param_name" => "cClass",

					'value' => __( '', 'hosting' ),

					"description" => __( "Enter Custom Class", 'hosting' )

				),
				array(

					"type" => "textfield",

					"heading" => __( "Custom ID", 'hosting' ),

					"param_name" => "cID",

					'value' => __( '', 'hosting' ),

					"description" => __( "Enter Custom ID", 'hosting' )

				),

			),

			"js_view" => 'VcColumnView',

			"category" => array( 'Hosting Element', 'Content' )

		) );


		vc_map( array(

			"name" => __( "Testimonial Child" ),

			"base" => "testimonial_child_layout05",

			"content_element" => true,

			"as_child" => array( 'only' => 'testimonial_layout05' ),

			"show_settings_on_create" => true,

			"params" => array(
				array(

					"type" => "attach_image",

					"heading" => __("Client Image", 'hosting'),

					"holder" => "img",

					"class" => "",

					"param_name" => "image1url",

					"value" => __("", "hosting"),

					"description" => __("Client Image", "hosting")

				),


				array(

					"type" => "textfield",

					"heading" => __( "Name", 'hosting' ),

					"param_name" => "namechild",

					'value' => __( 'Jhone', 'hosting' ),

					"description" => __( "Enter Name", 'hosting' )

				),

				array(

					"type" => "textfield",

					"heading" => __( "URL", 'hosting' ),

					"param_name" => "url_child",

					'value' => __( 'abc.com', 'hosting' ),

					"description" => __( "Enter URL", 'hosting' )

				),

				array(

					"type" => "textarea",

					"heading" => __( "Testimonial Content", 'hosting' ),

					"param_name" => "testimonial_content_child",

					'value' => __( 'ABC', 'hosting' ),

					"description" => __( "Enter Testimonial Content", 'hosting' )

				),

			),

		) );


		if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

			class WPBakeryShortCode_testimonial_layout05 extends WPBakeryShortCodesContainer {

			}

		}

		if ( class_exists( 'WPBakeryShortCode' ) ) {

			class WPBakeryShortCode_testimonial_child_layout05 extends WPBakeryShortCode {

			}

		}
	}

/***domain search***/
	vc_map(array(

		"name" => __("Domain Search", "Hostiko"),

		"base" => "domain_search_layout03",

		"class" => "domain_search_layout03",

		"category" => __("Hosting Element", "Hostiko"),

		"description" => __("Domain Search.", "hostico"),

		"icon" => get_template_directory_uri() . "/assets/images/addons.png",

		"show_settings_on_create" => true,

		"params" => array(

			array(

				"type" => "textfield",

				"heading" => __("Form Action Link", "hostico"),

				"param_name" => "domain_search",

				"value" => __("Link", "hostico"),

				"description" => __("Required Action form Link.", "hostico")

			),
			array(

				"type" => "textfield",

				"heading" => __("Place Holder", "hostico"),

				"param_name" => "place_holder",

				"value" => __("place_holder", "hostico"),

				"description" => __("place_holder.", "hostico")

			),
			array(

				"type" => "textfield",

				"heading" => __("Button Text", "hostico"),

				"param_name" => "button_text",

				"value" => __("Search", "hostico"),

				"description" => __("Button Text", "hostico")

			),


		)

	));

	vc_map(array(

        "name" => __("Plan Pricing Tables"),

        "base" => "plan_pricing_tables",

        "as_parent" => array('only' => 'plan_pricing_table'),

        "content_element" => true,

        "show_settings_on_create" => true,

        "is_container" => true,

        "params" => array(

            array(

                "type" => "textfield",

                "heading" => __("Table Title", 'hosting'),

                "param_name" => "tabletitle",

                'value' => __('Pricing Table', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Price Title", 'hosting'),

                "param_name" => "tableprice",

                'value' => __('Pricing Title', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 1 Title", 'hosting'),

                "param_name" => "opt1name",

                'value' => __('Option 1', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 2 Title", 'hosting'),

                "param_name" => "opt2name",

                'value' => __('Option 2', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 3 Title", 'hosting'),

                "param_name" => "opt3name",

                'value' => __('Option 3', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 4 Title", 'hosting'),

                "param_name" => "opt4name",

                'value' => __('Option 4', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 5 Title", 'hosting'),

                "param_name" => "opt5name",

                'value' => __('Option 5', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

        ),

        "js_view" => 'VcColumnView',

        "category" => array('Hosting Element', 'Content')

    ));


    vc_map(array(

        "name" => __("Plan Values"),

        "base" => "plan_pricing_table",

        "content_element" => true,

        "as_child" => array('only' => 'plan_pricing_tables'),

        "show_settings_on_create" => true,

        "params" => array(

            array(

                "type" => "textfield",

                "heading" => __("Plan Price", 'hosting'),

                "param_name" => "plan_price",

                'value' => __('$89.00', 'hosting'),

                "description" => __("Enter Plan Price", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Plan Order Link", 'hosting'),

                "param_name" => "plan_order_link",

                'value' => __('#', 'hosting'),

                "description" => __("Enter Plan Order Link", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 1 value", 'hosting'),

                "param_name" => "opt1value",

                'value' => __('Option 1', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 2 value", 'hosting'),

                "param_name" => "opt2value",

                'value' => __('Option 2', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 3 value", 'hosting'),

                "param_name" => "opt3value",

                'value' => __('Option 3', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 4 value", 'hosting'),

                "param_name" => "opt4value",

                'value' => __('Option 4', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 5 value", 'hosting'),

                "param_name" => "opt5value",

                'value' => __('Option 5', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

        ),

    ));


    if (class_exists('WPBakeryShortCodesContainer')) {

        class WPBakeryShortCode_plan_pricing_tables extends WPBakeryShortCodesContainer
        {

        }

    }

    if (class_exists('WPBakeryShortCode')) {

        class WPBakeryShortCode_plan_pricing_table extends WPBakeryShortCode
        {

        }

    }


    /***********************************************************************************/
    /***********************************************************************************/
    /****************************TLD's LISTING VC MAP START*****************************/
    /***********************************************************************************/
    /***********************************************************************************/
    vc_map(array(

        "name" => __("Domain TLD's Listing"),

        "base" => "tld_listing",

        "as_parent" => array('only' => 'tld_list'),

        "content_element" => true,

        "show_settings_on_create" => true,

        "is_container" => true,

        "params" => array(

            array(

                "type" => "textfield",

                "heading" => __("TLD's Listing Title", 'hosting'),

                "param_name" => "tldtitle",

                'value' => __('New Domain Available', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 1 Title", 'hosting'),

                "param_name" => "opt1name",

                'value' => __('Table column 1', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 2 Title", 'hosting'),

                "param_name" => "opt2name",

                'value' => __('Table column 2', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 3 Title", 'hosting'),

                "param_name" => "opt3name",

                'value' => __('Table column 3', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 4 Title", 'hosting'),

                "param_name" => "opt4name",

                'value' => __('Table column 4', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 5 Title", 'hosting'),

                "param_name" => "opt5name",

                'value' => __('Table column 5', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

        ),

        "js_view" => 'VcColumnView',

        "category" => array('Hosting Element', 'Content')

    ));

    vc_map(array(

        "name" => __("TLD Value"),

        "base" => "tld_list",

        "content_element" => true,

        "as_child" => array('only' => 'tld_listing'),

        "show_settings_on_create" => true,

        "params" => array(


            array(

                "type" => "textfield",

                "heading" => __("Table column 1 value", 'hosting'),

                "param_name" => "opt1value",

                'value' => __('Table column 1', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 2 value", 'hosting'),

                "param_name" => "opt2value",

                'value' => __('Table column 2', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 3 value", 'hosting'),

                "param_name" => "opt3value",

                'value' => __('Table column 3', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 4 value", 'hosting'),

                "param_name" => "opt4value",

                'value' => __('Table column 4', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Table column 5 value", 'hosting'),

                "param_name" => "opt5value",

                'value' => __('Table column 5', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

        ),

    ));


    if (class_exists('WPBakeryShortCodesContainer')) {

        class WPBakeryShortCode_tld_listing extends WPBakeryShortCodesContainer
        {

        }

    }

    if (class_exists('WPBakeryShortCode')) {

        class WPBakeryShortCode_tld_list extends WPBakeryShortCode
        {

        }

    }


    /***********************************************************************************/
    /***********************************************************************************/
    /***************************TLD's LISTING  VC MAP ENDS******************************/
    /***********************************************************************************/
    /***********************************************************************************/


    vc_map(array(

        "name" => __("Plan Slider"),

        "base" => "plan_slider",

        "as_parent" => array('only' => 'plan_slide'),

        "content_element" => true,

        "show_settings_on_create" => true,

        "is_container" => true,

        "params" => array(

            array(

                "type" => "textfield",

                "heading" => __("VPS Slider Title", 'hosting'),

                "param_name" => "vpsSliderTitle",

                'value' => __('Pricing Slider', 'hosting'),

                "description" => __("Enter VPS Slider Title", 'hosting')

            ),
            array(

                "type" => "dropdown",

                "heading" => __("Slider Type", 'hosting'),

                "param_name" => "slider_type",

                'value' => array(
                    __('Horizontal', 'js_composer') => '',
                    __('Vertical', 'js_composer') => 'vertical',
                ),

                "description" => __("Select Slider Type", 'hosting')

            ),
	        array(

		        "type" => "textfield",

		        "heading" => __("Enter You Currency", 'hosting'),

		        "param_name" => "currency",

		        'value' => __('$', 'hosting'),

		        "description" => __("Enter Your Currency Symbol", 'hosting')

	        ),
	        array(

		        "type" => "textfield",

		        "heading" => __("Order Button Text", 'hosting'),

		        "param_name" => "btn_txt",

		        'value' => __('', 'hosting'),

		        "description" => __("Enter Your Custom Order Button Text", 'hosting')

	        ),
	        array(

		        "type" => "textfield",

		        "heading" => __("Enter Your price period", 'hosting'),

		        "param_name" => "period_text",

		        'value' => __('mo', 'hosting'),

		        "description" => __("Enter Your price period", 'hosting')

	        ),
	        array(

		        "type" => "textfield",

		        "heading" => __("Enter Your (Coupon Code) Custom text", 'hosting'),

		        "param_name" => "coupon_text",

		        'value' => __('Coupon Code', 'hosting'),

		        "description" => __("Enter Your (Coupon Code) Custom text", 'hosting')

	        ),
	        array(

		        "type" => "textfield",

		        "heading" => __("Promo Code Value", 'hosting'),

		        "param_name" => "coupon_code_value",

		        'value' => __('30% OFF', 'hosting'),

		        "description" => __("Enter Promo Code Value", 'hosting')

	        ),

            array(

                "type" => "textfield",

                "heading" => __("Option 1 Title", 'hosting'),

                "param_name" => "opt1name",

                'value' => __('Option 1', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "attach_image",

                "heading" => __("Option 1 Icon", 'hosting'),

                "holder" => "img",

                "class" => "",

                "param_name" => "image1url",

                "value" => __("", "hosting"),

                "description" => __("Option 1 Icon", "hosting")

            ),


            array(

                "type" => "textfield",

                "heading" => __("Option 2 Title", 'hosting'),

                "param_name" => "opt2name",

                'value' => __('Option 2', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "attach_image",

                "heading" => __("Option 2 Icon", 'hosting'),

                "holder" => "img",

                "class" => "",

                "param_name" => "image2url",

                "value" => __("", "hosting"),

                "description" => __("Option 2 Icon", "hosting")

            ),


            array(

                "type" => "textfield",

                "heading" => __("Option 3 Title", 'hosting'),

                "param_name" => "opt3name",

                'value' => __('Option 3', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "attach_image",

                "heading" => __("Option 3 Icon", 'hosting'),

                "holder" => "img",

                "class" => "",

                "param_name" => "image3url",

                "value" => __("", "hosting"),

                "description" => __("Option 3 Icon", "hosting")

            ),


            array(

                "type" => "textfield",

                "heading" => __("Option 4 Title", 'hosting'),

                "param_name" => "opt4name",

                'value' => __('Option 4', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "attach_image",

                "heading" => __("Option 4 Icon", 'hosting'),

                "holder" => "img",

                "class" => "",

                "param_name" => "image4url",

                "value" => __("", "hosting"),

                "description" => __("Option 4 Icon", "hosting")

            ),


            array(

                "type" => "textfield",

                "heading" => __("Promo Code", 'hosting'),

                "param_name" => "coupon_code",

                'value' => __('LVPS30', 'hosting'),

                "description" => __("Enter Promo Code", 'hosting')

            ),



        ),

        "js_view" => 'VcColumnView',

        "category" => array('Hosting Element', 'Content')

    ));


    vc_map(array(

        "name" => __("Slide Values"),

        "base" => "plan_slide",

        "content_element" => true,

        "as_child" => array('only' => 'plan_slider'),

        "show_settings_on_create" => true,

        "params" => array(

            array(

                "type" => "textfield",

                "heading" => __("Plan Name", 'hosting'),

                "param_name" => "plan_name",

                'value' => __('Plan 1', 'hosting'),

                "description" => __("Enter Plan Name", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Plan Price", 'hosting'),

                "param_name" => "plan_price",

                'value' => __('$89.00', 'hosting'),

                "description" => __("Enter Plan Price", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Discount Price", 'hosting'),

                "param_name" => "discount_price",

                'value' => __('$69.00', 'hosting'),

                "description" => __("Enter Discount Price", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Plan Order Link", 'hosting'),

                "param_name" => "plan_order_link",

                'value' => __('#', 'hosting'),

                "description" => __("Enter Plan Order Link", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 1 value", 'hosting'),

                "param_name" => "opt1value",

                'value' => __('Option 1', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 2 value", 'hosting'),

                "param_name" => "opt2value",

                'value' => __('Option 2', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 3 value", 'hosting'),

                "param_name" => "opt3value",

                'value' => __('Option 3', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

            array(

                "type" => "textfield",

                "heading" => __("Option 4 value", 'hosting'),

                "param_name" => "opt4value",

                'value' => __('Option 4', 'hosting'),

                "description" => __("Enter Value", 'hosting')

            ),

        ),

    ));


    if (class_exists('WPBakeryShortCodesContainer')) {

        class WPBakeryShortCode_plan_slider extends WPBakeryShortCodesContainer
        {

        }

    }

    if (class_exists('WPBakeryShortCode')) {

        class WPBakeryShortCode_plan_slide extends WPBakeryShortCode
        {

        }

    }

	/***********************************************************************************/
	/***********************************************************************************/
	/****************************FAQ Layout*****************************/
	/***********************************************************************************/
	/***********************************************************************************/
	vc_map(array(

		"name" => __("FAQ Listing"),

		"base" => "faq_listing",

		"as_parent" => array('only' => 'faq'),

		"content_element" => true,

		"show_settings_on_create" => true,

		"is_container" => true,

		"params" => array(

			array(

				"type" => "textfield",

				"heading" => __("Heading Title", 'hosting'),

				"param_name" => "heading",

				'value' => __('My Account ', 'hosting'),

				"description" => __("Enter your Custom Group Heading", 'hosting')

			),

			array(

				"type" => "dropdown",

				"heading" => __("Select Style", 'hosting'),

				"param_name" => "style",

				'value'       => array(
					'1'   => 'Style 1',
					'2'   => 'Style 2',
					'3' => 'Style 3',
				),
				'std'         => '1', // Your default value

			),

			array(
				"type" => "attach_image",
				"heading" => __("Table column 2 Title", 'hosting'),
				"param_name" => "icon",
				"description" => __("Select Your Icon Image", 'hosting')
			),

			array(

				"type" => "textfield",

				"heading" => __("Select Icon Backgroud Color", 'hosting'),

				"param_name" => "colorpicker",

				'value' => __('#FF0000', 'hosting'),

				"description" => __("Choose custom Icon Backgroud Color", 'hosting')

			),
			array(

				"type" => "textfield",

				"heading" => __("Custom Class", 'hosting'),

				"param_name" => "class",

				'value' => __('', 'hosting'),

				"description" => __("Enter your custom class", 'hosting')

			),
			array(

				"type" => "textfield",

				"heading" => __("Custom ID", 'hosting'),

				"param_name" => "faq_id",

				'value' => __('', 'hosting'),

				"description" => __("Enter your custom ID", 'hosting')

			),

		),

		"js_view" => 'VcColumnView',

		"category" => array('Hosting Element', 'Content')

	));

	vc_map(array(

		"name" => __("FAQ"),

		"base" => "faq",

		"content_element" => true,

		"as_child" => array('only' => 'faq_listing'),

		"show_settings_on_create" => true,

		"params" => array(


			array(

				"type" => "textfield",

				"heading" => __("Add FAQ Heading/Question", 'hosting'),

				"param_name" => "faq_heading",

				'value' => __('How do I read my statement?', 'hosting'),

				"description" => __("Enter Value", 'hosting')

			),

			array(

				"type" => "textarea_html",

				"heading" => __("Add FAQ Answer/Description", 'hosting'),

				"param_name" => "faq_description",

				'value' => __('Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto ', 'hosting'),

				"description" => __("Enter FAQ Answer/Description", 'hosting')

			),
		),

	));


	if (class_exists('WPBakeryShortCodesContainer')) {

		class WPBakeryShortCode_faq_listing extends WPBakeryShortCodesContainer
		{

		}

	}

	if (class_exists('WPBakeryShortCode')) {

		class WPBakeryShortCode_faq extends WPBakeryShortCode
		{

		}

	}


	/***********************************************************************************/
	/***********************************************************************************/
	/*********************************DataCenter START**********************************/
	/***********************************************************************************/
	/***********************************************************************************/
	vc_map(array(

		"name" => __("DataCenters"),

		"base" => "data_centers",

		"as_parent" => array('only' => 'datacenter'),

		"content_element" => true,

		"show_settings_on_create" => true,

		"is_container" => true,

		"params" => array(
			array(
				"type" => "attach_image",
				"heading" => __("Map Image", 'hosting'),
				"param_name" => "icon",
				"description" => __("Select Your Icon Image", 'hosting')
			),
			array(

				"type" => "textfield",

				"heading" => __("Map Width", 'hosting'),

				"param_name" => "icon_width",

				'value' => __('', 'hosting'),

				"description" => __("Enter your custom Map Width", 'hosting')

			),
			array(

				"type" => "textfield",

				"heading" => __("Map Height", 'hosting'),

				"param_name" => "icon_height",

				'value' => __('', 'hosting'),

				"description" => __("Enter your custom Map Height", 'hosting')

			),

			array(

				"type" => "textfield",

				"heading" => __("Custom Class", 'hosting'),

				"param_name" => "class",

				'value' => __('', 'hosting'),

				"description" => __("Enter your custom class", 'hosting')

			),
			array(

				"type" => "textfield",

				"heading" => __("Custom ID", 'hosting'),

				"param_name" => "faq_id",

				'value' => __('', 'hosting'),

				"description" => __("Enter your custom ID", 'hosting')

			),

		),

		"js_view" => 'VcColumnView',

		"category" => array('Hosting Element', 'Content')

	));

	vc_map(array(

		"name" => __("Datacenter"),

		"base" => "datacenter",

		"content_element" => true,

		"as_child" => array('only' => 'data_centers'),

		"show_settings_on_create" => true,

		"params" => array(
			array(
				"type" => "attach_image",
				"heading" => __("Pin Image", 'hosting'),
				"param_name" => "icon6",
				"description" => __("Select Your Icon Image", 'hosting')
			),
			array(
				"type" => "textfield",
				"heading" => __("Location on Map from Left", 'hosting'),
				"param_name" => "dcl_left",
				'value' => __('20px', 'hosting'),
				"description" => __("Enter Value", 'hosting')
			),
			array(
				"type" => "textfield",
				"heading" => __("Location on Map from Top", 'hosting'),
				"param_name" => "dcl_top",
				'value' => __('20px', 'hosting'),
				"description" => __("Enter Value", 'hosting')
			),
			array(
				"type" => "textfield",
				"heading" => __("Location Name on Map", 'hosting'),
				"param_name" => "dcl_name",
				'value' => __('Location Name', 'hosting'),
				"description" => __("Enter Value", 'hosting')
			),
			array(
				"type" => "textarea_html",
				"holder" => "div",
				"class" => "",
				"heading" => __("Add Location Description", 'hosting'),
				"param_name" => "dcontent",
				"description" => __("Enter Location Description", 'hosting')
			),
			array(
				"type" => "textfield",
				"heading" => __("Custom Class", 'hosting'),
				"param_name" => "dcl_class",
				'value' => __('', 'hosting'),
				"description" => __("Enter Value", 'hosting')
			),
			array(
				"type" => "textfield",
				"heading" => __("Custom ID", 'hosting'),
				"param_name" => "dcl_id",
				'value' => __('', 'hosting'),
				"description" => __("Enter Value", 'hosting')
			),
		),
	));
	if (class_exists('WPBakeryShortCodesContainer')) {
		class WPBakeryShortCode_data_centers extends WPBakeryShortCodesContainer
		{

		}
	}
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_datacenter extends WPBakeryShortCode
		{

		}
	}

	/****************************************************************************/
	/****************************************************************************/
	/*****************************Mega Menu Addon********************************/
	/****************************************************************************/
	/****************************************************************************/
    vc_map(array(
        "name" => __("DropDown Mega Menu", "Hostiko"),
        "base" => "custom_mega_menu",
        "as_parent" => array('only' => 'menu_tabs'),
        "class" => "",
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => "Class",
                "param_name" => "settings_class",
                "value" => "",
                "description" => "Extra class or Press Save Changes"
            ),

        ),
        "js_view" => 'VcColumnView',
        "category" => array('by Header Builder', 'Content')

    ));


    vc_map(
        array(

            "name" => __("Menu Tabs"),

            "base" => "menu_tabs",

            "content_element" => true,

            "as_child" => array('only' => 'custom_mega_menu'),

            "show_settings_on_create" => true,

            "params" => array(

                array(

                    "type" => "textfield",

                    "heading" => __("Headings", 'Hostiko'),

                    "param_name" => "plan_heading",

                    'value' => __('Headings', 'Hostiko'),

                    "description" => __("Enter Headings", 'Hostiko')

                ),
                array(

                    "type" => "attach_image",

                    "heading" => __("Icon", 'Hostiko'),

                    "param_name" => "plan_icon",

                    'value' => __('Attach Image', 'Hostiko'),

                    "description" => __("Attaching Image here", 'Hostiko')

                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __('Default Active'),
                    'param_name'  => 'tab_status',
                    'admin_label' => true,
                    'value'       => array(
                        ''   => 'Select Option',
                        'yes'   => 'Yes',
                        'no'   => 'No',
                    ),
                    'std'         => 'no', // Your default value
                ),
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __("Add Description", 'Hostiko'),
                    "param_name" => "content",
                    "description" => __("Enter Description", 'Hostiko')
                ),

                array(

                    "type" => "textfield",

                    "heading" => __("Custom ID", 'Hostiko'),

                    "param_name" => "plan_id",

                    'value' => __('Enter Custom ID', 'Hostiko'),

                    "description" => __("Custom ID here", 'Hostiko')

                ),
                array(

                    "type" => "textfield",

                    "heading" => __("Custom Class", 'Hostiko'),

                    "param_name" => "plan_class",

                    'value' => __('Enter Custom Class', 'Hostiko'),

                    "description" => __("Custom Class here", 'Hostiko')

                ),



            ),

        )

    );


    if (class_exists('WPBakeryShortCodesContainer')) {
        class WPBakeryShortCode_custom_mega_menu extends WPBakeryShortCodesContainer
        {

        }

    }

    if (class_exists('WPBakeryShortCode')) {

        class WPBakeryShortCode_menu_tabs extends WPBakeryShortCode
        {

        }

    }


	/*  $templatename = basename(get_page_template());


  //shortcodes for layout 07


	  if ($templatename == 'hostico-layout7.php') {

		  vc_map(array(

			  "name" => __("Feature Box", "Hostiko"),

			  "base" => "Hostiko",

			  "class" => "special_class",

			  "category" => __("Hostiko Element", "Hostiko"),

			  "description" => __("Description for foo param.", "my-text-domain"),

			  "icon" => get_template_directory_uri() . "/assets/images/addons.png",

			  "show_settings_on_create" => true,

			  "params" => array(

				  array(

					  "type" => "textfield",

					  "holder" => "div",

					  "class" => "inside",

					  "heading" => __("Text", "my-text-domain"),

					  "param_name" => "foo",

					  "value" => __("Default param value", "my-text-domain"),

					  "description" => __("Description for foo param.", "my-text-domain")

				  ),


			  )

		  ));

	  }*/
}

add_action( 'vc_before_init', 'vcas_addons' );