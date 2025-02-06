
<?php

class MSI_Amin_Menu{

   public function __construct(){
    add_action('admin_menu', [$this, 'add_menu_item']);
   }


   public function add_menu_item(){
        add_menu_page(
            'Query Post', //page title
            'Query Post', //menu title
            'administrator', // capabilities (user role)
            'msi_query_post', // slug
            [$this, 'query_post_callback'] // callback function
        );
   }

   public function query_post_callback(){

        $filter_cat = 0;

        if(isset($_GET['filter_cat'])){
            $filter_cat = $_GET['filter_cat'];
        }

        $posts = get_posts(array(
            "post_type" => "post",
            "posts_per_page" => 10,
            "cat" => $filter_cat
        ));

        $terms = get_terms(array(
            "taxonomy" => "category"
        ));

     
    include MSI_PLUGIN_PATH . 'includes\templates\query_post_tem.php';
   
   }


}