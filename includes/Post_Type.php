<?php

namespace MSI;

class Post_Type{

    public function __construct(){
        // initialize plugin and register_post_type 
        add_action('init', [$this, 'register_post_type']);
        // show content
        add_filter('the_content', [$this, 'the_content']);
        // add fields in edit texonamy
        add_action ('book_category_edit_form_fields', [$this, 'book_category_edit_form_fields']);
        // save fields in edit texonamy
        add_action ('edited_book_category', [$this, 'edited_book_category']);
        // create custom metabox
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes'] );
        // save value in custom metabox 
        add_action( 'save_post_book', [ $this, 'save_post_book'] ); // save_post hook save the value in all posts
    
        // require CMB2
        if ( file_exists( MSI_PLUGIN_PATH . 'lib\CMB2\init.php' ) ) {
            require_once MSI_PLUGIN_PATH . 'lib\CMB2\init.php';
        }
        // create CMB2
        add_action( 'cmb2_admin_init', [ $this, 'register_options_metabox'] );
    
    }

    // create post type and taxonomy
    public function register_post_type(){
        // register_post_type
        register_post_type('book', [
            'labels' => [
                'name' => __( 'Books' ),
                'singular_name' => __( 'Book' ),
                'add_new' => __( 'Add New Book' ),
                'add_new_item' => __( 'Add New Book' ),
                'edit_item' => __( 'Edit Book' ),
                'new_item' => __( 'New Book' ),
                'view_item' => __( 'View Book' ),
                'search_items' => __( 'Search Books' ),
                'not_found' => __( 'No books found' ),
                'not_found_in_trash' => __( 'No books found in trash' ),
            ],
            'public' => true,
            'show_in_rest' => true,
            'has_archive' => true,
            'menu_position' => 3,
            'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" fill="#ccc" width="50px" height="40px" viewBox="0 0 256 512"><path d="M.1 29.3C-1.4 47 11.7 62.4 29.3 63.9l8 .7C70.5 67.3 96 95 96 128.3L96 224l-32 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l32 0 0 95.7c0 33.3-25.5 61-58.7 63.8l-8 .7C11.7 449.6-1.4 465 .1 482.7s16.9 30.7 34.5 29.2l8-.7c34.1-2.8 64.2-18.9 85.4-42.9c21.2 24 51.2 40 85.4 42.9l8 .7c17.6 1.5 33.1-11.6 34.5-29.2s-11.6-33.1-29.2-34.5l-8-.7C185.5 444.7 160 417 160 383.7l0-95.7 32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-32 0 0-95.7c0-33.3 25.5-61 58.7-63.8l8-.7c17.6-1.5 30.7-16.9 29.2-34.5S239-1.4 221.3 .1l-8 .7C179.2 3.6 149.2 19.7 128 43.7c-21.2-24-51.2-40-85.4-42.9l-8-.7C17-1.4 1.6 11.7 .1 29.3z"/></svg>'),
            'supports' => ['title', 'editor', 'thumbnail']
        ]);

        //  Category Taxonomy
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

        //  Tags Taxonomy
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
        ) ) ;

    }

    // show content
    public function the_content($contents) {
        if (!is_singular('book')) {
            return $contents;
        }
    
        $terms = wp_get_post_terms(get_the_ID(), 'book_category');

        $another_title = get_post_meta(get_the_ID(), 'another_title', true);

        ob_start();
        ?>

        <ul>
            <?php foreach($terms as $term): ?>

            <li>
                <a href="<?php echo get_term_link($term, 'book_category'); ?>">
                   <?php echo ($term->name);  ?> 
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <p>Another Title: <?php echo $another_title ?></p>
        
        <?php

        $html  = ob_get_clean();
     
        return $contents . $html;
    }

    // add fields in edit texonamy
    public function book_category_edit_form_fields($term) {
        $extra_meata = get_term_meta($term->term_id, 'extra_meata', true);
    ?>
        <tr class="form-field term-slug-wrap">
            <th scope="row"><label for="slug">Extra meata</label></th>
            <td><input name="extra-meata" id="slug" type="text" value="<?php echo $extra_meata ?>" size="40">
        </tr>
    <?php
       
    }

    // save fields in edit texonamy
    public function edited_book_category($term_id) {
    
        if (isset($_POST['extra-meata'])) {
            update_term_meta($term_id, 'extra_meata', sanitize_text_field($_POST['extra-meata']));
        }
       
    }

    // create custom metabox
    public function add_meta_boxes(){
        add_meta_box(
			'wporg_box_id',          
			'My Custom Meta Box',    
			[$this,'my_custom_box_html'],  
			'book'                   
		);
    }

    // custom metabox callback function
    public function my_custom_box_html ($post){
        $sub_title = get_post_meta( $post->ID, 'book_sub_title', true );

        ?>
            <div>
                <label>Text Input:</label>
                <input type="text" name="book_sub_title" value="<?php echo $sub_title; ?>">
            </div>
        <?php
    }

    // save the value in custom metabox only book post type
    public function save_post_book ( $post_id ) {
       
        if ( isset( $_POST['book_sub_title'] ) ) {
            update_post_meta( $post_id,'book_sub_title', sanitize_text_field( $_POST['book_sub_title'] ) );
        }
       
    }

    // register CMB2 fields
    public function register_options_metabox() {
        $box1 = new_cmb2_box( array(
            'id'           => 'my_custom_box',
            'title'        => 'My MetaBox',
            'object_types' => array( 'book' )
        ) );

        $box1->add_field( array(
            'id'      => 'another_title',
            'name'    => 'Another Title',
            'desc'    => 'Enter Another Title',
            'type'    => 'text',
            
        ) );

        $group_field_id = $box1->add_field( array(
            'id'      => 'another_group_title',
            'description'    => 'Enter Another Group Title',
            'type'    => 'group' 
        ) );
        
        $box1->add_group_field( $group_field_id, array(
            'id'          => 'another_group_field',
            'name'        => 'Another Group Field',
            'description' => 'Enter Another Group Field',
            'type'        => 'text',
            'repeatable' => true
        ) );
       
    
    }


}