<?php
    // get active category
    $cats = get_categories("parent=0&orderby=slug");
    $excludeids = array();
    $includeids = array();
    foreach ( $cats as $cat ) {
        if (TIL_theme_single_post_with_cat($cat->name)) {
            // open active category
            $cur_cat="&current_category=$cat->term_id";
        }
        if ($cat->slug == "00-intros" || $cat->slug == "about") {
            $excludeids[] = $cat->cat_ID;
        }
        if ($cat->slug == "about") {
            $includeids[] = $cat->cat_ID;
        }
    }
    $excludeid = implode($excludeids, ",");
    $includeid = implode($includeids, ",");
?>
<div id="cat_indicator" class="<?php echo(TIL_theme_get_cat_class()); ?>"></div>
<?php if (((is_category(TIL_theme_event_cat()) && !is_author())) || (TIL_theme_single_post_with_cat(TIL_theme_event_cat()))  ) { ?>
    <div id="events">
        <div class="box">
            <?php TIL_theme_the_calendar($_GET['from_date']); ?>
        </div>
    </div>
<?php } ?>

<ul id="main-bottom-menu">
    <?php
        if (TIL_cat_on_bottom()) {
            wp_list_categories("depth=2&title_li=&orderby=slug&order=ASC$cur_cat&exclude=$excludeid");
        } else {
            wp_list_categories("depth=1&title_li=&orderby=slug&order=ASC$cur_cat&exclude=$excludeid");
        }
    ?>
</ul>	

<ul class="bottom-menu">
    <?php 
        if ($includeid != "") {
            wp_list_categories("depth=1&title_li=&orderby=slug&order=ASC$cur_cat&include=$includeid");
        }
        wp_list_pages('title_li='); 
    ?>
    <li><a>|</a></li>

    <?php if (function_exists("sidebarlogin")) { ?>
        <li id="sidebarlogintoggle"><a href="#">Login</a></li>
        <li>
            <div id="sidebarlogin">
                <?php sidebarlogin("thewelcome=&thelogin=&before_title=<h2%20class=\"user\">&after_title=</h2>"); ?>
            </div>
        </li>
    <?php } else { ?>
        <li><a href="<?php bloginfo('url'); ?>/wp-login.php?action=register">Register</a></li>
        <li><a href="<?php bloginfo('url'); ?>/wp-admin/">Login</a></li>
    <?php } ?>
</ul>
