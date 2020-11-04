<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function optionsframework_option_name() {
	// Change this to use your theme slug
    $option_name = get_option( 'stylesheet' );
    $option_name = preg_replace( "/\W/", "_", strtolower( $option_name ) );
    return $option_name;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'theme-textdomain'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );

	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);

	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}

	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}


	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}

	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/resource/img/option/';

	$options = array();

	$options[] = array(
		'name' => '基础设置',
		'type' => 'heading'
	);

    $options[] = array(
        'name' => '博客建立年份',
        'desc' => '请别留空，<b>默认为 2012 年</b>',
        'id' => 'found_year',
        'std' => '2012',
        'type' => 'text'
    );

    $options[] = array(
        'name' => '邮件提醒发送邮箱',
        'desc' => '如 solanum@nomai.com 等，请自行使用发信插件配置发信，如 <a href="https://wordpress.org/plugins/wp-mail-smtp/" target="_blank">WP Mail SMTP</a>',
        'id' => 'email_notice',
        'type' => 'text'
    );

    $options[] = array(
        'name' => '分析代码移至 footer（默认 head）',
        'desc' => '打开这个开关以让分析代码在页面最尾端加载（Google 分析默认推荐将分析代码放置在页头）',
        'id' => 'analysis_place',
        'std' => '0',
        'type' => 'checkbox'
    );

    $options[] = array(
        'name' => '分析代码',
        'desc' => '请包含 script 标签',
        'id' => 'analysis',
        'type' => 'textarea'
    );


    /**
	 * For $settings options see:
	 * http://codex.wordpress.org/Function_Reference/wp_editor
	 *
	 * 'media_buttons' are not supported as there is no post to attach items to
	 * 'textarea_name' is set by the 'id' you choose
	 */

	return $options;
}
