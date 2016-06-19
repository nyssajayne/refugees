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

/* Social sharing shortcode */
function vote1refugees_shortcode_social() {
	ob_start();
	include('vote1refugees_social.php');
	$content = ob_get_clean();
	return $content;
}

add_shortcode( 'social', 'vote1refugees_shortcode_social' );

function vote1refugees_share_twitter() {
	$sharing_text = esc_attr(get_option('sharing_options_twitter'));

	$twitter_link = 'twitter.com/intent/tweet?text=' . $sharing_text . ' ' . get_bloginfo( 'url' );

	return $twitter_link;
}

add_shortcode( 'share_twitter', 'vote1refugees_share_twitter' );

function vote1refugees_share_facebook() {
	$fb_link = 'www.facebook.com/sharer/sharer.php?u=' . get_bloginfo( 'url' ) . '&title=' . get_bloginfo( 'title' );

	return $fb_link;
}

add_shortcode( 'share_facebook', 'vote1refugees_share_facebook' );

function vote1refugees_share_email(){
	$email_link = 'mailto:%20?subject=' . get_bloginfo( 'title' ) . '&body=' . esc_attr(get_option('sharing_options_email')) . ' ' . get_bloginfo( 'url' );

	return $email_link;
}

add_shortcode( 'share_email', 'vote1refugees_share_email' );