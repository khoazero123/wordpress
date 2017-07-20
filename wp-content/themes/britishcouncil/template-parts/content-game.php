<div class="views-row views-row-1 views-row-odd views-row-first">
    <div class="views-field views-field-field-image">
        <div class="field-content"><a href="<?php echo get_permalink(); ?>"><img src="http://learnenglish.britishcouncil.org/sites/podcasts/files/styles/230x138/public/image/screenshot-beat-the-keeper.jpg?itok=aYCEAgAv" width="230" height="138" alt="" title="Beat the keeper" /></a></div>
    </div>
    <div class="views-field views-field-nothing"> 
        <span class="field-content">
            <div class="views-field views-field-comment-count">
                <span class="field-content"><?php 
                $comments_count = wp_count_comments( get_the_ID() );
                echo $comments_count->total_comments;
                ?></span>
            </div>
            <div class="views-field views-field-title">
                <span class="field-content"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></span>
            </div>
            <div class="views-field views-field-field-text-teaser">
                <div class="field-content">
                    <p>the_content <?php /* the_content(); */ ?></p>
                </div>
            </div>
        </span>
    </div>
</div>