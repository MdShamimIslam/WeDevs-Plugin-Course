<?php 

/*
* Plugin Name: Query Post
* Description: This is Query Post Plugin
*/

class QUERY_POST{
    private static $instance;

    private function __construct(){

    };

    public static function get_instance(){

        if(self::$instance){
            return self::$instance;
        }

        self::$instance = new self();

        return self::$instance;

    }
}

QUERY_POST::get_instance();
