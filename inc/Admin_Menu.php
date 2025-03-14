<?php

namespace CD;

class Admin_Menu {
    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    function admin_menu() {
        add_menu_page(
            'Custom Demo',
            'Custom Demo',
            'administrator',
            'custom_crud_operation',
            [ $this, 'admin_settings_callback' ],
        );
    }

    function admin_settings_callback() {
        echo '<div id="root"></div>';
    }

}