<?php

/*
* Plugin Name: Query Post
* Description: This is Query Post Plugin
*/

class MSI_Query_Post {
    private static $instance = null;

    private function __construct() {
        $this->define_constant(); 
        $this->load_classes(); 
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

        new MSI_Amin_Menu();
    }




    
}

MSI_Query_Post::get_instance();

