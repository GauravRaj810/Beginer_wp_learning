<?php
// it is called as plugin header provide Wordpress with info about the plugin name , version , description 
/**
 * Plugin Name: WP Learn Hooks
 * Description : A Simple plugin  to demonstarte how to use hooks in wordpress.
 * Version : 1
 */
//[filter hook] add filter - syntax - add_filter( 'hook_name', 'your_custom_function', [priority], [accepted_args] );

//  add_filter( 'the_content' , 'modify_post_content');  
//  function modify_post_content($content){
//     return '<p>thanks for reading!</p>' . $content;
//  }
 /* example of removing filter hook using remove filter  */
//  remove_filter( 'the_content', 'modify_post_content' );
