<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<html prefix="og: http://ogp.me/ns#">
<head>
    <?php if (get22min('analysis_place', '0')==0) {
        echo get22min("analysis", "");} ?>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico"/>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if (is_single() || is_page()) { ?>
        <meta property="og:title" name="title" content="<?php the_title(); ?>"/>
        <meta property="og:description" content="<?php the_excerpt(); ?>"/>
        <meta property="og:site_name" content="<?php bloginfo("name"); ?>"/>
    <?php } else { ?>
        <meta property="og:title" content="<?php bloginfo("name"); ?>"/>
        <meta property="og:description" content="<?php bloginfo('description'); ?>"/>
    <?php } ?>
    <link href="<?php echo get_template_directory_uri(); ?>/resource/google_fonts/fonts.css" rel="preload" as="style" onload="this.rel='stylesheet'">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<a class="to-top" name="top"></a>
<div id="page">
    <header id="masthead">
        <h1><a class="header-title" href="<?php bloginfo('url'); ?>" rel="home"><?php bloginfo('name'); ?></a><span
                    class="logo_dot"></span></h1>

        <?php if (has_nav_menu('ht_menu')) { ?>
            <?php
            //抽屉二级菜单，代码来自MDx主题，感谢！
            wp_nav_menu(array('theme_location' => 'ht_menu', 'menu' => 'menu-nav', 'depth' => 2, 'container' => false, 'menu_class' => 'mdui-list', 'menu_id' => 'menu-nav'));
            ?>
        <?php } ?>
    </header>

    <div id="content">
