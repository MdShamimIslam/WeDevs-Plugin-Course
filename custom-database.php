<?php
/**
* Plugin Name: Custom Database
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

          global $wpdb;
          $table_name = $wpdb->prefix . 'custom_posts';
          //Delete column(s) of database
          $wpdb->query("ALTER TABLE {$table_name} DROP COLUMN description, DROP COLUMN title");

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
}

new Custom_Database();