<?php
get_header();
//get_template_part( 'template-parts/slider', 'home' );
 ?>
  </header>
            </div>
        </div>
        <?php
        get_template_part( 'template-parts/top-main', 'game' );
        ?>
        <div id="main">
        <div id="content" class="column" role="main">
            <a id="main-content"></a>

            <div id="block-views-content-blocks-block-10" class="block block-views first last odd">


                <div class="view view-content-blocks view-id-content_blocks view-display-id-block_10 one-column view-dom-id-4a72f1f00bdd2ed22cb1518ca92c24a7">



                    <div class="view-content">
                    <!-- LOOP -->
                    <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post();

                        /*
                        * Include the Post-Format-specific template for the content.
                        * If you want to override this in a child theme, then include a file
                        * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                        */
                        get_template_part( 'template-parts/content', 'game' );

                    // End the loop.
                    endwhile;

                    ?>
                    </div>
                </div>
    </div>
    <div class="view view-taxonomy-term view-id-taxonomy_term view-display-id-page one-column view-dom-id-a2407c311bf56f574466e7c638696d96">





    </div> <a href="http://learnenglish.britishcouncil.org/en/games/feed" class="feed-icon" title="Subscribe to Learn English | British Council"><img src="http://learnenglish.britishcouncil.org/misc/feed.png" width="16" height="16" alt="Subscribe to Learn English | British Council" /></a> </div>


    <aside class="sidebars">
        <section class="region region-sidebar-second column sidebar">
            <div id="block-views-content-blocks-block-9" class="block block-views popular-block first odd">

                <h2 class="block__title block-title"><span class="popular-title-wrapper">Popular</span>
                </h2>

                <div class="view view-content-blocks view-id-content_blocks view-display-id-block_9 view-dom-id-ee7b301477377a122bfc1d13e9b5a68e">



                    <div class="view-content">
                        <?php 
                        $new_query = new WP_Query(  array(
                            'posts_per_page' => $carousel_count, 
                            'meta_key' => 'wpb_post_views_count', 
                            'orderby' => 'meta_value_num', 
                            'order' => 'DESC',
                            'monthnum'=> $month,
                            'year'=> $year, 
                            'cat'=> 10
                            )); 
                        ?>
                        <div class="views-row views-row-1 views-row-odd views-row-first">

                            <span class="views-field views-field-comment-count">        <span class="field-content">2751</span> </span>
                            <span class="views-field views-field-title">        <span class="field-content"><a href="/en/games/wordshake">Wordshake</a></span> </span>
                        </div>
                        <div class="views-row views-row-2 views-row-even">

                            <span class="views-field views-field-comment-count">        <span class="field-content">1054</span> </span>
                            <span class="views-field views-field-title">        <span class="field-content"><a href="/en/games/verb-machine">Verb Machine</a></span> </span>
                        </div>
                        <div class="views-row views-row-3 views-row-odd">

                            <span class="views-field views-field-comment-count">        <span class="field-content">981</span> </span>
                            <span class="views-field views-field-title">        <span class="field-content"><a href="/en/games/magic-gopher">Magic Gopher</a></span> </span>
                        </div>
                        <div class="views-row views-row-4 views-row-even">

                            <span class="views-field views-field-comment-count">        <span class="field-content">642</span> </span>
                            <span class="views-field views-field-title">        <span class="field-content"><a href="/en/games/pic-your-wits">Pic-your-wits</a></span> </span>
                        </div>
                        <div class="views-row views-row-5 views-row-odd views-row-last">

                            <span class="views-field views-field-comment-count">        <span class="field-content">561</span> </span>
                            <span class="views-field views-field-title">        <span class="field-content"><a href="/en/games/beat-keeper">Beat the Keeper</a></span> </span>
                        </div>
                    </div>





                </div>
            </div>
            <div id="block-menu-menu-helpandsupport" class="block block-menu no-border help_support even" role="navigation">

                <h2 class="block__title block-title"><span class="help-amp-support-title-wrapper">Help &amp; Support</span>
                </h2>

                <ul class="menu">
                    <li class="menu__item is-leaf first leaf"><a href="/en/getting-started" class="menu__link">Getting started</a></li>
                    <li class="menu__item is-leaf leaf"><a href="/en/content" class="menu__link">Find out your English level</a></li>
                    <li class="menu__item is-leaf leaf"><a href="/en/why-register-learnenglish" title="Why register on LearnEnglish?
" class="menu__link">Why register?</a></li>
                    <li class="menu__item is-leaf leaf"><a href="/en/house-rules" class="menu__link">House Rules</a></li>
                    <li class="menu__item is-leaf last leaf"><a href="/en/frequently-asked-questions" class="menu__link">Frequently asked questions</a></li>
                </ul>
            </div>
            <div id="block-menu-menu-online-courses" class="block block-menu course_two last odd" role="navigation">

                <h2 class="block__title block-title"><span class="courses-title-wrapper">Courses</span>
                </h2>

                <ul class="menu">
                    <li class="menu__item is-leaf first last leaf"><a href="/en/courses?WT.ac=sidebar-promo" class="menu__link">Find a face-to-face or online course in your country.</a></li>
                </ul>
            </div>
        </section>
    </aside>
    </div>

<?php

get_footer();
?>