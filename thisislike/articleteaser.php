<div class="entry-headline">
    <?php include('entryheadline.php'); ?>
    <div id="articleexcerpt">
        <?php 
            $excerpt = $post->post_excerpt;
            $tags = get_the_tags();
            $wrapmeta = (strlen($excerpt)/ 10 + count($tags)) > 15;
            if (!empty($excerpt)) the_excerpt(); 
        ?>
    </div>
</div>


<?php 

    if ($wrapmeta) {
        echo("</div><div class=\"separator\">");
    }
?>
<div class="meta <?php if ($wrapmeta) echo("wrap"); ?>">
	<?php
        // profiles
        $contactperson = get_field_duplicate("contactperson");
        $street = get("street");
        $zip = get("zip");
        $city = get("city");
        $email = (get_field_duplicate("emailaddress"));

	$nameofcompany = get('nameofcompany');
	$websites = get_field_duplicate('website');
	$phone = get_field_duplicate('phone');
	$fax = get('fax');
        
        // events
        $event_venue = get("event_venue");
        $event_street = get("event_street");
        $event_zip = get("event_zip");
        $event_city = get("event_city");

        if (!is_array($websites)) {
            $websites = get_field_duplicate('event_url');
        }
	
        ?>
            <dl>
                <?php
                    if (is_array($contactperson)) {
                        echo("<dt>Contact </dt><dd>");
                        foreach ($contactperson as $ct) {
                            echo("<p>$ct</p>");
                        }
                        echo("</dd>");
                    } elseif ($contactperson) {
                        echo("<dt>Contact </dt><dd>$contactperson</dd>");
                    }
                    if ($street || $zip || $city) {
                        if (trim($street) != "") {
                            $street .= ",";
                        }
                        $address = "$street $zip $city";
                        $googlemaps = "http://maps.google.com/maps?q=" . urlencode($address);

                        echo("<dt>Address </dt><dd><a href=\"$googlemaps\">$address</a></dd>");
                    }
                    if (is_array($phone)) {
                        echo("<dt>Phone </dt><dd>");
                        foreach ($phone as $ph) {
                            echo("<p><a href=\"tel:$ph\">$ph</a></p>");
                        }
                        echo("</dd>");
                    } elseif ($phone) {
                        echo("<dt>Phone </dt><dd><a href=\"tel:$phone\">$phone</a></dd>");
                    }
                    if ($fax) {
                        echo("<dt>Fax </dt><dd>$fax</dd>");
                    }
                    if (is_array($email)) {
                        echo("<dt>Email </dt><dd>");
                        foreach ($email as $em) {
                            $em = TIL_theme_replace_email_refs($em);
                            echo("<p><a href=\"mailto:$em\">$em</a></p>");
                        }
                        echo("</dd>");
                    } elseif ($email) {
                        echo("<dt>Email </dt><dd><a href=\"mailto:$email\">$email</a></dd>");
                    }

                    if ($event_venue) {
                        echo("<dt>Venue </dt><dd>$event_venue</dd>");
                    }
                    if ($event_street || $event_zip || $event_city) {
                        if (trim($event_street) != "") {
                            $event_street .= ",";
                        }
                        $address = "$event_street $event_zip $event_city";
                        $googlemaps = "http://maps.google.com/maps?q=" . urlencode($address);

                        echo("<dt>Address </dt><dd><a href=\"$googlemaps\">$address</a></dd>");
                    }

                    if (is_array($websites)) {
                        echo("<dt>Website </dt><dd>");
                        foreach ($websites as $website) {
                            $link = $website;
                            if (substr($link, 0, 4) != "http") {
                                $link = "http://$link";
                            }
                            echo("<p><a href=\"$link\">$website</a></p>");
                        }
                        echo("</dd>");
                    }
                ?>
            </dl>
            <?php if ($tags) { ?>
                <dl class="tags">
                    <dt>Tags</dt>
                    <dd><ul class="tags">
                        <?php
                            function tag_cmp($a, $b) {
                                return $a->count < $b->count;
                            }
                            usort($tags, "tag_cmp");
                            $i = 0;

                            foreach ($tags as $tag) {
                                if ($i > 35) {
                                    break;
                                }
                                $tag_link = get_tag_link($tag->term_id);
                                echo("<li><a href=\"{$tag_link}\" title=\"{$tag->name} Tag\" class=\"{$tag->slug}\">{$tag->name}</a></li>");

                                $i++;
                            }
                        ?>
                    </ul></dd>
                </dl>
            <?php } ?>
            <?php do_action('claim_button', $post_id=$post->ID); ?>
            <dl>
                <dt>Added by </dt>
                <dd>
                    <?php the_author_posts_link(); ?>	
                </dd>
            </dl>
</div>
