<?php
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('BESTBUG_HB_OPTIONS')) {
    /**
     * BESTBUG_HB_OPTIONS Class
     *
     * @since    1.0
     */
    class BESTBUG_HB_OPTIONS
    {


        /**
         * Constructor
         *
         * @return    void
         * @since    1.0
         */
        function __construct()
        {
            $this->init();
        }

        public function init()
        {

            add_filter('bb_register_options', array($this, 'options'), 10, 1);

            if (is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
            }
            add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        }

        public function adminEnqueueScripts()
        {
            if (isset($_GET['page']) && ($_GET['page'] == BESTBUG_HB_PAGESLUG)) {
                BESTBUG_CORE_OPTIONS::adminEnqueueScripts();
            }
        }

        public function enqueueScripts()
        {

        }

        public function options($options)
        {
            if (empty($options)) {
                $options = array();
            }

            $posttypes = get_post_types(array('public' => true));
            unset($posttypes['attachment']);
            $args = array(
                'posts_per_page' => -1,
                'post_type' => BESTBUG_HB_HEADER_POSTTYPE,
                'orderby' => 'title',
                'post_status' => 'publish',
                'order' => 'ASC',
            );
            $query = new WP_Query($args);
            $headers = array('' => esc_html__('None', 'bestbug'));
            if ($query->post_count > 0) {
                foreach ($query->posts as $key => $post) {
                    $headers[$post->post_name] = $post->post_title;
                }
            }

            $prefix = BESTBUG_HB_PREFIX;
            $options[] = array(
                'type' => 'options_fields',
                'menu' => array(
                    // add_submenu_page || add_menu_page
                    'type' => 'add_submenu_page',
                    'parent_slug' => 'edit.php?post_type=' . BESTBUG_HB_HEADER_POSTTYPE,
                    'page_title' => esc_html('Settings', 'bestbug'),
                    'menu_title' => esc_html('Settings', 'bestbug'),
                    'capability' => 'manage_options',
                    'menu_slug' => BESTBUG_HB_PAGESLUG,
                ),
                'fields' => array(
                    array(
                        'type' => 'toggle',
                        'heading' => esc_html__('Display header', 'bestbug'),
                        'param_name' => $prefix . 'auto_show',
                        'value' => 'no',
                        'description' => esc_html__('Display header automatically', 'bestbug'),
                    ),
                    array(
                        'type' => 'text',
                        'heading' => '',
                        'param_name' => $prefix . 'auto_show_txt',
                        'value' => "You need to add <b> &lt;?php do_action('bbhb_header') ?&gt; </b> <br/> to anywhere you want to display header",
                        'dependency' => array('element' => $prefix . 'auto_show', 'value' => array('no')),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__('Max width', 'bestbug'),
                        'param_name' => $prefix . 'max_width',
                        'value' => '1170px',
                        'description' => esc_html('The max-width of header', 'bestbug'),
                    ),
                    array(
                        'type' => 'toggle',
                        'heading' => esc_html__('Display by Header Settings', 'bestbug'),
                        'param_name' => $prefix . 'display_by_hsettings',
                        'value' => 'no',
                        'description' => esc_html__('You can choose conditions in Header Settings to display header', 'bestbug'),
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => esc_html__('Default Header', 'bestbug'),
                        'value' => $headers,
                        'param_name' => $prefix . 'header',
                        'std' => '',
                        'description' => esc_html__('Choose default header for all pages.', 'bestbug'),
                        'dependency' => array('element' => $prefix . 'display_by_hsettings', 'value' => array('no')),
                    ),
                    array(
                        'type' => 'couple2',
                        'heading' => esc_html__('Use header with Conditions', 'bestbug'),
                        'label' => array(
                            esc_html__('Conditions', 'bestbug'),
                            esc_html__('use:', 'bestbug'),
                        ),
                        'value' => array(),
                        'value2' => $headers,
                        'param_name' => $prefix . 'conditions',
                        'std' => array(),
                        'description' => 'Conditions like <b>is_single()</b> or <b>is_single() && is_page()</b>,<br>you can read about condition tags in Wordpress in <a href="https://codex.wordpress.org/Conditional_Tags" target="_blank">here</a> <br>Your server must allow "eval()" function.',
                        'dependency' => array('element' => $prefix . 'display_by_hsettings', 'value' => array('no')),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => esc_html__('Use metabox?', 'bestbug'),
                        'value' => $posttypes,
                        'param_name' => $prefix . 'use_metabox',
                        'std' => array(
                            'post' => 1,
                            'page' => 1,
                        ),
                        'description' => esc_html__('Choose posttype you want to show metabox to choose header', 'bestbug'),
                        'dependency' => array('element' => $prefix . 'display_by_hsettings', 'value' => array('no')),
                    ),
                ),
            );

            return $options;
        }

    }

    new BESTBUG_HB_OPTIONS();
}

