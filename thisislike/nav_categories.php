<?php
    $this_category = get_category($cat);
    $parent = $this_category->category_parent;


    if ($parent == 0) {
        $r["child_of"] = $this_category->cat_ID;
    } else {
        $r["child_of"] = $parent;
    }

    $categories = get_categories( $r );
    //print_r($categories);
