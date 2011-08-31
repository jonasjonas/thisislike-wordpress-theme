<?php get_header(); ?>

<div id="header">
    <div class="max">
	<div id="mind-player">
		<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?><?php endif; ?>	
	</div>
        <div class="container_right">
        </div>
    </div>
</div>
<div id="main">
    <div id="content">				
        <div class="entries">
            <div id="post-content">
                <div class="separator">
                    <?php
                        $thisauthor = get_userdata(intval($author));


                        echo("<dl>");
                        echo("<dt>User </dt><dd>$thisauthor->user_nicename</dd>");
                        if ($thisauthor->user_email) {
                            $email = TIL_theme_replace_email_refs($thisauthor->user_email);
                            echo("<dt>Email </dt><dd><a href=\"mailto:$email\">$email</a></dd>");
                        }
                        if ($thisauthor->user_url) {
                            echo("<dt>Website </dt><dd><a href=\"$thisauthor->user_url\">$thisauthor->user_url</a></dd>");
                        }
                        echo("</dl>");
                        echo("<div id=\"articleexcerpt\">");
                            echo("<p>&nbsp;</p>");
                            echo("<p>" . str_replace("\n", "<br>", $thisauthor->description) . "</p>");
                        echo("</div>");
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
