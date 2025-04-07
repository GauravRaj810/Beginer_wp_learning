<?php
/**
 * Plugin Name: Bookstore
 * Description: A plugin to manage books 
 * Version : 1.0
 */


if( ! defined ('ABSPATH')){
    exit; // Exit if accessed directly 
}
/* CREATING CUSTOM POST TYPES  */


//The bookstore_register_book_post_type function is where  defining your custom post type. Here's a breakdown of the arguments you used:
add_action( 'init', 'bookstore_register_book_post_type' ); 
function bookstore_register_book_post_type(){
    $args = array(
        //  Defines the labels for the custom post type, such as "Add New Book", "Edit Book", etc.
        'labels' => array(
            'name' => 'Books',
            'singular_name' => 'Book',
            'menu_name' => 'Books',
            'add_new' => 'Add New Book',
            'add_new_item' => 'Add New Book',
            'new_item' => 'New Book',
            'edit_item' => 'Edit Book',
            'view_item' => 'View Book',
            'all_item' => 'All Books',
        ),
        'public'=>true, // Set to true, this makes the custom post type publicly accessible.
        'has_archive' => true, //Allows an archive page for this custom post type (e.g., your-site.com/books/).
        'show_in_rest' => true,
        'supports' => array('title' , 'editor' , 'author' , 'thumbnail' , 'excerpt' , 'custom-fields'), //Defines which features are supported for this post type. In your case, it supports the title, editor, author, thumbnail, and excerpt.

        // here above custom fields for adding meta data ...using custom field panel for these custom post type must support this thats why we added  

    );
    register_post_type('book', $args); // In WordPress, register_post_type() is typically called during the init action hook. 
    /* so he have to add init hook for running this or changing this  */
}


/* Creating taxomony - organinzing things */
add_action('init' , 'bookstore_register_genre_taxomony');
function bookstore_register_genre_taxomony(){
    $args = array(
        'labels'   => array(
            'name'   => 'Genres',
            'singular_name' => 'Genre',
            'edit_item' => 'Edit Genre',
            'update_item' =>'Update Genre',
            'add_new_item' =>'New Genre Name',
            'menu_name' => 'Genre',
        ),
        'hierarhchical' => true,
        'rewrite'  => array('slug' => 'genre'),
        'show_in_rest' => true,
    );
    register_taxonomy('genre' , 'book' , $args);
}

/* Registering a call back funciton  */
add_filter( 'postmeta_form_keys' , 'bookstore_add_isbn_to_quick_edit' , 10 , 2);
function bookstore_add_isbn_to_quick_edit($keys , $post){
    if($post->post_type === 'book'){
        $keys[] = 'isbn';
    }
    return $keys;
}

// adding custom js - 
add_action('wp_enqueue_scripts' , 'bookstore_enqueue_scripts');
function bookstore_enqueue_scripts(){
    // for enqueue need style function - 
    wp_enqueue_style(
        'bookstore-style',
        plugins_url() .  '/bookstore/bookstore.css'
    ); 
    wp_enqueue_script(
        'bookstyle-script', 
        plugins_url() . '/bookstore/bookstore.js'
    );
}


