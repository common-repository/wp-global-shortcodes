<?php

/**
 * WP_Global_Shortcodes_Helpers Class
 *
 * This class handles the role creation and assignment of capabilities for those roles.
 *
 * @since 1.0.0
 */

/**
 * The helpers of the plugin.
 *
 * @since 1.0.0
 * @package WPGS
 * @author Rarus <info@rarus.io>
 */
class WP_Global_Shortcodes_Helpers {

    /**
     * The globally defined control translation option
     *
     * @var string
     * @since 1.0.0
     */
    public $translate;

    /**
     * The globally defined manage_errors option
     *
     * @var string
     * @since 1.0.0
     */
    public $manage_errors;

    /**
     * WP_Global_Shortcodes_Helpers constructor.
     */
    function __construct(){

        /**
         * Prebuffer values to make the performance more efficient
         */
        $this->translate = get_option('wpgs_control_translations');
        $this->manage_errors = get_option('wpgs_manage_errors');

        /**
         * This is a hidden option key. We just provide it this way for developers
         * to disable the wpml string translationn function in an performance
         * optimized way
         */
        $this->disable_wpml = get_option('wpgs_disable_wpml_translate_functions');
    }

    /**
     * Translate custom Strings
     *
     * @param $string - The language string
     * @param null $cname - If no custom name is set, return the default one
     * @return string - The translated language string
     */
    public function translate( $string, $cname = null, $prefix = null ){

        //Checkbox for enabling the usage of translateable strings
        if(!empty($this->translate)){
            $enable = true;
        } else {
            $enable = false;
        }

        /**
         * Filter to control the translation and optimize
         * them to a specific output
         */
        $trigger = apply_filters('wpgs/helpers/control_translations', $enable, $string, $cname);
        if(empty($trigger)){
            return $string;
        }

        //If empty, we return the current value as it is
        if(empty($string))
            return $string;

        //Keep our globally available textdomain
        $txtdomain = WPGS_TEXTDOMAIN;

        if(!empty($cname)){
            $context = $cname;
        } else {
            $context = 'default';
        }

        //For outputting a prefix on various translations
        if($prefix == 'default'){
            $front = 'WPGS: ';
        } elseif (!empty($prefix)){
            $front = $prefix;
        } else {
            $front = '';
        }

        // WPML String Translation Logic
        if(function_exists('icl_t') && empty($this->disable_wpml)){
            // icl_t( TEXTDOMAIN, CONTEXT, STRING )
            return icl_t((string) $txtdomain, $context, $string);
        } else {
            return $front . _x($string, $context, (string) $txtdomain);
        }
    }

    /**
     * Writes errors to the wordpress debug log
     *
     * @param $arr - array of debug messages
     * @return bool - true if error can nbe thrown
     */
    public function throw_errors( $arr ){
        if(!is_array($arr) && empty($arr))
            return false;

        /**
         * Backend setting for enabling debugging
         */
        if(!empty($this->manage_errors)){
            $manage_errors = true;
        } else {
            $manage_errors = false;
        }

        /**
         * Decide by yourself if a specific error or errors
         * in general should be thrown to wordpress' error log
         */
        $enable = apply_filters('wpgs/helpers/throw_error', $manage_errors, $arr);

        if($enable){
            foreach($arr as $error){
                error_log($error);
            }
        }

        return true;
    }

    /**
     * Creates a formatted admin notice
     *
     * @param $content - notice content
     * @param string $type - Status of the specified notice
     * @param bool $is_dismissible - If the message should be dismissible
     * @return string - The formatted admin notice
     */
    public function create_admin_notice($content, $type = 'info', $is_dismissible = true){
        if(empty($content))
            return '';

        /**
         * Block an admin notice based onn the specified values
         */
        $throwit = apply_filters('wpgs/helpers/throw_admin_notice', true, $content, $type, $is_dismissible);
        if(!$throwit)
            return '';

        if($is_dismissible !== true){
            $isit = '';
        } else {
            $isit = 'is-dismissible';
        }


        switch($type){
            case 'info':
                $notice = 'notice-info';
                break;
            case 'success':
                $notice = 'notice-success';
                break;
            case 'warning':
                $notice = 'notice-warning';
                break;
            case 'error':
                $notice = 'notice-error';
                break;
            default:
                $notice = 'notice-info';
                break;
        }

        ob_start();
        ?>
        <div class="notice <?php echo $notice; ?> <?php echo $isit; ?>">
            <p><?php echo $this->translate($content, 'create-admin-notice'); ?></p>
        </div>
        <?php
        $res = ob_get_clean();

        return $res;
    }

}
