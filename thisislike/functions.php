<?php 

// functions that are overridable in subthemes and work as settings for a theme
if (!function_exists('TIL_cat_on_bottom')) {
    function TIL_cat_on_bottom() {
        return false;
    }
}
if (!function_exists('TIL_theme_event_cat')) {
    function TIL_theme_event_cat() {
        return "events";
    }
}
if (!function_exists('TIL_theme_issue_cat')) {
    function TIL_theme_issue_cat() {
        return "issue";
    }
}
if (!function_exists('TIL_get_intro_post')) {
    function TIL_get_intro_post() {
        return null;
    }
}
if (!function_exists('TIL_get_font_dimensions')) {
    function TIL_get_font_dimensions() {
        static $dimensions = array(
            "p" => array(
                'charsPerLine' => 60,
                'lineHeight' => 15,
                'padding' => 15,
            ),
            "h1" => array(
                'charsPerLine' => 32,
                'lineHeight' => 24,
                'padding' => 23,
            ),
            "h2" => array(
                'charsPerLine' => 32,
                'lineHeight' => 24,
                'padding' => 23,
            ),
            "h3" => array(
                'charsPerLine' => 60,
                'lineHeight' => 15,
                'padding' => 0,
            ),
            "ul" => array(
                'charsPerLine' => 50,
                'lineHeight' => 15,
                'padding' => 0,
            ),
            "ol" => array(
                'charsPerLine' => 50,
                'lineHeight' => 15,
                'padding' => 0,
            ),
        );

        return $dimensions;
    }
}

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'TIL_theme_setup' );

/* {{{ TIL_theme_setup() */
if ( ! function_exists( 'TIL_theme_setup' )) {
    function TIL_theme_setup() {
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
    }
}
/* }}} */

/* {{{ TIL_theme_catch_that_image() */
function TIL_theme_catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches [1] [0];

    if(empty($first_img)){ //Defines a default image
        $first_img = get_bloginfo('template_url')."/images/articleimagedefault.jpg";
    }
    return $first_img;
}
/* }}} */
/* {{{ TIL_theme_multicolumn() */
function TIL_theme_multicolumn($content){
    require_once("classes/htmldom.php");
    require_once("classes/columnsplitter.php");

    // run through a couple of essential tasks to prepare the content
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

    // the first "more" is converted to a span with ID
    $columns = preg_split('/(<span id="more-\d+"><\/span>)|(<!--more-->)/', $content);
    $col_count = count($columns);

    for ($i = 0; $i < $col_count; $i++) {
        // replace BRs with paragraphs
        $columns[$i] = preg_replace('/&nbsp;<br ?\/?>/', '</p><p>&nbsp;</p><p>', $columns[$i]);	

        $columns[$i] = TIL_theme_replace_videos($columns[$i]);
        $columns[$i] = TIL_theme_autocolumn($columns[$i]);
                                
        // now add the div wrapper
        $columns[$i] = '<div class="separator">'.$columns[$i].'</div>';
    }
    $content = join($columns, "\n");

    // remove any left over empty <p> tags
    // remove empty separator
    $content = str_replace(array(
        '<p></p>', 
        "<div class=\"separator\">\n</div>", 
        "<div class=\"separator\"></div>", 
        "<div class=\"separator\"><p><br />\n</p></div>",
    ),'', $content);

    return $content;
}
/* }}} */
/* {{{ TIL_theme_autocolumn() */
function TIL_theme_autocolumn($content, $maxheight = 400){
    // parse html
    $source_html = new \depage\htmlform\abstracts\htmldom();
    $source_html->loadHTML($content);

    $splitter = new \depage\html\columnsplitter();
    $splitter->setMaxheight($maxheight);
    $splitter->setFontDimensions(TIL_get_font_dimensions());
    $columns = $splitter->split($source_html);

    // write back into content
    $content = "";
    foreach ($columns as $column) {
        foreach ($column->getBodyNodes() as $node) {
            $content .= $column->saveHTML($node);
        }
        $content .= "</div>\n<div class=\"separator\">";
    }

    return $content;
}
/* }}} */

