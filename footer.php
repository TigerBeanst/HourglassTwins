<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Susty
 */
?>

</div>

<footer id="colophon">
    <p>Powered by<a href="https://cn.wordpress.org/" target="_blank" title="优雅的个人发布平台">WordPress</a> with Theme <a
                href="https://jakting.com/archives/hourglass-twins.html" target="_blank">HourglassTwins</a><br>

        Copyright &copy; <?php echo get22min("found_year", "2012") . ' - ' . date('Y') ?> <?php bloginfo('name') ?>
        <?php get_search_form(); ?>
    </p>

    <p><a class="menu" href="#top"><i class="fas fa-arrow-up"></i> 回到顶部 <i class="fas fa-arrow-up"></i></a></p>
</footer>
</div>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/resource/css/all.min.css">
<script src="<?php echo get_template_directory_uri(); ?>/resource/js/fancybox.umd.js"></script>
<?php if (get22min('analysis_place', '0')==1) {
    echo get22min("analysis", "");} ?>
<?php wp_footer(); ?>

</body>
</html>
