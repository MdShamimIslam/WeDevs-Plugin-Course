<?php
namespace CD;

class Ajax {

    public function __construct() {
        add_action( 'wp_ajax_cd_get_list', array( $this, 'get_list' ) );
        add_action( 'wp_ajax_cd_save_values', array( $this, 'save_values' ) );
        add_action( 'wp_ajax_cd_update_values', array( $this, 'update_values' ) );
        add_action( 'wp_ajax_cd_get_item', array( $this, 'get_item' ) );
        add_action( 'wp_ajax_cd_delete_item', array( $this, 'delete_item' ) );
    }

    // get all items
    public function get_list() {
        check_ajax_referer( 'react-nonce-settings', 'nonce' );

        global $wpdb;

        $table_name = CD_CUSTOM_TABLE_NAME;

        $data = $wpdb->get_results(" SELECT * FROM {$table_name}; ");

        wp_send_json_success( $data );
    }

    // add or save item
    public function save_values() {
        check_ajax_referer( 'react-nonce-settings', 'nonce' );

        global $wpdb;

        $title = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
        $description = isset( $_POST['description'] ) ? sanitize_textarea_field( $_POST['description'] ) : '';

        $inserted = $wpdb->insert(
            CD_CUSTOM_TABLE_NAME,
            array(
                'title' => $title,
                'description' => $description,
            ),
            array( '%s', '%s' )
        );

        if ( $inserted ) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    // update item
    public function update_values() {
        check_ajax_referer( 'react-nonce-settings', 'nonce' );

        global $wpdb;

        $title = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
        $description = isset( $_POST['description'] ) ? sanitize_textarea_field( $_POST['description'] ) : '';

        $id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : '';

        if ( ! $id ) {
            wp_send_json_error();
        }

        $inserted = $wpdb->update(
            CD_CUSTOM_TABLE_NAME,
            array(
                'title' => $title,
                'description' => $description,
            ),
            array( 'id'  => $id ),
            array( '%s', '%s' ),
            array( '%d' )
        );

        if ( $inserted ) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

     // get single item
     public function get_item() {
        check_ajax_referer( 'react-nonce-settings', 'nonce' );

        $id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : '';

        if ( ! $id ) {
            wp_send_json_error();
        }

        global $wpdb;

        $table_name = CD_CUSTOM_TABLE_NAME;

        $data = $wpdb->get_row("SELECT * FROM {$table_name} WHERE id = {$id}");

        wp_send_json_success( $data );
    }

    // delete item
    public function delete_item() {
        check_ajax_referer( 'react-nonce-settings', 'nonce' );

        global $wpdb;

        $id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : '';

        if ( ! $id ) {
            wp_send_json_error();
        }

        $inserted = $wpdb->delete(
            CD_CUSTOM_TABLE_NAME,
            array( 'id'  => $id ),
            array( '%d' )
        );

        if ( $inserted ) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
}
