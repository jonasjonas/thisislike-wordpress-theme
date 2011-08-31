<?php /* Template Name: recent */?>

<?php get_header(); ?>	

<div id="header">
    <div class="max">
        <div id="mind-player">
            <?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?><?php endif; ?>	
        </div>
        <div class="container_right">
            <ul class="top-menu">
                <?php 
                    $tag = get_tags();
                    echo ("<li class='current_page_item'><a href='" . get_tag_link(get_query_var('tag_id')) . "'>Tag: " . single_tag_title('', false) . "</a></li>");

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
        <div class="smallteasers">
            <?php             
                if (have_posts()) {
                    $post_index = 0;
                    while (have_posts()) {
                        if ($post_index > 0 && $post_index % 6 == 0) {
                            echo("</div><div class=\"smallteasers\">");
                        }
                        the_post();
                        include 'smallteaser.php';

                        $post_index++;
                    }
                }
            ?>
        </div>
    </div>
</div>
</div>

<?php get_footer(); ?>
