<?php

/**
 * Class WP_Global_Shortcodes_Run
 *
 * Thats where we bring the plugin to life
 *
 * @since 1.0.0
 * @package WPGS
 * @author Rarus <info@rarus.io>
 */

class WP_Global_Shortcodes_Run{

    /**
     * Includes a setting to display the frontend crawl button
     *
     * @var string
     * @since 1.0
     */
    public $disable_frontend_crawl;

    /**
     * WPGS settings Object.
     *
     * @var string
     * @since 1.5
     */
    public $pagename;

    /**
     * Our WP_Global_Shortcodes_Run constructor.
     */
    function __construct(){
        $this->post_type = WPGS()->settings->get_post_type();
        $this->add_hooks();
    }

    /**
     * Register all of our plugin functionality and make the magic happen
     */
    private function add_hooks(){
        add_action('plugin_action_links_' . WPGS_PLUGIN_BASE, array($this, 'plugin_action_links') );

        /* Include Custom Post Type */
        add_action('init', array($this, 'wpgs_register_post_types'), 15 );

        //Add our global shortcode
        add_shortcode('WPGS', array($this, 'wpgs_display_shortcode'));

        //Add some neat helpers
        add_filter( 'manage_' . $this->post_type . '_posts_columns', array($this, 'wpgs_set_custom_post_type_column_title') );
        add_action( 'manage_' . $this->post_type . '_posts_custom_column' , array($this, 'wpgs_set_custom_post_type_column_content'), 10, 2 );
        add_action( 'admin_notices', array($this, 'wpgs_set_custom_post_notice') );

        //Rarus privacy
        add_action('init', array($this, 'rarus_privacy'));
    }

    /**
     * Plugin action links.
     *
     * Adds action links to the plugin list table
     *
     * Fired by `plugin_action_links` filter.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $links An array of plugin action links.
     *
     * @return array An array of plugin action links.
     */
    public function plugin_action_links( $links ) {

        $links['our_shop'] = sprintf( '<a href="%s" target="_blank" style="font-weight:700;color:#6238bf;">%s</a>', 'https://shop.rarus.io/?utm_source=rarus-plugin-wpgs&utm_medium=plugin-page&utm_campaign=Plugin%20to%20Shop', WPGS()->helpers->translate('Our Shop', 'plugin-action-links') );

        return $links;
    }

    /**
     * Includes all of our local post types
     */
    public function wpgs_register_post_types(){
        $labels = array(
            'name'                => WPGS()->helpers->translate( 'Global Shortcodes', 'admin-backend'),
            'singular_name'       => WPGS()->helpers->translate( 'Global Shortcode', 'admin-backend' ),
            'menu_name'           => WPGS()->helpers->translate('Global Shortcode', 'admin-backend'),
            'parent_item_colon'   => WPGS()->helpers->translate('Parent Global Shortcode', 'admin-backend'),
            'all_items'           => WPGS()->helpers->translate('All Global Shortcodes', 'admin-backend'),
            'view_item'           => WPGS()->helpers->translate('View Global Shortcodes', 'admin-backend'),
            'add_new_item'        => WPGS()->helpers->translate('Add New Global Shortcodes', 'admin-backend'),
            'add_new'             => WPGS()->helpers->translate('Add New', 'admin-backend'),
            'edit_item'           => WPGS()->helpers->translate('Edit Global Shortcode', 'admin-backend'),
            'update_item'         => WPGS()->helpers->translate('Update Global Shortcode', 'admin-backend'),
            'search_items'        => WPGS()->helpers->translate('Search Global Shortcode', 'admin-backend'),
            'not_found'           => WPGS()->helpers->translate('Not found', 'admin-backend'),
            'not_found_in_trash'  => WPGS()->helpers->translate('Not found in Trash', 'admin-backend')
        );

        $args = array(
            'label'               => $this->post_type,
            'description'         => WPGS()->helpers->translate('Create and manage a shortcode once, use it everywhere.', 'admin-backend'),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'author', 'revisions' ),
            'taxonomies'          => array(),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'rewrite'             => array( 'slug' => $this->post_type, 'with_front' => false ),
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'menu_position'       => 10.6,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'page',
            'menu_icon' => 'dashicons-layout',
        );

        $args = apply_filters('wpgs/admin/post_type_args', $args);

        // Registering your Custom Post Type
        register_post_type( $this->post_type, $args );
    }

    /**
     * #############################ä
     * ###
     * #### CORE LOGIC
     * ###
     * #############################ä
     */

    /**
     * The main function for handling the shortcode content of
     * our global shortcodes
     *
     * @param array $atts - we are just parsing the id
     * @param string $content - not used
     * @return string - the shortcode content
     */
    public function wpgs_display_shortcode($atts = array(), $content = ''){
        if(empty($atts['id']) || !is_numeric($atts['id']))
            return '';

        $post = get_post($atts['id']);
        if(!empty($post) && !is_wp_error($post)){
            return $post->post_content;
        }

        return '';
    }

    /**
     * Set the columns of our post type
     *
     * @param $columns
     * @return mixed
     */
    public function wpgs_set_custom_post_type_column_title($columns){
        //Set date to the end
        $date = $columns['date'];
        $author = $columns['author'];
        unset($columns['date']);
        unset($columns['author']);

        $columns['wpgs-code'] = WPGS()->helpers->translate('Code', 'plugin-column-title');
        $columns['wpgs-shortcode'] = WPGS()->helpers->translate('Shortcode', 'plugin-column-title');
        $columns['author'] = $author;
        $columns['date'] = $date;

        return $columns;
    }

    /**
     * Set the content for our custom post type columns
     *
     * @param $column
     * @param $post_id
     */
    public function wpgs_set_custom_post_type_column_content($column, $post_id){

        if($column == 'wpgs-shortcode')
            echo htmlentities('[WPGS id="' . $post_id . '"]');

        if($column == 'wpgs-code')
            echo htmlentities('<?php echo WPGS()->get(' . $post_id . '); ?>');
    }

    /**
     * Display a admin notice on single post types to show
     * the custom shortcode
     */
    public function wpgs_set_custom_post_notice(){
        if(!is_admin())
            return;

        $id = get_the_ID();
        $action = !empty($_GET['action']) ? $_GET['action'] : false;

        if(!get_post_type($id) == $this->post_type || $action != 'edit')
            return;
        $content = WPGS()->helpers->translate('Copy this shortcode and paste it wherever you want: ', 'admin-notice') . htmlentities('[WPGS id="' . $id . '"]');
        echo WPGS()->helpers->create_admin_notice($content, 'success', false);
    }

    /**
     * #############################ä
     * ###
     * #### BACKEND LOGIC
     * ###
     * #############################ä
     */

    public function rarus_privacy(){
        if(!current_user_can(WPGS()->settings->get_admin_cap('wpgs-rarus-privacy')))
            return;

        if(!isset($_GET['rarus_privacy']))
            return;

        $privacy = $_GET['rarus_privacy'];

        if($privacy == 'yes'){
            update_option(RARUS_PRIVACY, 'yes');
        } elseif($privacy == 'reset'){
            delete_option(RARUS_PRIVACY);
        } else {
            update_option(RARUS_PRIVACY, 'no');
        }
    }

}
