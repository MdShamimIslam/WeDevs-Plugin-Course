<?php

namespace MSI;

class Book_Reader{

    public function __construct(){
        add_action('init', [$this, 'init']);

        if ( file_exists( MSI_PLUGIN_PATH . 'lib\CMB2\init.php' ) ) {
            require_once MSI_PLUGIN_PATH . 'lib\CMB2\init.php';
        }
        // create CMB2
        add_action( 'cmb2_admin_init', [ $this, 'register_options_metabox'] );
        // show the book content
        add_action( 'the_content', [ $this, 'the_content_of_book'] );
        // show the chapter content
        add_action( 'the_content', [ $this, 'the_content_of_chapter'] );
    
    }

    public function init(){
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
            'has_archive' => true
        ]);

        register_post_type('chapter', [
            'labels' => [
                'name' => __( 'Chapters' ),
                'singular_name' => __( 'Chapter' ),
                'add_new' => __( 'Add New Chapter' ),
                'add_new_item' => __( 'Add New Chapter' ),
                'edit_item' => __( 'Edit Chapter' ),
                'new_item' => __( 'New Chapter' ),
                'view_item' => __( 'View Chapter' ),
                'search_items' => __( 'Search Chapters' ),
                'not_found' => __( 'No Chapters found' ),
                'not_found_in_trash' => __( 'No Chapters found in trash' ),
            ],
            'public' => true,
            'show_in_rest' => true,
            'has_archive' => true
        ]);
    }
  
    public function register_options_metabox() {

        // create metabox
        $book_metabox = new_cmb2_box( array(
            'id'           => 'book_settings_box',
            'title'        => 'Book Settings',
            'object_types' => array( 'chapter' )
        ) );

        // get all posts from book post type
        $books_query = get_posts(array(
            'post_type' => 'book',
            'posts_per_page' => -1,
        ));

        $books_option = array();

        foreach ($books_query as $book) {
            $books_option[$book->ID] = $book->post_title;
        }

        // add field in the metabox
        $book_metabox->add_field( array(
            'id'      => '_book_id',
            'name'    => 'Select Book Type',
            'desc'    => 'Choose the book name',
            'type'    => 'select',
            'options' => $books_option
        ) );
    
    }

    // show the content of book function
    public function the_content_of_book($contents){
        global $post;

        if ($post->post_type != 'book') {
            return $contents;
        }

        $chapters = get_posts([
            'post_type' => 'chapter',
            'posts_per_page' => -1,
            'meta_key' => '_book_id',
            'meta_value' => $post->ID
        ]);

        ob_start();

        ?>

        <ul>
            <?php foreach ( $chapters as $chapter ) : ?>
            <li>
                <a href="<?php the_permalink($chapter->ID); ?>">
                    <?php echo $chapter->post_title;?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <?php

        $contents .= ob_get_clean();

        return $contents;
    }

    // show the content of chapter function
    public function the_content_of_chapter($contents){
        global $post;

        if ($post->post_type != 'chapter') {
            return $contents;
        }

        $book_id = get_post_meta($post->ID, '_book_id', true);
        $book = get_post($book_id);

        $contents .= '<p>Book Name : <a href="'. get_the_permalink($book) .'">'. $book->post_title .'</a></p>';

        return $contents;
    }

}