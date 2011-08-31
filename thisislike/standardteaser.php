<div class="entry teaser <?php echo(TIL_theme_get_cat_class($post->ID)); ?>">
<div class="entry-body">
<span class="icon"></span>

<div class="entry-headline">
    <?php include('entryheadline.php'); ?>
</div>
<div class="post-image">
    <?php
        if (get('teaserimage')) {
                $rightimage = get('teaserimage');
        } else {
                $rightimage = TIL_theme_catch_that_image();
        }
        if (strpos($rightimage, "images/articleimagedefault.jpg")) {
            $imageurl = get_bloginfo('stylesheet_directory') . "/images/articleimagedefault.jpg";
        } else {
            $imageurl = get_bloginfo('template_url').'/thumb.php?src='.$rightimage.'&amp;w=262&amp;h=256&amp;zc=1&amp;q=75';
        }
    ?>
    
    <a href='<?php the_permalink() ?>'><img src="<?php echo $imageurl;?>" class="image-in-list"></a>
</div>


<div class="excerpt">
    <?php 
        //profiles
        $contactperson = get("contactperson");
        $street = get("street");
        $zip = get("zip");
        $city = get("city");
        $email = TIL_theme_replace_email_refs(get("emailaddress"));

        //events
        $event_venue = get("event_venue");
        $event_street = get("event_street");
        $event_zip = get("event_zip");
        $event_city = get("event_city");
    ?>
    <p><?php 
        $excerpt = $post->post_excerpt;
        if (strlen($excerpt) > 130) {
            $excerpt = substr($excerpt, 0, 130) . "...";
        }
        echo(__($excerpt)); 
    ?></p>
</div>
<div class="meta">
    <dl>
        <?php
            if ($contactperson) {
                echo("<dt>Contact </dt><dd>$contactperson</dd>");
            }
            if ($street || $zip || $city) {
                if (trim($street) != "") {
                    $street .= ",";
                }
                echo("<dt>Address </dt><dd>$street $zip $city</dd>");
            }
            if ($email) {
                echo("<dt>Email </dt><dd><a href=\"mailto:$email\">$email</a></dd>");
            }
            if ($event_venue) {
                echo("<dt>Venue </dt><dd>$event_venue</dd>");
            }
            if ($event_street || $event_zip || $event_city) {
                if (trim($event_street) != "") {
                    $event_street .= ",";
                }
                echo("<dt>Address </dt><dd>$event_street $event_zip $event_city</dd>");
            }
        ?>
    </dl>
</div>
</div>
</div>
