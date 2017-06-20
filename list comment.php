<?php 
                        $list_comment = get_comments( array('status' => 'approve','number' => 10) );
                        $i=0;
                        foreach ($list_comment as $comment) {
                            $class = ($i % 2 == 0) ? 'even' : 'odd';
                            $post = get_post($comment->comment_post_ID);
                            echo '<div id="views_slideshow_cycle_div_comments-block_2_'.$i.'" class="views-slideshow-cycle-main-frame-row views_slideshow_cycle_slide views_slideshow_slide views-row-'.($i+1).' views-row-'.$class.'">
                                <div class="views-slideshow-cycle-main-frame-row-item views-row views-row-0 views-row-first views-row-'.$class.'">

                                    <div class="views-field views-field-picture">
                                        <div class="field-content"><img src="<?php echo get_stylesheet_directory_uri(); ?>/sites/podcasts/files/styles/thumbnail/public/pictures/picture-939653-1496062759.jpg?itok=QeCfOkad" alt="" /></div>
                                    </div>
                                    <div class="views-field views-field-created"> <span class="field-content">'.$comment->comment_date_gmt.'</span> </div>
                                    <div class="views-field views-field-comment-body"> <span class="field-content">'.$comment->comment_content.'</span> </div>
                                    <div class="views-field views-field-title"> <span class="field-content"><a href="/en/starting-out/episode-10-sportsman">from: '.$post->title.'</a></span> </div>
                                </div>
                            </div>';
                            $i++;
                        }
                        ?>