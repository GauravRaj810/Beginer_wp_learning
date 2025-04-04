<?php
// it is called as plugin header provide Wordpress with info about the plugin name , version , description 
/**
 * Plugin Name: WP Learn Hooks
 * Description : A Simple plugin  to demonstarte how to use hooks in wordpress.
 * Version : 1
 */

 add_filter( 'the_content' , 'wp_learn_amend_content');

 function wp_learn_amend_content($content){
    return '<p>thanks for reading!</p>' . $content;
 }