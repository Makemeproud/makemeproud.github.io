<?php
    /**
     * Redux Framework is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 2 of the License, or
     * any later version.
     * Redux Framework is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
     *
     * @package     ReduxFramework
     * @author      Dovy Paukstys
     * @version     3.1.5
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_tlm' ) ) {

        /**
         * Main ReduxFramework_tlm class
         *
         * @since       1.0.0
         */
        class ReduxFramework_tlm extends ReduxFramework {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            function __construct( $field = array(), $value = '', $parent ) {

                $this->parent   = $parent;
                $this->field    = $field;
                $this->value    = $value;

                $this->extension_url = ReduxFramework::$_url . 'inc/extensions/tlm/'; // Extention url

                // Set default args for this field to avoid bad indexes. Change this to anything you use.
                $defaults    = array(
                    'options'          => array(),
                    'stylesheet'       => '',
                    'output'           => true,
                    'enqueue'          => true,
                    'enqueue_frontend' => true
                );
                $this->field = wp_parse_args( $this->field, $defaults );

            }

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function render() {

                // No errors please
                $defaults = array(
                    'full_width' => true,
                    'overflow'   => 'inherit',
                );
                $my_theme = wp_get_theme();
                $this->field = wp_parse_args( $this->field, $defaults );

                $bDoClose = false;

                $id = $this->parent->args['opt_name'] . '-' . $this->field['id'];

                ?>
                    <p class='hostiko-license'><i class='fa fa-exclamation-triangle'></i>You are licensed to use <?php echo esc_html( ucfirst($my_theme->get( 'TextDomain' ) ));?> to create one single end product for yourself or for your client.<a href="https://themeforest.net/licenses/standard" target='_blank'> Read More</a></p>
                    <div class='p_code_section'> 
                    <div id="tlm-faq">
                        <h4 class='find-pcode' style="cursor:pointer;"><i class="fa fa-plus"></i>Tutorial to find your purchase code.</h4>
                        <span class="hide"><iframe width="560" height="315" src="https://www.youtube.com/embed/nzBQf3nnJA8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></span>
                    </div>
                    <div class='enter-pcode'>
                    <i class="fa fa-key"></i><input placeholder= "Enter Your Purchase Code Here.." type="text" name="<?php echo $this->parent->args['opt_name']; ?>[tlm]" id="purchase_code_verification" value="<?php if(isset($this->parent->options['tlm'])){echo $this->parent->options['tlm'];}else{echo '1234567890';} ?>" class="regular-text ">
                    <a href="javascript:void(0);" id="validation_activate" class="validation_activate_buttons" data-verify="0">Register the code</a>
                    <a href="javascript:void(0);" id="validation_deactivate" class="validation_activate_buttons" data-verify="1">Deregister the code</a>
                    <a href="#popup_license" id="popup_license_button">popup license</a>
                    </div>
                    </div>
                     <br>             
                <?php
            }

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function enqueue() {

                wp_enqueue_script(
                    'redux-tlm-remodal',
                    $this->extension_url . 'tlm/remodal.js',
                    array( 'jquery' ),
                    ReduxFramework_extension_tlm::$version,
                    true
                );

                wp_enqueue_script(
                    'redux-tlm',
                    $this->extension_url . 'tlm/field_tlm.js',
                    array( 'jquery' ),
                    ReduxFramework_extension_tlm::$version,
                    true
                );

                wp_enqueue_style(
                    'redux-tlm-remodal-default',
                    $this->extension_url . 'tlm/remodal-default-theme.css',
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-tlm-remodal',
                    $this->extension_url . 'tlm/remodal.css',
                    time(),
                    true
                );

                wp_enqueue_style(
                    'redux-tlm',
                    $this->extension_url . 'tlm/field_tlm.css',
                    time(),
                    true
                );

            }

            /**
             * Output Function.
             * Used to enqueue to the front-end
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function output() {

                if ( $this->field['enqueue_frontend'] ) {

                }

            }

        }
    }
