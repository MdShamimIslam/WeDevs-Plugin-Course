<?php

/**
* Plugin Name: React Admin Panel
* Description: A test plugin for Admin Panel.
* Version: 1.0.0
* Author: Md. Shamim Islam
* Author URI: https://shamimv0.netlify.app
* License: GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: admin-panel
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class React_Admin_Panel {

    function __construct() {
        $this -> define_constant();
        $this -> loaded_classes();
    }

    private function define_constant(){
        define('RAP_PLUGIN_DIR', plugin_dir_url(__FILE__)) ;
        define('RAP_PLUGIN_PATH', plugin_dir_path(__FILE__)) ;
    }

    private function loaded_classes(){
        require_once RAP_PLUGIN_PATH . 'inc/Admin_Menu.php';
        require_once RAP_PLUGIN_PATH . 'inc/Enqueue.php';
        require_once RAP_PLUGIN_PATH . 'inc/Ajax.php';

        new RAP\Admin_Menu();
        new RAP\Enqueue();
        new RAP\Ajax();
    }
    

}

new React_Admin_Panel();

