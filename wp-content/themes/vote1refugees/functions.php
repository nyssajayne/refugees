<?php
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_styles' );

/**
 * Register style sheet.
 */
function register_styles() {
	wp_register_style( 'style', get_stylesheet_directory_uri() . '/style.css' );
	wp_register_style( 'main', get_stylesheet_directory_uri() . '/main.css' );
	wp_register_style( 'raleway', 'https://fonts.googleapis.com/css?family=Raleway:400,300italic,700,300' );
	
	wp_enqueue_style( 'style' );
	wp_enqueue_style( 'main' );
	wp_enqueue_style( 'raleway' );
}

add_action( 'wp_enqueue_scripts', 'register_scripts' );

function register_scripts() {
	wp_register_script( 'scripts', get_stylesheet_directory_uri() . '/scripts/scripts.js' );
	wp_register_script( 'fontawesome', 'https://use.fontawesome.com/47ac25ce56.js' );

	wp_enqueue_script( 'scripts' );
	wp_enqueue_script( 'fontawesome' );
}

add_action( 'after_setup_theme', 'register_menu' );

function register_menu() {
  register_nav_menu( 'primary', __( 'Primary Menu', 'theme-slug' ) );
}