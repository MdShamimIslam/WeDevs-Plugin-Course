<?php

/*
 * Plugin Name:       First Plugin
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 */

//  singleTon pattern in this function 
class My_First_Plugin{

   private static $instance;

   private function __construct(){
      add_filter( 'the_content', [$this, 'show_the_content'] );
      add_filter( 'body_class', [$this, 'show_body_class'] );
      add_action( 'wp_footer', [$this, 'show_the_footer'] );
   }

   public static function get_instance(){
      if(self::$instance){
         return self::$instance;
      }

      self::$instance = new self();

      return self::$instance;
   }

   public function show_the_content( $content ){ 

      $is_show = apply_filters( 'sham_content' , true );

      if( ! $is_show ){
        return $content;
      };

      $myClass = implode(" ", apply_filters('sham_create_class', array()));

      $content .= '<p class="'. $myClass .'">This is a BD</p>';
      return $content;

   }

   function show_the_footer(){
      do_action( 'sham_footer' );
      echo "This is a footer content";
   }

   function show_body_class( $classes ){
      $classes[] = 'first-css-class';
      return $classes;
   }

}

My_First_Plugin::get_instance();

