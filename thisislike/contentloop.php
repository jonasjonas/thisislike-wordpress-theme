	<?php 
	global $more;
	$more = 0;
	?>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div class="entry-body">
			<div class="separator">
                            <?php include 'articleteaser.php'; ?>
			</div>
			
			<?php 
			$content = get_the_content('',FALSE,''); //arguments remove 'more' text

			echo(TIL_theme_multicolumn($content));

                        // get cooperations
                        $cooperations = get_group("02 Kooperationen");

                        if (is_array($cooperations)) {
                            echo("
                                <div class=\"separator\">
                                    <h2>Cooperations</h2>");
                            $i = 0;

                            foreach ($cooperations as $coop) {
                                if ($i % 4 == 0 && $i > 0) {
                                    echo("</div><div class=\"separator toppadding\">");
                                }
                                $link = $coop["coop_website"][1];
                                if (substr($link, 0, 4) != "http") {
                                    $link = "http://$link";
                                }
                                
                                echo("
                                    <h3>{$coop["coop_name"][1]}</h3>
                                    <p>{$coop["coop_description"][1]}</p>
                                    <p><a href=\"$link\">{$coop["coop_website"][1]}</a></p>
                                    <p>&nbsp;</p>");

                                $i++;
                            }
                            echo("</div>");
                        }

                        // get images from extras
                        $images = get_field_duplicate('image');

                        if (is_array($images)) {
                            foreach ($images as $image) {
                                if ($image) {
                                    $imageurl = get_bloginfo('template_url').'/thumb.php?src='.$image[o].'&amp;h=430&amp;zc=1&amp;q=75';
                                    echo '<div class="video"><img src="'.$imageurl.'" /></div>';
                                }
                            }
                        }

			?>	
				
		</div>

	<?php endwhile; else: ?>	<?php endif; ?>
