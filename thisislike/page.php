<?php get_header(); ?>

<div id="header">
    <div class="max">
	<div id="mind-player">
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?><?php endif; ?>	
	</div>
        <div class="container_right">
            <ul class="top-menu">
		<?php 
		global $wp_query;
		$thePostID = $wp_query->post->ID;
		$article = $_GET['article'];
		$comments = get_comments('post_id=' . $thePostID . ',status=approve');
		$howmanycomments = sizeof($comments);
		?>
		
		
                <li class="<?php if ($article == '') {echo 'current_page_item ';}?>"><a href="<?php the_permalink(); ?>">Article</a></li>
                <?php if ($howmanycomments > 0) { ?>
                    <li class="page-comment <?php if ($article == 'comments') {echo 'current_page_item ';}?>"><a href="<?php the_permalink(); ?>?article=comments">comments (<?php echo $howmanycomments; ?>) </a></li>
                <?php } ?>
                <li class="page-comment <?php if ($article == 'leavecomment') {echo 'current_page_item';}?>"><a href="<?php the_permalink(); ?>?article=leavecomment">leave a comment</a></li>

            </ul>
        </div>
    </div>
</div>
<div id="main">

	<div id="content">				
				<div class="entries">
					 <div id="post-content">
					
					<?php 

					
					if ($article == 'comments') {
						include 'commentsloop.php';
					} elseif ($article == 'leavecomment') {
						include 'leaveacommentloop.php';
						  		
					} else {
                                            include 'contentloop.php'; 					
					}
					?>
							 	
					 </div>

				</div>

				</div>
			</div>

<?php get_footer(); ?>
