<?php
/**
 * Functions and definitions
 *
 * Sets up the theme using core.php and provides some helper functions using custom functions.
 * Others are attached to action and
 * filter hooks in WordPress to change core functionality
 *
 * @package Catch Themes
 * @subpackage Clean Education
 * @since Clean Education 0.1
 */

//define theme version
if ( !defined( 'CLEAN_EDUCATION_THEME_VERSION' ) ) {
	$theme_data = wp_get_theme();

	define ( 'CLEAN_EDUCATION_THEME_VERSION', $theme_data->get( 'Version' ) );
}

/**
 * Implement the core functions
 */
require trailingslashit( get_template_directory() ) . 'inc/core.php';

/**
 * Add custom css
 */
add_action( 'wp_enqueue_scripts', 'enqueue_my_styles' );
function enqueue_my_styles() {
    //wp_enqueue_style( 'my-theme', get_stylesheet_uri() );
    wp_enqueue_style( 'my-theme', get_stylesheet_directory_uri()  . "/css/quiz-style.css");
}


/**
 * Use a template for many categorys
 */

add_filter( 'category_template', 'my_category_template' );
function my_category_template( $template ) {
	if( is_category( array( 'exercise', 'skill' ) ) ) // We can search for multiple categories by slug as well
		$template = locate_template( array( 'category-exercise.php', 'category.php' ) );
	//else $template = locate_template( array( 'category-exercise.php', 'category.php' ) );
	return $template;
}


?>