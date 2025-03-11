<?php

namespace RAP;

class Ajax {
    function __construct() {
        add_action( 'wp_ajax_react_form_submit', [ $this, 'react_form_submit' ] );
        add_action( 'wp_ajax_react_get_form_data', [ $this, 'react_get_form_data' ] );
    }

    function react_form_submit() {
        check_ajax_referer( 'react-nonce-settings', 'nonce' );

        $title = isset( $_POST[ 'title' ] ) ? sanitize_text_field( $_POST[ 'title' ] ) : '' ;
        $select_option = isset( $_POST[ 'select_option' ] ) ? sanitize_text_field( $_POST[ 'select_option' ] ) : '' ;
        $select_radio = isset( $_POST[ 'select_radio' ] ) ? sanitize_text_field( $_POST[ 'select_radio' ] ) : '' ;
        
        $select_checkbox = isset($_POST['select_checkbox']) ? (array) $_POST['select_checkbox'] : [];

        $checkbox = array_map('sanitize_text_field', $select_checkbox);

        $data = [
            'title' => $title,
            'select_option' => $select_option,
            'select_radio' => $select_radio,
            'select_checkbox' => $checkbox
        ];

        update_option('react-settings-values', $data);

        wp_send_json_success([
            'message' => 'Successfully Form Submitted.'
        ]);
    }

    function react_get_form_data(){
        check_ajax_referer( 'react-nonce-settings', 'nonce' );

        $data = get_option('react-settings-values', []);

        wp_send_json_success($data);
    }
}