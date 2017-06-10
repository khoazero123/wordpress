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