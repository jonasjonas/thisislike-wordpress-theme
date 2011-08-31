<?hp /* Template Name: recent */?>

<?php get_header(); ?>	

<div id="header">
    <div class="max">
        <div id="mind-player">
            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?><?php endif; ?>	
        </div>
        <div class="container_right">
            <ul class="top-menu">
                <?php 
                    if (!TIL_cat_on_bottom()) {
                        include 'subcategories.php';
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
<div id="main">
<div id="content">
    <div class="entries">
        <?php if(is_category(TIL_theme_event_cat())) {
            list($month, $day, $year) = TIL_theme_validate_date($_GET['from_date']);
            $querystr = " 
                SELECT
                    postmeta.meta_value AS eventdate,
                    posts.*
                FROM
                    $wpdb->posts AS posts,
                    $wpdb->postmeta AS postmeta
                WHERE
                    postmeta.meta_key = 'event_date' AND
                    postmeta.meta_value >= '" . $year . "-" . $month . "-" . $day . "' AND
                    postmeta.post_id = posts.ID
                ORDER BY
                    eventdate ASC
                LIMIT " . intval(get_option('posts_per_page')) . ";";
            $pageposts = $wpdb->get_results($querystr, OBJECT);
            if ($pageposts) {
                global $pageposts; // todo perhaps need global $post for template tags
                foreach ($pageposts as $post) {
                    setup_postdata($post);
                    include 'standardteaser.php';
                }
            }
        } else { 
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    if (get_panel_name() == "intros" || get_panel_name() == "issues") {
                        include 'introteaser.php';
                    } else if (!is_category(TIL_theme_issue_cat())) {
                        include 'standardteaser.php';
                    }
                }
            }
        } ?>
    </div>
</div>
</div>

<?php get_footer(); ?>
