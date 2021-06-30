<?php
/*
Template Name: 归档页面
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
                <script>
                    (function ($, window) {
                        $(function() {
                            var $a = $('#archives'),
                                $m = $('.al_mon', $a),
                                $l = $('.al_post_list', $a),
                                $l_f = $('.al_post_list:first', $a);
                            $l.hide();
                            $l_f.show();
                            $m.css('cursor', 's-resize').on('click', function(){
                                $(this).next().slideToggle(400);
                            });
                            var animate = function(index, status, s) {
                                if (index > $l.length) {
                                    return;
                                }
                                if (status == 'up') {
                                    $l.eq(index).slideUp(s, function() {
                                        animate(index+1, status, (s-10<1)?0:s-10);
                                    });
                                } else {
                                    $l.eq(index).slideDown(s, function() {
                                        animate(index+1, status, (s-10<1)?0:s-10);
                                    });
                                }
                            };
                            $('#al_expand_collapse').on('click', function(e){
                                e.preventDefault();
                                if ( $(this).data('s') ) {
                                    $(this).data('s', '');
                                    animate(0, 'up', 100);
                                } else {
                                    $(this).data('s', 1);
                                    animate(0, 'down', 100);
                                }
                            });
                        });
                    })(jQuery, window);
                </script>
                <p><?php zww_archives_list(); ?></p>
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
