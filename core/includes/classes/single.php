<?php
/**
 * Class WP_Global_Shortcodes_Single
 *
 * The global content.
 *
 * @since 1.0.0
 * @package AWPA
 * @author Rarus LLC <info@rarus.io>
 */
class WP_Global_Shortcodes_Single {

    /**
     * Meta identifier.
     * @var string
     */
    protected $meta_ident = '';

    /**
     * Global Content ID.
     * @var string
     */
    protected $id = 0;

    /**
     * Global Content Data.
     * @var array
     */
    protected $data = array(
        'title' => '',
        'content' => '',
        'author' => '',
        'permalink' => ''
    );


    public function __construct( $id = 0 ){
        $this->id = $id;
        $this->meta_ident = WPGS()->settings->get_meta_ident();
        $this->load_data();
    }

    /**
     * ################################
     * ###
     * ##### --- Get Data Values ---
     * ###
     * ################################
     */

    public function get_id(){
        return $this->id;
    }

    public function get_title(){
        return $this->data['title'];
    }

    public function get_content(){
        return $this->data['content'];
    }

    public function get_edit_link(){
        return $this->data['permalink'];
    }

    public function get_meta($key, $if_false = false){
        return $this->meta_getter($key, $if_false);
    }

    /**
     * ################################
     * ###
     * ##### --- Set Data Values ---
     * ###
     * ################################
     */

    public function set_title($title = ''){
        $args = array(
            'ID'           => $this->id,
            'post_title'   => $title,
        );
        $check = wp_update_post($args);

        if(is_numeric($check) && !empty($check)){
            return true;
        } else {
            return false;
        }
    }

    public function set_content($content = ''){
        $args = array(
            'ID'           => $this->id,
            'post_content'   => $content,
        );
        $check = wp_update_post($args);

        if(is_numeric($check) && !empty($check)){
            return true;
        } else {
            return false;
        }
    }

    public function set_author($author = ''){
        $args = array(
            'ID'           => $this->id,
            'post_author'   => $author,
        );
        $check = wp_update_post($args);

        if(is_numeric($check) && !empty($check)){
            return true;
        } else {
            return false;
        }
    }

    public function set_meta($key, $data = ''){
        return $this->meta_setter($key, $data);
    }

    /**
     * ################################
     * ###
     * ##### --- Handler functions ---
     * ###
     * ################################
     */

    /**
     * Loads Global Content data into the class based vars
     *
     * @return void
     */
    private function load_data(){

        $post = get_post($this->id);

        $this->data['title'] = $post->post_title;
        $this->data['content'] = $post->post_content;
        $this->data['author'] = $post->post_author;
        $this->data['permalink'] = get_edit_post_link($this->id);
    }

    /**
     * Validate the meta properties
     *
     * @param $key - Meta key
     * @param string $if_false - The custom return value if something went wrong ir is not available
     * @return mixed|string
     */
    private function meta_getter($key, $if_false = ''){

        $metakey = $this->get_prop($this->id, $key);
        if(!empty($metakey)){
            return $metakey;
        } else {
            return $if_false;
        }
    }

    /**
     * Set a meta value
     *
     * @param $key - The meta key
     * @param string $val - The meta value
     * @return bool - Wether meta could be set or not
     */
    private function meta_setter($key, $val = ''){

        if(!empty($val) && !empty($key)) {
            $check = $this->set_prop($this->id, $key, $val);
            if($check)
                return true;
        }

        return false;
    }

    /**
     * Get meta value
     *
     * @param $id - element id
     * @param $key - meta key
     * @param bool $single - if is single
     * @return mixed
     */
    private function get_prop($id, $key, $single = true){
        return get_post_meta($id, $this->meta_ident . $key, $single);
    }

    /**
     * Set meta value into the Global Content class and the database
     *
     * @param $id - Global Content id
     * @param $key - Meta key
     * @param $val - The value
     * @return bool|int - standard wp get_post_meta response
     */
    private function set_prop($id, $key, $val){
        $check = update_post_meta($id, $this->meta_ident . $key, $val);
        if(!empty($check)){
            $this->data[$key] = $val;
        }

        return $check;
    }

}