/* {{{ TIL_theme_replace_videos() */
function TIL_theme_replace_videos($content) {
    $videosites = array(
        array( // youtube
            'regex' => "/<a href=\"http:\/\/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/|#!v=)([a-zA-Z0-9\-\_]{11})([^<]*)<\/a>/",
            'func' => TIL_theme_youtube_tag
        ),
        array( // vimeo
            'regex' => "/<a href=\"http:\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+)([^<]*)<\/a>/",
            'func' => TIL_theme_vimeo_tag
        ),
    );

    foreach ($videosites as $v) {
        preg_match_all($v['regex'], $content, $matches, PREG_SET_ORDER); 
        foreach ($matches as $match) {	 
            $content = preg_replace($v['regex'], $v['func']($match[3], 710, 430), $content, 1);	
        }
    }

    return $content;
}
/* }}} */
/* {{{ TIL_theme_youtube_tag */
function TIL_theme_youtube_tag($videoID, $width, $height) {
    $video_url = htmlspecialchars("http://www.youtube.com/embed/$videoID", ENT_QUOTES);
    $tag = "<iframe class=\"youtube-player\" type=\"text/html\" width=\"$width\" height=\"$height\" src=\"$video_url\" frameborder=\"0\"></iframe>";

    $content = "</p></div><div class=\"video\">" . $tag . "</div><div class=\"separator\"><p>";

    return $content;
}
/* }}} */
/* {{{ TIL_theme_vimeo_tag */
function TIL_theme_vimeo_tag($videoID, $width, $height) {
    $video_url = htmlspecialchars("http://player.vimeo.com/video/$videoID?byline=0&amp;portrait=0", ENT_QUOTES);
    $tag = "<iframe src=\"$video_url\" width=\"$width\" height=\"$height\" frameborder=\"0\"></iframe>";

    $content = "</p></div><div class=\"video\">" . $tag . "</div><div class=\"separator\"><p>";

    return $content;
}
/* }}} */

