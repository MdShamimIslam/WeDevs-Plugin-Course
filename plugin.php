<?php

/*
* Plugin Name: Admin Menu and Save Data
* Description: Description of Admin Menu and Save Data
*/

if (!defined('ABSPATH')) {
    exit;
}



class Plugin {
    private static $instance = null;

    private function __construct () {
        // define the constant
        $this->define_constant();
        // load classes function
        $this->load_classes(); 
    }

    public static function get_instance(){
        if (self::$instance === null) {

            self::$instance = new self();
            
        }
        
        return self::$instance;
    }

    // define constant
    private function define_constant() {
            define('PLUGIN_PATH', plugin_dir_path(__FILE__));
        
    }

    // load classes function
    private function load_classes() {
        require_once PLUGIN_PATH . 'includes/Admin_Menu.php';

        new MSI\Admin_Menu_And_Save_Data();
    }

}

Plugin::get_instance();