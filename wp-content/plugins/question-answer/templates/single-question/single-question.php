<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 




	get_header();
	do_action('qa_action_before_single_question');

	while ( have_posts() ) : the_post(); 
	?>
	<div itemscope itemtype="http://schema.org/Question" id="question-<?php the_ID(); ?>" <?php post_class('single-question entry-content'); ?>>

       <?php

       if(isset($_GET['question_edit'])):

           do_action('qa_action_question_edit');


       elseif(isset($_GET['answer_edit'])):

           do_action('qa_action_answer_edit');


       else:

           do_action('qa_action_single_question_main');

       endif;

       ?>


    <?php //do_action('qa_action_single_question_main'); ?>
	
    </div>
	<?php
	endwhile;
		
    do_action('qa_action_after_single_question');
	//echo '</div>';
	do_action('qa_action_single_question_sidebar');
	
	//get_sidebar();
	get_footer();


