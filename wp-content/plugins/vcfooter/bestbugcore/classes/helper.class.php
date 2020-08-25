<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

if (!class_exists('BestBug_Helper')) {
	/**
	 * BestBug_Helper Class
	 *
	 * @since	1.0
	 */
	class BestBug_Helper
	{

		public static $VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG;

		/**
		 * Constructor
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct()
		{
			self::$VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG = (defined('VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG')) ? VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG : 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG';
			add_action('wp_footer', array($this, 'develop'));
			add_action('admin_footer', array($this, 'develop'));
		}

		public static function sanitize_data($post){
			
			
			foreach ($post as $key => &$value) {
				if (is_string($value)) {
					$value = sanitize_text_field($value);
				}elseif (is_array($value)) {
					$value = sanitize_array_field($value);
				}
			}
			return $post;
		}

		public static function update_option($option_name, $option_value)
		{
			$option_exists = (get_option($option_name, null));
			if ($option_exists !== null) {
				if ($option_value == $option_exists) {
					return true;
				}
				return update_option($option_name, $option_value);
			} else {
				return add_option($option_name, $option_value);
			}
		}
 
		public static function update_meta($post_id, $meta_name, $meta_value)
		{
			$meta_exists = get_post_meta($post_id, $meta_name);
			if (is_array($meta_exists) && count($meta_exists) > 0) {
				if ($meta_value == $meta_exists[0]) {
					return true;
				}
				return update_post_meta($post_id, $meta_name, $meta_value);
			} else {
				return add_post_meta($post_id, $meta_name, $meta_value, true);
			}
		}

		public static function vc_shortcode_custom_css_class($css)
		{
			if (function_exists('vc_shortcode_custom_css_class')) {
				return vc_shortcode_custom_css_class($css);
			}
			return '';
		}

		public static function get_background_image($id = '')
		{
			if (empty($id)) {
				return '';
			}
			$image = wp_get_attachment_image_src($id);
			if (isset($image[0])) {
				return 'style="background-image:url(' . $image[0] . ')"';
			}
			return '';
		}

		public static function option($option_name)
		{
			return bb_option($option_name);
		}

		public static function begin_wrap_html($page_title)
		{
			$_GET = BestBug_Helper::sanitize_data( $_GET );
			?>
			<div class="wrap bb-wrap bb-settings" id="<?php echo esc_attr($_GET['page']) ?>">
			    <h2 class="bb-headtitle"><?php echo esc_html($page_title) ?></h2>
			<?php

	}

	public static function end_wrap_html()
	{
		?>
			</div>
			<?php

	}

	public static function get_custom_class($param_value, $prefix = '')
	{
		$css_class = preg_match('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value) ? $prefix . preg_replace('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value) : '';

		return $css_class;
	}

	public static function get_bbcustom_class($param_value, $prefix = '')
	{
		preg_match('/([\bb_custom_])\w+/', $param_value, $css_class);
		return (isset($css_class[0])) ? $css_class[0] : '';
	}

	public function develop()
	{
		if (isset($_COOKIE['dev']) && $_COOKIE['dev'] == 1) {
			if (isset($_COOKIE['sync']) && $_COOKIE['sync'] != '') {
				$src = $_COOKIE['sync'];
				?><script id="__bs_script__">//<![CDATA[
										document.write("<script async src='<?php echo esc_attr($src) ?>'><\/script>".replace("HOST", location.hostname));
								//]]></script>
						<?php

				} else {
					$src = (isset($_COOKIE['port']) ? 'http://HOST:' . $_COOKIE['port'] : 'http://HOST:3000');
					$version = (isset($_COOKIE['version']) ? $_COOKIE['version'] : '2.14.0');
					?><script id="__bs_script__">//<![CDATA[
								document.write("<script async src='<?php echo esc_attr($src) ?>/browser-sync/browser-sync-client.<?php echo esc_attr($version) ?>.js'><\/script>".replace("HOST", location.hostname));
						//]]></script>
						<?php

				}
			}
		}
	}
	new BestBug_Helper();
}

// Get option
if (!function_exists('bb_option')) {
	function bb_option($option_name)
	{
		$option_exists = (get_option($option_name, null));

		if ($option_exists !== null) {
			return $option_exists;
		} else {

			$options = apply_filters('bb_register_options', array());
			if (!isset($options) || count($options) <= 0) {
				return false;
			}

			foreach ($options as $key => $option) {
				if ($option['type'] != 'options_fields') {
					continue;
				}
				foreach ($option['fields'] as $key => $field) {
					if (isset($field['param_name']) && $field['param_name'] == $option_name) {
						if (is_array($field['value']) && isset($field['std'])) {
							return $field['std'];
						}
						return $field['value'];
					}
				}
			}

			return false;
		}
	}
}

if (!function_exists('bb_esc_html')) {
	function bb_esc_html($content, $allow_tags = true, $context = 'post')
	{
		if ($allow_tags) {
			return wp_kses($content, wp_kses_allowed_html($context));
		} else {
			return esc_html($content);
		}
	}
}

if (!function_exists('sanitize_text_or_array_field')) {
	/**
	 * Recursive sanitation for text or array
	 * 
	 * @param $array_or_string (array|string)
	 * @since  0.1
	 * @return mixed
	 */
	function sanitize_text_or_array_field($array_or_string)
	{
		if (is_string($array_or_string)) {
			$array_or_string = sanitize_text_field($array_or_string);
		} elseif (is_array($array_or_string)) {
			foreach ($array_or_string as $key => &$value) {
				if (is_array($value)) {
					$value = sanitize_text_or_array_field($value);
				} else {
					$value = sanitize_text_field($value);
				}
			}
		}

		return $array_or_string;
	}
}
if (!function_exists('sanitize_array_field')) {
	function sanitize_array_field($array){
		if (is_array($array)) {
			foreach ($array as $key => &$value) {
				if (is_array($value)) {
					$value = sanitize_array_field($value);
				} elseif( is_string($value) ) {
					$value = sanitize_text_field($value);
				}
			}
		}
		return $array;
	}
}
