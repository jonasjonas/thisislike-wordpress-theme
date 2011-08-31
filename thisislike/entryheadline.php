<h2>
    <?php
        $event_date = get("event_date");

        if ($event_date) {
            $event_color = get("event_color");

            $event_day = substr($event_date, 8, 2);
            $event_month = substr($event_date, 5, 2);
            echo("
                <div class=\"date\">
                    <div class=\"box\" style=\"background: $event_color;\">
                        <span>$event_day</span>
                        <span>$event_month</span>
                    </div>
                </div>");
        } else {
            $event_color = "";
        }
    ?>
    <a href="<?php the_permalink() ?>" rel="bookmark"><?php TIL_theme_split_headline(get_the_title(), $event_color); ?></a>
    <?php TIL_theme_the_indicators($post_id, $event_color); ?>
</h2>
