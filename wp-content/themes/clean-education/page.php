<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Catch Themes
 * @subpackage Clean Education
 * @since Clean Education Pro 1.0
 */
$message = null;
$current_object = $wp_query->queried_object;//echo '<pre>';var_dump($page);echo '</pre>';
$post_private = get_post_meta(@$current_object->ID,'_private',true);//echo '<pre>';var_dump($post_private);echo '</pre>';
if($post_private) {
	$post_class_id = get_post_meta($current_object->ID,'_class_id',true);

	if(!is_user_logged_in()) { 
		auth_redirect();
	} else {
		$current_user = wp_get_current_user();
        $user_class_id = get_the_author_meta('_class_id', $current_user->ID );
		if($post_class_id != $user_class_id) $message = 'You cannot access this '.$current_object->post_type.'!';
	}
}

get_header();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php 
		if($message) echo '<h2>'.$message.'</h2>';
		else while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php
				/**
				 * clean_education_comment_section hook
				 *
				 * @hooked clean_education_get_comment_section - 10
				 */
				do_action( 'clean_education_comment_section' );
			?>

		<?php 
		endwhile; // end of the loop.

		?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php 
if (!is_front_page()) {
	get_sidebar();
} else {
	?>
	<style>
		@media screen and (min-width: 1024px) {
			.content-area {
				width: 100% !important;
			}
		}
		@media screen and (min-width: 900px) {
			.content-area {
				width: 100% !important;
			}
		}
	</style>
	<?php
} ?>
<?php get_footer(); ?>