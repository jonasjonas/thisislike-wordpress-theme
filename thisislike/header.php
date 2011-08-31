<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <title><?php 
            bloginfo('name'); 
            echo(" // ");
            bloginfo('description'); 
            wp_title('//', true, 'left'); 
        ?></title>
	<link rel="stylesheet" href="<?php bloginfo('template_url')?>/reset.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url')?>" type="text/css" media="screen" title="no title" charset="utf-8">
        <?php wp_enqueue_script('jquery'); ?>
        <?php wp_enqueue_script('jquery-wheel', get_bloginfo('template_url') . '/js/jquery.mousewheel.min.js', 'jquery'); ?>
        <?php wp_enqueue_script('knnk-global', get_bloginfo('template_url') . '/js/global.js', array('jquery', 'jquery-wheel')); ?>

        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_head(); ?>
</head>
    <body<?php if (is_home()) echo(" class=\"home\"")?>>
	<div id="wrap">
