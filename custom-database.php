<?php
/**
* Plugin Name: Custom Database and CRUD Operation
* Description: Custom Database management System.
*/

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_Database {

    public $version = '1.0.1';

    function __construct() {

        register_activation_hook( __FILE__, [ $this, 'register_activation_hook' ] );
        add_action( 'admin_init', [ $this, 'admin_init' ] );

        // load classes and define constant
        $this -> define_constant();
        $this -> loaded_classes();

    }

    public function register_activation_hook() {
        $this->create_or_update_db();
    }

    function admin_init() {
        if ( !current_user_can( 'update_plugins' ) ) {
            return;
        }
        // option jodi update korte vule jawa hoy tahle version null aste pare.ajnno default vabe null
        $current_version = get_option( 'custom_data', null );

        if ( version_compare( $current_version, $this->version, '<' ) ) {
          $this->create_or_update_db();

          // Delete column(s) of database
          // global $wpdb;
          // $table_name = $wpdb->prefix . 'custom_posts';
          // $wpdb->query("ALTER TABLE {$table_name} DROP COLUMN description, DROP COLUMN title");

        }
    }

    function create_or_update_db() {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_posts';
        $wpdb_collate = $wpdb->collate;

        $sql = " CREATE TABLE {$table_name} (
            id INT(10) unsigned NOT NULL auto_increment,
            title VARCHAR(255),
            description TEXT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
            )
            COLLATE {$wpdb_collate}";

        dbDelta( $sql );

        update_option( 'custom_data', $this->version );

    }

    private function define_constant(){
        define('CD_PLUGIN_DIR', plugin_dir_url(__FILE__)) ;
        define('CD_PLUGIN_PATH', plugin_dir_path(__FILE__)) ;
        define( 'CD_CUSTOM_TABLE_NAME', $GLOBALS['wpdb']->prefix . 'custom_posts' );
    }

    private function loaded_classes(){
        require_once CD_PLUGIN_PATH . 'inc/Admin_Menu.php';
        require_once CD_PLUGIN_PATH . 'inc/Enqueue.php';
        require_once CD_PLUGIN_PATH . 'inc/Ajax.php';

        new CD\Admin_Menu();
        new CD\Enqueue();
        new CD\Ajax();
    }
}

new Custom_Database();