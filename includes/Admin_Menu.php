<?php

namespace MSI;

class Admin_Menu_And_Save_Data {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
    }

    public function add_admin_menu() {
        add_menu_page(
            'Admin Settings',
            'Admin Settings',
            'manage_options',
            'msi_admin_settings',
            [ $this, 'admin_settings_callback' ],
            'data:image/svg+xml;base64,' . base64_encode( '<svg fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M368.4 18.3L312.7 74.1 437.9 199.3l55.7-55.7c21.9-21.9 21.9-57.3 0-79.2L447.6 18.3c-21.9-21.9-57.3-21.9-79.2 0zM288 94.6l-9.2 2.8L134.7 140.6c-19.9 6-35.7 21.2-42.3 41L3.8 445.8c-3.8 11.3-1 23.9 7.3 32.4L164.7 324.7c-3-6.3-4.7-13.3-4.7-20.7c0-26.5 21.5-48 48-48s48 21.5 48 48s-21.5 48-48 48c-7.4 0-14.4-1.7-20.7-4.7L33.7 500.9c8.6 8.3 21.1 11.2 32.4 7.3l264.3-88.6c19.7-6.6 35-22.4 41-42.3l43.2-144.1 2.7-9.2L288 94.6z"/></svg>' ),
            3
        );

        add_submenu_page(
            'msi_admin_settings',
            'Sub Menu',
            'Sub Menu',
            'manage_options',
            'msi_sub_menu',
            [ $this, 'sub_menu_callback' ]

        );
    }

    public function admin_settings_callback () {
        if (isset($_POST['submit'])) {
            if (! wp_verify_nonce($_POST["msi_nonce"], "my_nonce")) {
                echo "You aren't valid";
                return;
            }

            $name = isset($_POST['msi_name']) ? sanitize_text_field($_POST['msi_name']) : "";
            $email = isset($_POST['msi_email']) ? sanitize_text_field($_POST['msi_email']) : "";
            $options = isset($_POST['msi_options']) ? sanitize_text_field($_POST['msi_options']) : "";

            $form_values = [
                'name' => $name,
                'email' => $email,
                'options' => $options
            ];
            
            update_option('setings-form-values', $form_values);

        }

        $settings_form_data = get_option('setings-form-values', [] );
        $selected_value = isset($settings_form_data['options']) ? $settings_form_data['options'] : "1";

        
        ?>

        <div class = 'wrap'>
            <h1>Admin Settings Page Form</h1>

            <form action="<?php echo esc_url(admin_url()); ?>admin.php?page=msi_admin_settings" method="post">
                <input type='hidden' name='msi_nonce' value='<?php echo wp_create_nonce('my_nonce') ?>' >
                <?php //echo wp_nonce_field("my_nonce", "msi_nonce") ?>

                <table class ='form-table'>
                    <tbody>
                        <tr>
                            <th>
                                <label>Name</label>
                            </th>
                            <td>
                                <input 
                                    name ='msi_name'
                                    type ='text'
                                    value ='<?php echo isset($settings_form_data['name']) ? esc_attr(wp_unslash($settings_form_data['name'])) : "" ?>'
                                    class ='regular-text'
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Email</label>
                            </th>
                            <td>
                                <input 
                                    name ='msi_email'
                                    type ='text'
                                    value ='<?php echo isset($settings_form_data['email']) ? esc_attr($settings_form_data['email']) : "" ?>'
                                    class ='regular-text'
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>Choose One</label>
                            </th>
                            <td>
                                <select name="msi_options">
                                    <option value="1" <?php echo $selected_value === '1' ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?php echo $selected_value === '2' ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?php echo $selected_value === '3' ? 'selected' : '' ?>>3</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit"><input type="submit" name="submit" class="button button-primary" value="Save Now"></p>
            </form>

        </div>

        <?php
    }

    public function sub_menu_callback () {
        ?>

        Sub Menu Page

        <?php
    }

}