<?php

namespace RAP;

class Enqueue {
    function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
    }

    function admin_enqueue_scripts( $screen ) {
        if ( $screen === 'toplevel_page_react_admin_settings' ) {

            $main_asset = require RAP_PLUGIN_PATH . 'assets/build/main.asset.php';

            wp_enqueue_script(
                'react-settings-js',
                RAP_PLUGIN_DIR . 'assets/build/main.js',
                $main_asset[ 'dependencies' ],
                $main_asset[ 'version' ],
                [ 'in_footer' => true ]
            );

            wp_localize_script( 'react-settings-js', 'reactSettings', [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'react-nonce-settings' ),
            ] );
        }
    }

}