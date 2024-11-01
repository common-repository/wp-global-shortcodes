<?php

/**
 * Class WP_Global_Shortcodes_Settings
 *
 * This class contains all of our important settings
 *
 * @since 1.0.0
 * @package AWPA
 * @author Rarus LLC <info@rarus.io>
 */
class WP_Global_Shortcodes_Settings{

    /**
     * Our globally used handling capability
     *
     * @var string
     * @since 1.0
     */
    public $admin_cap;

    /**
     * WP_Global_Shortcodes_Settings constructor.
     *
     * We define all of our necessary settings in here
     */
    function __construct(){
        $this->admin_cap = 'manage_options';
        $this->general_settings = array();
        $this->post_type = 'wp_global_shortcodes';
        $this->meta_identifier = 'wpgs_';
    }

    /**
     * Our admin cap handler function
     *
     * This function handles the admin capability throughout
     * the whole plugin.
     *
     * $target - With the target function you can make a more precised filtering
     * by changing it for specific actions.
     *
     * @param string $target - A identifier where the call comes from
     * @return mixed
     */
    public function get_admin_cap($target = 'main'){
        /**
         * Customize the globally uses capability for this plugin
         *
         * This filter is called every time a capability is needed.
         */
        return apply_filters('wpgs/admin/capability', $this->admin_cap, $target);
    }

    /**
     * Return the block post type
     *
     * @return string - block post type
     */
    public function get_post_type(){
        return $this->post_type;
    }

    /**
     * Return the Meta Identifier for all post types
     *
     * @return string - meta identifier
     */
    public function get_meta_ident(){
        return $this->meta_identifier;
    }
}