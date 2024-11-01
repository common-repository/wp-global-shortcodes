<?php
if ( ! class_exists( 'WP_Global_Shortcodes' ) ) :

    /**
     * Main WP_Global_Shortcodes Class.
     *
     * @since 1.0.0
     * @package WPGS
     * @author Rarus <info@rarus.io>
     */
    final class WP_Global_Shortcodes {

        /**
         * @var WP_Global_Shortcodes
         * @since 1.0.0
         */
        private static $instance;

        /**
         * WPGS settings Object.
         *
         * @var object|WP_Global_Shortcodes_Settings
         * @since 1.0.0
         */
        public $settings;

        /**
         * WPGS helpers Object.
         *
         * @var object|WP_Global_Shortcodes_Helpers
         * @since 1.0.0
         */
        public $helpers;

        /**
         * Throw error on object clone.
         *
         * Cloning instances of the class is forbidden.
         *
         * @since 1.0.0
         * @return void
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'rarus' ), '1.0.0' );
        }

        /**
         * Disable unserializing of the class.
         *
         * @since 1.0.0
         * @return void
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'rarus' ), '1.0.0' );
        }

        /**
         * Main WP_Global_Shortcodes Instance.
         *
         * Insures that only one instance of WP_Global_Shortcodes exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 1.0.0
         * @static
         * @staticvar array $instance
         * @return object|WP_Global_Shortcodes The one true WP_Global_Shortcodes
         */
        public static function instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Global_Shortcodes ) ) {
                self::$instance =                new WP_Global_Shortcodes;
                self::$instance->base_hooks();
                self::$instance->includes();
                self::$instance->settings        = new WP_Global_Shortcodes_Settings();
                self::$instance->helpers         = new WP_Global_Shortcodes_Helpers();

                new WP_Global_Shortcodes_Run();
            }

            return self::$instance;
        }

        public function get_global_shortcode($id){
            return new WP_Global_Shortcodes_Single($id);
        }

        /**
         * Include required files.
         *
         * @access private
         * @since 1.0.0
         * @return void
         */
        private function includes() {
            require_once WPGS_PLUGIN_DIR . 'core/includes/classes/helpers.php';
            require_once WPGS_PLUGIN_DIR . 'core/includes/classes/settings.php';
            require_once WPGS_PLUGIN_DIR . 'core/includes/classes/single.php';

            require_once WPGS_PLUGIN_DIR . 'core/includes/classes/run.php';
        }

        /**
         * Include required files.
         *
         * @access private
         * @since 1.0.0
         * @return void
         */
        private function base_hooks() {
            add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain') );
        }

        /**
         * Loads the plugin language files.
         *
         * @access public
         * @since 1.0.0
         * @return void
         */
        public function load_textdomain() {
            load_plugin_textdomain( WPGS_TEXTDOMAIN, FALSE, dirname( plugin_basename( WPGS_PLUGIN_FILE ) ) . '/language/' );
        }

        /**
         * Loads the plugin language files.
         *
         * @access public
         * @since 1.0.0
         * @return string
         */
        public function get($id) {
            if(is_numeric($id))
                return do_shortcode('[WPGS id="' . $id . '"]');

            return '';
        }

    }

endif; // End if class_exists check.