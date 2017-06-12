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

/*
Show field class name in profile
 */

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields( $user ) {
	$list_class = array('A2','A1','B','C');
 ?>

	<h3>Extra profile information</h3>
	<table class="form-table">
		<tr>
			<th><label for="twitter">Class name:</label></th>
			<td>
			<?php if ( ! is_admin() ) { ?>
				<input type="text" value="<?php echo esc_attr( get_the_author_meta( 'class', $user->ID ) ); ?>" class="regular-text" disabled /><br />
				<span class="description">Your class name.</span>
			<?php } else {
				echo '<select name="class">
					<option value="">-- Select class --</option>';
					foreach ($list_class as $class) {
						echo '<option value="'.$class.'"'.(esc_attr(get_the_author_meta('class', $user->ID )) == $class ? ' selected' : '').'>'.$class.'</option>';
					}
					echo '</select>';
			} ?>
			</td>
		</tr>
	</table>
<?php }

/*
Save field class name for admin
 */

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	//if ( !current_user_can( 'edit_user', $user_id ) )
	if ( ! is_admin() )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'class', $_POST['class'] );
}

?>