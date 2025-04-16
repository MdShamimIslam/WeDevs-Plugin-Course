<?php
/*
* Plugin Name: WP Custom REST API
* Description: A custom REST API endpoint for WordPress.
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;

}

class REST_API_Plugin {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    public function register_routes() {
        // get all invoices
        register_rest_route( 'msi/v1', '/invoices', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_invoices' ),
            'args' => array(
                'per_page' => array(
                    'validate_callback' => function( $param ) {
                        return is_numeric( $param );
                    },

                    'sanitize_callback' => function( $param ) {
                        return intval( $param );
                    },

                    'default' => 2

                ),
                
            ),
        ) );

        // get single invoice
        register_rest_route( 'msi/v1', '/invoices/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array( $this, 'get_single_invoice' ),
            'args' => array(
                'id' => array(
                    'validate_callback' => function( $param ) {
                        return is_numeric( $param );
                    },

                    'sanitize_callback' => function( $param ) {
                        return intval( $param );
                    },
                    'required' => true,

                ),
                
            ),
        ) );

        // create invoice
        register_rest_route( 'msi/v1', '/invoiceS', array(
            'methods' => 'POST',
            'callback' => array( $this, 'create_invoice' ),
            'args' => array(
                'title' => array(
                    'validate_callback' => function( $param ) {
                        return !empty( $param );
                    },
                    'sanitize_callback' => function( $param ) {
                        return sanitize_text_field( $param );
                    },
                    'required' => true,

                ),
                
            ),
            'permission_callback' => function() {
                return current_user_can( 'edit_posts' );
            },
        ) );
       
    }

    public function get_invoices ( $request ) {
        $per_page = $request->get_param( 'per_page' );

        $posts = get_posts( array(
            'post_type' => 'post',
            'posts_per_page' => $per_page,
        ) );

        return new WP_REST_Response( $posts, 200 );
    }

    public function get_single_invoice ( $request ) {
        $post_id = $request->get_param( 'id' );

        $post = get_post( $post_id );

        $response =  new WP_REST_Response( $post, 200 );

        $response->header( 'X-WP-Invoice', $post_id );

        return $response;
    }

    public function create_invoice ( $request ) {
        $post_title = $request->get_param( 'title' );

        wp_insert_post( array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => $post_title,
        ) );

        return new WP_REST_Response( [ 'success'=>true ] );
    }
}

new REST_API_Plugin();

