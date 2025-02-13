<?php

namespace MSI;

if (!defined('ABSPATH')) {
    exit;
}

class Custom_Column {

    public function __construct() {
        // add column
        add_filter('manage_page_posts_columns', [$this, 'manage_page_posts_columns']);
        // set value in column
        add_action('manage_page_posts_custom_column', [$this, 'manage_page_posts_custom_column'], 10, 2);
        // sortable columns
        add_filter( 'manage_edit-page_sortable_columns', [$this, 'manage_edit_page_sortable_columns'] );
 
    }

    public function manage_page_posts_columns($columns) {
        $new_columns = [];

        foreach ($columns as $key => $column){
            if($key == 'title') {
                $new_columns['id'] = 'ID';
                $new_columns['image'] = 'Image';
            }
            $new_columns[$key] = $column;
        }

        return $new_columns;
    }

    public function manage_page_posts_custom_column($column_name, $post_id) {
        if ($column_name == "id") {
           echo $post_id;
       }
        if ($column_name == "image") {
            $url = get_the_post_thumbnail_url($post_id, "thumbnail");
            if ($url) {
                echo "<img src='{$url}' width='50' height='50' />";
            }
       }
    }

    public function manage_edit_page_sortable_columns( $sortable_columns ) {
        $sortable_columns['id'] = 'id' ;
        return $sortable_columns;
    }



}
