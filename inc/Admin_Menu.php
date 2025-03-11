<?php

namespace RAP;

class Admin_Menu {
    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    function admin_menu() {
        add_menu_page(
            'React Settings',
            'React Settings',
            'administrator',
            'react_admin_settings',
            [ $this, 'admin_settings_callback' ],
        );
    }

    function admin_settings_callback() {
        echo '<div id="root"></div>';
    }

}