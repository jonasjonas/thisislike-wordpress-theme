<?php get_header(); 
    $article = $_GET['article'];
?>
<div id="header">
    <div class="max">
	<div id="mind-player">
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?><?php endif; ?>	
	</div>
        <div class="container_right">
            <ul class="top-menu">
                <?php
                    if ($article == '') {
                        $featured_current='current_page_item';
                    } elseif ($article == 'recent') {
                        $recent_current='current_page_item';
                    }
                    echo ("<li class=$featured_current><a href=". get_bloginfo('url'). ">featured</a></li>");
                    echo ("<li class=$recent_current><a href=" . get_bloginfo('url') . "/?article=recent>recent</a></li>");
                ?>
            </ul>
        </div>
    </div>
</div>
<div id="main">
    <div id="content">				
        <div class="entries">
            <?php 
                $intropost_ID = TIL_get_intro_post();

                if ($article == '') {
                    if ($intropost_ID !== null) {
                        // normal startpage
                        $introposts = get_posts(array(
                            'include' => $intropost_ID,
                        ));
                        foreach ($introposts as $post) {
                            setup_postdata($post);
                            include 'introteaser.php';
                        }
                    }

                    $sticky = get_option("sticky_posts");
                    if ($intropost_ID !== null) {
                        $introstickyindex = array_search($intropost_ID, $sticky);
                        if ($introstickyindex !== null) {
                            array_splice($sticky, $introstickyindex, 1);
                        }
                    }
                    query_posts(array(
                        'post__in' => $sticky,
                    )); 						
                } else {
                    // recent posts
                    query_posts(array(
                        'post__not_in' => array($intropost_ID),
                        'caller_get_posts' => 1,
                    ));
                }
            
                 while (have_posts()) : the_post();
                    if (get_panel_name() == "intros" || get_panel_name() == "issues") {
                        include 'introteaser.php';
                    } else {
                        $event_date = get("event_date");
                        if (!$event_date || strtotime($event_date) > time() - 86400) { 
                            include 'standardteaser.php';
                        }
                    }
                endwhile; 
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
