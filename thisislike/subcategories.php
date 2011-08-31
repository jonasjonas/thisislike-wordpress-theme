<?php
    $this_category = get_category($cat);
    $parent = $this_category->category_parent;
    if ($parent == 0) {
        //echo '<li class="cat-item current-cat"><a href="'.$this_category->cat_link.'">'.$this_category->cat_name.'</a></li>';
        if (get_category_children($this_category->cat_ID) != "") {
            wp_list_categories('orderby=slug&show_count=0&title_li=&use_desc_for_title=1&depth=0&hide_empty=0&child_of='.$this_category->cat_ID);
        }
    } elseif ($parent != 0)  {
        $parent_category = get_category($parent);

        $catname = get_cat_name($parent);
        $catlink = get_category_link($parent);

        if ($parent_category->category_parent != 0) {
            echo '<li><a href="'.$catlink.'">'.$catname.'</a></li>';
        }
        if (get_category_children($parent) != "") {
            wp_list_categories('orderby=slug&show_count=0&title_li=&use_desc_for_title=1&depth=0&hide_empty=0&child_of='.$parent);
        }
    }	
