<?php
    if (get('introimage')) {
        $rightimage = get('introimage');
    } else {
        $rightimage = TIL_theme_catch_that_image();
    }
    if (strpos($rightimage, "images/articleimagedefault.jpg")) {
        $background_style = "";
    } else {
        $imageurl = get_bloginfo('template_url').'/thumb.php?src='.$rightimage.'&amp;w=663&amp;h=430&amp;zc=1&amp;q=75';
        $background_style = "background: url($imageurl)";
    }

    $class="intro";
    $link_to = "";
    if (in_category(TIL_theme_issue_cat())) {
        $class .= " issue";
        $categories = get_the_category();
        foreach ($categories as $cat) {
            if ($cat->slug != TIL_theme_issue_cat()) {
                if (is_category($cat->cat_ID)) {
                    $class .= " scrollto";
                } else {
                    $link_to .= get_category_link($cat->cat_ID);
                    $class .= " teaser";
                }
            }
        }
    }
?>
<div class="<?php echo($class); ?>" style="<?php echo($background_style); ?>">
    <div class="entry-body">
        <div class="entry-headline">
            <h1><?php 
                if ($link_to != "") {
                    echo("<a href=\"$link_to\">");
                }
                TIL_theme_split_headline(get_the_title(), $event_color);
                if ($link_to != "") {
                    echo("</a>");
                }
            ?></h1>
        </div>
        <?php 
            echo(TIL_theme_multicolumn($pages[0]));
        ?>

    </div>
</div>