/* {{{ TIL_theme_split_headline() */
function TIL_theme_split_headline($headline, $color = "") {
    if ($color != "") {
        $style = "style=\"background: $color; color: #ffffff;\"";
    } else {
        $style = "";
    }
    $headline = "<span $style>" . str_replace(" ", " </span><span $style>", $headline) . "</span>";

    echo $headline;
}
/* }}} */
/* {{{ TIL_theme_get_cat_class() */
function TIL_theme_get_cat_class($post_id = null) {
    if ($post_id === null) {
        $categories[] = get_category(get_query_var('cat'),false);
    } else {
        $categories = get_the_category($post_id);
    }
    if ( empty( $categories ) ) $categories =  apply_filters( 'the_category', __( 'Uncategorized' ), $separator, $parents );

    $class = "";
    if (is_array($categories)) {
        foreach ($categories as $cat) {
            if ($post_id !== null || is_category($cat)) {
                $class .= "cat_" . $cat->name . " ";
            }
        }
    }

    return $class;
}
/* }}} */
/* {{{ TIL_theme_the_indicators() */
function TIL_theme_the_indicators($post_id, $color) {
    if ($color != "") {
        $style = "style=\"background: $color; color: #ffffff;\"";
    } else {
        $style = "";
    }

    $categories = get_the_category( $post_id );
    if ( empty( $categories ) ) $categories =  apply_filters( 'the_category', __( 'Uncategorized' ), $separator, $parents );

    $class = "";
    $indicator = "";
    if (is_array($categories)) {
        foreach ($categories as $cat) {
            $class .= "cat_" . $cat->name . " ";
            $indicator .= "<a href=\"" . get_category_link( $cat->term_id ) . "\" title=\"$cat->name\"><span class=\"cat_$cat->name\"></span></a>";
        }
    }

    echo("<span class=\"indicators\" $style>$indicator</span>");
}
/* }}} */
/* {{{ TIL_theme_the_calendar() */
function TIL_theme_the_calendar($from = null) {
    list($month, $day, $year) = TIL_theme_validate_date($from);
    global $wpdb;
    
    $querystr = " 
        SELECT
            postmeta.meta_value AS eventdate
        FROM
            $wpdb->posts AS posts,
            $wpdb->postmeta AS postmeta
        WHERE
            postmeta.meta_key = 'event_date' AND
            postmeta.meta_value LIKE '" . $year . "-" . $month . "%' AND
            postmeta.post_id = posts.ID
        ORDER BY
            eventdate ASC;";
    $pageposts = $wpdb->get_results($querystr, OBJECT);

    $today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $from_date = mktime(0, 0, 0, $month, $day, $year);
    $prev_month = mktime(0, 0, 0, $month - 1, 1, $year);
    $next_month = mktime(0, 0, 0, $month + 1, 1, $year);

    $days = date("d", mktime(0, 0, 0, $month+1, 0, $year));

    $first_of_month = mktime(0, 0, 0, $month, 1, $year);
    $first_of_next_month = mktime(0, 0, 0, $month+1, 1, $year);

    if ($pageposts) {
        foreach ($pageposts as $post) {
            list($event_month, $event_day, $event_year) = TIL_theme_validate_date($post->eventdate);
            
            if (($first_of_month <= mktime(0, 0, 0, $event_month, $event_day, $event_year))
                && ($first_of_next_month > mktime(0, 0, 0, $event_month, $event_day, $event_year)) 
            ) {
                $events[intval($event_day)] = true;
            }
        }
    }

    $date_link = get_bloginfo('url') . "/category/" . get_term_by('name', TIL_theme_event_cat(), 'category')->slug . "/?from_date="; // todo make this more flexible

    // months
    echo("
        <div class=\"months\">
            <h3>" . date("M", $from_date) . " " . date("Y", $from_date) . "</h3>
            <a href=\"" . $date_link . date("Y", $prev_month) . "-" . date("m", $prev_month) . "-" . date("d", $prev_month) . "\">&laquo;</a>
            <a href=\"" . $date_link . date("Y", $next_month) . "-" . date("m", $next_month) . "-" . date("d", $next_month) . "\">&raquo;</a>
        </div>");

    // days
    echo("<ul class=\"days\">");
    for ($i = 1; $i <= $days; $i++) {
        $class = "";
        
        if (mktime(0, 0, 0, $month, $i, $year) == $today) {
            $class .= "today ";
        }
        if (mktime(0, 0, 0, $month, $i, $year) >= $from_date) {
            $class .= "upcoming ";
        }
        if ($events[$i]) {
            $class .= "has-event ";
        }
        echo("<li class=\"$class\"><a href=\"$date_link$year-$month-$i\">$i</a></li>");
    }
    echo("</ul>");
    
    // months
    echo("
        <div class=\"monthend\">
            <a href=\"" . $date_link . date("Y", $prev_month) . "-" . date("m", $prev_month) . "-" . date("d", $prev_month) . "\">&laquo;</a>
            <a href=\"" . $date_link . date("Y", $next_month) . "-" . date("m", $next_month) . "-" . date("d", $next_month) . "\">&raquo;</a>
        </div>");
}
/* }}} */

/* {{{ TIL_theme_replace_email_refs */
function TIL_theme_replace_email_refs($email) {
    $original = array(
        "@",
        ".",
        "-",
        "_",
        );
    $repl = array(
        " *at* ",
        " *dot* ",
        " *minus* ",
        " *underscore* ",
        );
    $value = str_replace($original, $repl, $email);

    return $value;
}
/* }}} */

/* {{{ TIL_theme_new_excerpt_length() */
function TIL_theme_new_excerpt_length($length) {
	return 13;
}
add_filter('excerpt_length', 'TIL_theme_new_excerpt_length');
/* }}} */
/* {{{ TIL_theme_new_excerpt_more() */
// ändern des excerpt more Strings
function TIL_theme_new_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'TIL_theme_new_excerpt_more');
/* }}} */

/* {{{ TIL_theme_validate_date() */
function TIL_theme_validate_date($in_date) {
    $in_date = strtotime($in_date);

    if ($in_date != 0) {
        $day = date("d", $in_date);
        $month = date("m", $in_date);
        $year = date("Y", $in_date);
    } else {
        $day = date("d");
        $month = date("m");
        $year = date("Y");
    }
    return array($month, $day, $year);
}
/* }}} */

/* {{{ TIL_theme_single_post_with_cat() */
function TIL_theme_single_post_with_cat($in_category) {
    $has_category = false;
    if (is_single()) {
        $categories = get_the_category();
        foreach($categories as $i) {
            if ($i->name == $in_category) {
                $has_category = true;
            }
        }
    }
    return $has_category;
}
/* }}} */

if (function_exists('register_sidebar')) register_sidebars(1, array());

/* vim:set ft=php fenc=UTF-8 sw=4 sts=4 fdm=marker et : */
