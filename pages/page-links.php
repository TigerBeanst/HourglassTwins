<?php
/*
Template Name: 友链页面
*/
get_header();
?>

<div id="primary">
    <main id="main">
        <?php if (have_posts()) : ?><?php while (have_posts()) :
        the_post(); ?>

        <div class="post-title">
            <h1><?php the_title(); ?></h1>
        </div>
        <?php edit_post_link(' [编辑页面]', '<span>', '</span>', 0, ''); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="post-content">
                <p class="tips"><kbd>站点随机排序</kbd></p>
                <?php
                $bookmarks = get_bookmarks('title_li=&orderby=rand&show_images=1'); //全部链接随机输出
                if (!empty($bookmarks)) {
                    echo '<ul class="link-content clearfix">';
                    foreach ($bookmarks as $bookmark) {
                        echo '<li><a href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" ><span class="sitename">' . $bookmark->link_name . "（" . $bookmark->link_url . "）" . '</span></a></li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            <?php if (comments_open()) { ?>
                <h2><i class="fa fa-comments"></i> 评论</h2>
                <?php comments_template(); ?>
            <?php } ?>
            <?php endwhile; ?>
            <?php endif; ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
?>
