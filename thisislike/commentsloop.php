<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<div class="entry-body">
		<?php
			global $wp_query;
			$thePostID = $wp_query->post->ID;				
			$comments = get_comments('post_id=' . $thePostID . ',status=approve');
			
		  		foreach($comments as $comm) {

					echo '<div class="separator comments"><div class="content"><p>';
					echo($comm->comment_content);
					$comid = $comm->comment_ID;
					$commentdate = $comm->comment_date;
					$commentday = explode(" ", $commentdate);
					$commentdays = explode("-", $commentday[0]);

					
					echo '</p></div><div class="meta">';
					// echo ($comm->user_id);
					//echo get_avatar($comm->user_id, 50);
					
					//echo get_avatar( $comment, 50 );
					
					echo '<dl><dt>posted by </dt><dd class="commentauthor">'.$comm->comment_author.'</dd> <dt>on</dt> <dd class="commentdate">'.$commentdays[2].'/'.$commentdays[1].'/'.$commentdays[0].'</dd></dl>';			
					
					
					echo '</div></div>';
				}		  		
		?>			
	</div>

<?php endwhile; else: ?>	<?php endif; ?>
