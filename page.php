<?php
get_header();
?>

<div id="primary">
    <main id="main">
        <?php if (have_posts()) : ?><?php while (have_posts()) :
            the_post(); ?>

            <div class="post-title">
                <h1><?php the_title(); ?></h1>
            </div>
            <span title="发表于 <?php the_time('Y 年 m 月 d 日') ?>"><i
                        class="fas fa-hourglass-start"></i> <?php the_time('Y 年 m 月 d 日') ?></span> / <span
                title="修改于：<?php the_modified_time('Y 年 m 月 d 日') ?>"><i
                    class="fas fa-hourglass-end"></i> <?php the_modified_time('Y 年 m 月 d 日') ?>
            <?php edit_post_link(' [编辑页面]', '<span>', '</span>', 0, ''); ?></span>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="post-content">
                <?php $content = article_toc(apply_filters('the_content', get_the_content()));
                echo $content[0] ?>
            </div>
            <?php if (has_tag()) { ?>
            <div class="post-tags">
                <i class="fa fa-tag" aria-hidden="true"></i>
                <?php
                $tags = get_the_tags();
                foreach ($tags as $tag) {
                    echo "<a href='" . get_category_link($tag->term_id) . "' style='margin:0 5px 0 5px'>" . $tag->name . "</a>";
                }
                ?>
            </div>
            </article>
        <?php }
            if (comments_open()) { ?>
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
