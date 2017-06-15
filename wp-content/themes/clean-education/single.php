<?php
/**
 * The Template for displaying all single posts
 *
 * @package Catch Themes
 * @subpackage Clean Education
 * @since Clean Education 0.1
 */

$message = null;
$current_object = $wp_query->queried_object;//echo '<pre>';var_dump($page);echo '</pre>';
$post_private = get_post_meta($current_object->ID,'_private',true);//echo '<pre>';var_dump($post_private);echo '</pre>';
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


get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php 
			if($message) echo '<h2>'.$message.'</h2>';
			else while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>

				<?php
					/**
					 * clean_education_after_post hook
					 *
					 * @hooked clean_education_post_navigation - 10
					 */
					do_action( 'clean_education_after_post' );

					/**
					 * clean_education_comment_section hook
					 *
					 * @hooked clean_education_get_comment_section - 10
					 */
					do_action( 'clean_education_comment_section' );
				?>
			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>