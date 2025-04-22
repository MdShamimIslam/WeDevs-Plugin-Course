<?php
/*
* Plugin Name: Dashboard Widget
* Description: A simple dashboard widget plugin.
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;

}

class Dashboard_Widget {
    public function __construct() {

        add_action( 'wp_dashboard_setup', array( $this, 'wp_dashboard_setup' ) );
        add_action( 'wp_ajax_msi_save_dashboard', array( $this, 'ajax_msi_save_dashboard' ) );

    }

    public function wp_dashboard_setup() {
        global $wp_meta_boxes;

        $id = 'msi-custom-dashboard-widget';

        wp_add_dashboard_widget(
            $id,
            'Custom dashboard widget',
            array( $this, 'custom_dashboard_widget' )
        );

        $default_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

        // Backup our widget.
        $backup = array( $id => $default_dashboard[ $id ] );

        unset( $default_dashboard[ $id ] );

        $sorted_dashboard = array_merge( $backup, $default_dashboard );

        // Re-setup it.
        $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }

    public function custom_dashboard_widget() {
        ?>
            <form action="<?php echo admin_url('/admin-ajax.php'); ?>" method="POST">
                <input type="hidden" name="action" value="msi_save_dashboard">
                <input type="text" name="title" placeholder="Enter title" >
                <button type="submit" class="button">Save</button>
            </form>
        <?php
    }

    public function ajax_msi_save_dashboard() {
        wp_insert_post(array(
            'post_title' => $_POST['title']
        ));

        wp_safe_redirect( admin_url('/edit.php') );
        
        exit;
    }
}

new Dashboard_Widget();

