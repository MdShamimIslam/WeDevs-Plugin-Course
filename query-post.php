<?php

/*
* Plugin Name: Query Post
* Description: This is Query Post Plugin
*/

if (!defined('ABSPATH')) {
    exit;
}


class MSI_Query_Post {
    private static $instance = null;

    private function __construct() {
        $this->define_constant(); 
        $this->load_classes(); 
        // auto create post when the plugin is activated
        register_activation_hook( __FILE__, [ $this, 'my_plugin_activate' ] );

    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function define_constant() {
        define('MSI_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
    }

    private function load_classes() {
        require_once MSI_PLUGIN_PATH . 'includes/Admin_Menu.php';
        require_once MSI_PLUGIN_PATH . 'includes/Custom_Column.php';
        require_once MSI_PLUGIN_PATH . 'includes/Post_Type.php';
        // require_once MSI_PLUGIN_PATH . 'includes/PostType-Taxonomy.php';

        new MSI_Amin_Menu();
        new MSI\Custom_Column();
        new MSI\Post_Type();
        // new MSI\PostType_Taxonomy();
    }

    public function my_plugin_activate() {

        $count_posts = get_posts([
            'post_type' => 'book',
            'fields' => 'ids'
        ]);

        if (count($count_posts) > 0) {
           return;
        }
      
        wp_insert_post( array(
            'post_type' => 'book',
            'post_title' => 'Auto Create Book',
            'post_content' =>'All Knowledge here in a book',
            'post_status' =>'publish'
        ) );
    }
    
}

MSI_Query_Post::get_instance();

