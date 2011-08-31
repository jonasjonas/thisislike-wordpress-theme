<div class="smallteaser">
    <?php
        if (get('teaserimage')) {
            $rightimage = get('teaserimage');
        } else {
            $rightimage = TIL_theme_catch_that_image();
        }
        $imageurl = get_bloginfo('template_url').'/thumb.php?src='.$rightimage.'&amp;w=50&amp;h=50&amp;zc=1&amp;q=75';
    ?>
    <a class="image" href='<?php the_permalink() ?>'><img src="<?php echo $imageurl;?>" class="image-in-list"></a>
    <?php include('entryheadline.php'); ?>
</div>

