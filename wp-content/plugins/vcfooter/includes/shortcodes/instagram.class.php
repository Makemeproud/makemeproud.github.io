<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'BESTBUG_FB_SHORTCODE_INSTAGRAM' ) ) {
	/**
	 * BESTBUG_FB_SHORTCODE_INSTAGRAM Class
	 *
	 * @since	1.0
	 */
	class BESTBUG_FB_SHORTCODE_INSTAGRAM {

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			
			add_shortcode( BESTBUG_FB_SHORTCODE_INSTAGRAM, array( $this, 'shortcode' ) );
			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_add_param' ) ) {
				$this->vc_shortcode();
			}

			if(is_admin()) {
				add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        }

		public function adminEnqueueScripts() {
			// wp_enqueue_style( 'css', BESTBUG_RPPRO_URL . '/assets/admin/css/style.css' );
			// wp_enqueue_script( 'js', BESTBUG_RPPRO_URL . '/assets/admin/js/script.js', array( 'jquery' ), '1.0', true );
		}

		public function enqueueScripts() {
			// wp_enqueue_style( 'css', BESTBUG_RPPRO_URL . '/assets/css/style.css' );
			// wp_enqueue_script( 'js', BESTBUG_RPPRO_URL . '/assets/js/script.js', array( 'jquery' ), '1.0', true );
		}
        
		public function vc_shortcode() {
			$style = esc_html__('Style', 'bestbug');
			vc_map( array(
		        'name'                      => esc_html__( 'Instagram', 'bestbug' ),
				'description'				=> esc_html__( 'Show images of Instagram user', 'bestbug' ),
		        'base'                      => BESTBUG_FB_SHORTCODE_INSTAGRAM,
		        'category'                  => esc_html( sprintf( esc_html__( 'by %s', 'bestbug' ), BBFOOTER_DESIGNER_CATEGORY ) ),
				'icon' 						=> 'icon-' . BESTBUG_FB_SHORTCODE_INSTAGRAM,
		        'allowed_container_element' => 'vc_row',
				'params'                    => array(
					array(
						"type"        => "textfield",
						"class"       => "",
						"heading"     => esc_html__( 'Title', 'bestbug' ),
						"param_name"  => "title",
						"value"       => "",
						'admin_label' => true,
					),
					array(
						"type"        => "textfield",
						"class"       => "",
						"heading"     => esc_html__( 'Username', 'bestbug' ),
						"param_name"  => "username",
						"value"       => "",
						'admin_label' => true,
					),
					array(
						"type"        => "textfield",
						"class"       => "",
						"heading"     => esc_html__( 'Number of items', 'bestbug' ),
						"param_name"  => "number_items",
						"value"       => "",
						'admin_label' => true,
					),
					// array(
				    //     'type'        => 'dropdown',
				    //     'heading'     => esc_html__( 'Items per row', 'bestbug' ),
				    //     'value'       => array(
					// 		'1' => 1,
					// 		'2' => 2,
					// 		'3' => 3,
					// 		'4' => 4,
					// 		'5' => 5,
					// 		'6' => 6,
					// 	),
				    //     'param_name'  => 'number_items_row',
					// 	'admin_label' => true,
					// 	'save_always' => true,
			        // ),
					array(
				        'type'        => 'dropdown',
				        'heading'     => esc_html__( 'Color', 'bestbug' ),
				        'value'       => array(
							esc_html__( 'Dark', 'bestbug' ) => 'dark',
							esc_html__( 'Light', 'bestbug' ) => 'light',
						),
				        'param_name'  => 'color',
						'admin_label' => true,
						'save_always' => true,
			        ),
					array(
						"type"        => "textfield",
						"class"       => "",
						"heading"     => esc_html__( 'Custom Class CSS', 'bestbug' ),
						"param_name"  => "el_class",
					),
					array(
						'type'       => 'bb_responsive',
						'heading'    => esc_html__( 'Heading style', 'bestbug' ),
						'param_name' => 'heading_style',
						'value'      => '',
						'use' => array(
							'padding',
							'margin',
							'border',
							'font',
							'display',
						),
						'selector' => '#class# .bbfb-instagram-title',
						'group' => esc_html__( 'Heading style', 'bestbug' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => esc_html__( 'CSS', 'bestbug' ),
						'param_name' => 'css',
						'group' => esc_html__( 'Design Options', 'bestbug' ),
					),
		        ),
		    ) );
        }
		public function settings($attr = BESTBUG_FB_SHORTCODE_INSTAGRAM) {
			return BESTBUG_FB_SHORTCODE_INSTAGRAM;
		}
		
		public function shortcode( $atts ){

			$atts = shortcode_atts( array(
				'title' => '',
				'username' => '',
				'number_items' => 6,
				'number_items_row' => 3,
				'color' => '',
				'el_class' => '',
				'css' => '',
				'heading_style' => '',
			), $atts );
			
			$class_array = array('bbfb-instagram');
			if(isset($atts[ 'el_class' ]) && !empty($atts[ 'el_class' ])) {
				array_push($class_array, $atts[ 'el_class' ]);
			}
			if(isset($atts[ 'css' ]) && !empty($atts[ 'css' ])) {
				array_push($class_array, BESTBUG_HELPER::vc_shortcode_custom_css_class($atts[ 'css' ]));
			}
			if(isset($atts[ 'heading_style' ]) && !empty($atts[ 'heading_style' ])) {
				array_push($class_array, BESTBUG_HELPER::get_bbcustom_class($atts[ 'heading_style' ]));
			}
			$class_string = apply_filters( 'vc_shortcodes_css_class', implode(' ', $class_array), BESTBUG_FB_SHORTCODE_INSTAGRAM, $atts );

			extract( $atts );

			if ( empty( $username ) ) {
				return;
			}

			$square_media = 'on';
			$offset = 0;

			$media_array = $this->scrape_instagram($username, $number_items);
			//$this->bbfb_get_instagram( $username, $number_items, $square_media, $offset );
			$html = '';

			if ( is_wp_error( $media_array ) ) {
				$html .= '<div class="bbfb-instagram-error">' . $media_array->get_error_message() . '</div>';
			} else {

				vc_icon_element_fonts_enqueue( 'font-awesome' );

				$html .= '<div class="'.esc_attr($class_string).'">';
				if ( !empty( $title ) ) :
					$html .= '<h5 class="bbfb-instagram-title bbfb-instagram-'.esc_attr( $color ).'-title"> '.esc_html($title).' </h5>';
				endif;

				$html .= '<div class="bbfb-instagram-row">';
				foreach ( $media_array as $item ) {
					$html .= '<div class="item">';
					$html .= '<a href="' . esc_url( $item['link'] ) . '" target="_blank">';
					//if ( 'on' == $show_likes_comments ) {
						$html .= '<div class="item-info">';
						$html .= '<span class="likes">' . $this->format_number($item['likes']) . '</span>';
						$html .= '<span class="comments">' . $this->format_number($item['comments']) . '</span>';
						$html .= '</div>';
					//}
					$html .= '<img src="' . esc_url( $item['thumbnail'] ) . '" alt="' . esc_html__( 'Instagram', 'tm-trio' ) . '" class="item-image"/>';
					$html .= '</a>';
					$html .= '</div>';
				}
				$html .= '</div>';

				$html .= '</div>';
			}
			$html = apply_filters( 'vc_shortcode_output', $html, $this, $atts );
			return $html;
		}
		
		function scrape_instagram( $username, $number_items ) {

			$username = trim( strtolower( $username ) );

			switch ( substr( $username, 0, 1 ) ) {
				case '#':
					$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
					$transient_prefix = 'h';
					break;

				default:
					$url              = 'https://instagram.com/' . str_replace( '@', '', $username );
					$transient_prefix = 'u';
					break;
			}

			if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

				$remote = wp_remote_get( $url );

				if ( is_wp_error( $remote ) ) {
					return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'wp-instagram-widget' ) );
				}

				if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
					return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'wp-instagram-widget' ) );
				}

				$shards      = explode( 'window._sharedData = ', $remote['body'] );
				$insta_json  = explode( ';</script>', $shards[1] );
				$insta_array = json_decode( $insta_json[0], true );

				if ( ! $insta_array ) {
					return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
				}

				if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
					$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
				} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
					$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
				} else {
					return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
				}

				if ( ! is_array( $images ) ) {
					return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
				}

				$instagram = array();

				foreach ( $images as $image ) {
					if ( true === $image['node']['is_video'] ) {
						$type = 'video';
					} else {
						$type = 'image';
					}

					$caption = __( 'Instagram Image', 'wp-instagram-widget' );
					if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
						$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
					}

					$instagram[] = array(
						'description' => $caption,
						'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
						'time'        => $image['node']['taken_at_timestamp'],
						'comments'    => $image['node']['edge_media_to_comment']['count'],
						'likes'       => $image['node']['edge_liked_by']['count'],
						'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
						'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
						'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
						'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
						'type'        => $type,
					);
				} // End foreach().

				// do not set an empty transient - should help catch private or empty accounts.
				if ( ! empty( $instagram ) ) {
					$instagram = base64_encode( serialize( $instagram ) );
					set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
				}
			}

			if ( ! empty( $instagram ) ) {

				$instagram = unserialize( base64_decode( $instagram ) );
				$offset = 0;
				return array_slice( $instagram, $offset, $number_items );;

			} else {

				return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wp-instagram-widget' ) );

			}
		}
	
		function format_number( $number ) {
			if ( $number > 999 && $number <= 999999 ) {
				$result = floor( $number / 1000 ) . ' K';
			} elseif ( $number > 999999 ) {
				$result = floor( $number / 1000000 ) . ' M';
			} else {
				$result = $number;
			}

			return $result;
		}
        
    }
	
	new BESTBUG_FB_SHORTCODE_INSTAGRAM();
}

