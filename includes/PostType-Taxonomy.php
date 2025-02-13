<?php

namespace MSI;

if (! define('ABSPATH')) {
    exit; 
}

class PostType_Taxonomy {

    public function __construct(){
        add_action('init', [$this, 'init']);

       }

       public function init(){

        // Category Taxonomy
        register_taxonomy( 'book_category', 'book', array(
            'labels' => array(
                'name' => __( 'Categories' ),
                'singular_name' => __( 'Category' ),
                'search_items' => __( 'Search Categories' ),
                'all_items' => __( 'All Categories' ),
                'parent_item' => __( 'Parent Category' ),
                'add_new_item' => __( 'Add new Category' ),
            ),
            'hierarchical' => true,
            'show_in_rest' => true,
            'rewrite'      => array( 'slug' => 'books-categories' )
        ) ) ;
 
        // Tags Taxonomy
        register_taxonomy( 'book_tags', 'book', array(
            'labels' => array(
                'name' => __( 'Tags' ),
                'singular_name' => __( 'Tag' ),
                'search_items' => __( 'Search Tags' ),
                'all_items' => __( 'All Tags' ),
                'parent_item' => __( 'Parent Tag' ),
                'add_new_item' => __( 'Add new Tag' ),
            ),
            'show_in_rest' => true,
            'hierarchical' => false
        ) ) 

       }

}