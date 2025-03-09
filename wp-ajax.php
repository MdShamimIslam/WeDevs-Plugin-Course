<?php

/**
* Plugin Name: WP Ajax
* Description: A test plugin for WP Ajax.
* Version: 1.0.0
* Author: Md. Shamim Islam
* Author URI: https://shamimv0.netlify.app
* License: GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: test-plugin
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Simple_Auth {

    public function __construct() {
        add_shortcode( 'simple-auth', [ $this, 'render_shortcode' ] );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Login and Profile Update via ajax
        add_action( 'wp_ajax_simple-auth-profile-form', [ $this, 'update_profile' ] );
        add_action( 'wp_ajax_nopriv_simple-auth-login-form', [ $this, 'handle_login' ] );

        // Fetch posts via ajax
        //  add_shortcode( 'simple-fetch-posts', [ $this, 'render_fetch_posts' ] );
        //  add_action( 'wp_ajax_simple-fetch-posts', [ $this, 'fetch_posts' ] );
        //  add_action( 'wp_ajax_nopriv_simple-fetch-posts', [ $this, 'fetch_posts' ] );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'simple-auth-style', plugin_dir_url( __FILE__ ) . 'assets/css/auth.css' );
        wp_enqueue_script( 'simple-auth-js', plugin_dir_url( __FILE__ ) . 'assets/js/auth.js', [ 'jquery', 'wp-util' ] );

        wp_localize_script( 'simple-auth-js', 'simpleAuthAjax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'simple-auth-profile' ),
        ] );
    }

    public function render_shortcode() {
        if ( is_user_logged_in() ) {
            return $this->render_profile_page();
        } else {
            return $this->render_login_page();
        }
    }

    public function update_profile () {

        if ( ! wp_verify_nonce( $_POST[ '_wpnonce' ], 'simple-auth-profile' ) ) {
            return wp_send_json_error( [
                'message' => 'Nonce verification failed'
            ] );
        }

        $display_name = sanitize_text_field( $_POST[ 'display_name' ] );
        $email = sanitize_text_field( $_POST[ 'email' ] );

        // user er ID ke dhore display_name  and email update kora hosse
        wp_update_user( [
            'ID' => get_current_user_id(),
            'display_name' => $display_name,
            'user_email' => $email
        ] );

        // WAY-1
        // echo json_encode( $_POST ); // in php
        // die();

        // WAY-2
        // echo wp_json_encode( $_POST ); // in wp
        // exit;

        // WAY-3
        // wp_send_json( $_POST );

        // WAY-4
        wp_send_json_success( [
            'message' => 'Profile Updated Successfully'
        ] );
    }

    public function render_profile_page() {

        $user = wp_get_current_user();

        ob_start();
        ?>
            <h2>Update Profile</h2>
            <div id='profile-update-message' class='success-message hidden'></div>

            <form method='POST' id='profile-form'>
                <label>
                    Display Name
                    <input type = 'text' name = 'display_name' required value = "<?php echo esc_attr( $user->display_name ); ?>" />
                </label>

                <label>
                    Email
                    <input type = 'email' name = 'email' required value = "<?php echo esc_attr( $user->user_email ); ?>" />
                </label>

                <input type = 'hidden' name = 'action' value = 'simple-auth-profile-form' />

                <button type = 'submit'>Update Profile</button>
            </form>

        <?php

        return ob_get_clean();
    }

    public function handle_login () {
        check_ajax_referer( 'simple-auth-login' );

        $username = sanitize_text_field( $_POST[ 'username' ] );
        $password = sanitize_text_field( $_POST[ 'password' ] );

        $user = wp_signon( [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => true,
        ] );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( [
                'message' => $user->get_error_message(),
            ] );
        }

        wp_send_json_success( [
            'message' => 'Login success, redirecting...',
        ] );

    }

    public function render_login_page() {

        ob_start();

        ?>
            <h2>Login</h2>
            <div id='login-message' class='hidden'></div>

            <form method='POST' id='simple-auth-login-form'>
                <label>
                    password
                    <input type = 'text' name = 'username' required value = '' placeholder = 'Username' />
                </label>

                <label>
                    Password
                    <input type = 'password' name = 'password' required value = '' placeholder = 'Password' />
                </label>

                <input type = 'hidden' name = 'action' value = 'simple-auth-login-form' />

                <?php wp_nonce_field( 'simple-auth-login' );
                ?>

                <button type = 'submit'>Login</button>
            </form>

        <?php

        return ob_get_clean();
    }

}

new Simple_Auth();

