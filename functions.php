<?php
//检测更新
require 'inc/theme-update/theme-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://cdn.jsdelivr.net/gh/hjthjthjt/hjthjthjt/HourglassTwins/details.json',
    __FILE__, //Full path to the main plugin file or functions.php.
    'unique-plugin-or-theme-slug'
);

//重新显示删除的 WordPress 链接管理器
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//添加设置页面
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
require_once dirname( __FILE__ ) . '/inc/options-framework.php';

//页面标题
function theme_title()
{
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'theme_title');

//屏蔽s.w.org
remove_action('wp_head', 'wp_resource_hints', 2);

//禁止加载默认jQuery库
function my_enqueue_scripts()
{
    wp_deregister_script('jquery');
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts', 1);

//将上传的图片文件用时间命名
add_filter('wp_handle_upload_prefilter', 'custom_upload_filter');
function custom_upload_filter($file)
{
    $info = pathinfo($file['name']);
    $ext = $info['extension'];
    $filedate = date('YmdHis') . rand(10, 99);//为了避免时间重复，再加一段2位的随机数
    $file['name'] = $filedate . '.' . $ext;
    return $file;
}


/**FancyBox图片灯箱，大致修改版**/
add_filter('the_content', 'fancybox');
function fancybox($content)
{
    $pattern = array("/<img(.*?)src=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>/i", "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>(.*?)<\/a>/i");
    $replacement = array('<a$1href=$2$3.$4$5 data-fancybox="images"><img$1src=$2$3.$4$5$6></a>', '<a$1href=$2$3.$4$5 data-fancybox="images"$6>$7</a>');
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');


//注册菜单
register_nav_menus(array(
    'ht_menu' => '菜单'
));

//文章目录TOC
function article_toc($content)
{
    /**
     * 名称: getTOC
     * 作者: HjtHjtHjt
     * 博客：https://jakting.com/
     * 修改时间：2019年10月15日
     */
    $reg_title = '/<h(.*?)>(.*?)<\/h.*?>/';
    $content_toc = "<ul class=\"toc_list\">";
    $title_1 = 0;
    $title_2 = 0;
    $title_3 = 0;
    preg_match_all($reg_title, $content, $matches_reg, PREG_SET_ORDER, 0);
    $count = count($matches_reg);
    //判断是否存在目录
    if ($count == 0) {
        //没有
        $flag = false;
        $return = array($content, null, $flag);
    } else {
        //有
        function str_replace_limit($search, $replace, $subject, $limit = 1)
        {
            if (is_array($search)) {
                foreach ($search as $k => $v) {
                    $search[$k] = '`' . preg_quote($search[$k], '`') . '`';
                }
            } else {
                $search = '`' . preg_quote($search, '`') . '`';
            }
            return preg_replace($search, $replace, $subject, $limit);
        }

        for ($ii = 0; $ii < $count; $ii++) {
            $title_number = $matches_reg[$ii][1]; //h2，h3，h4的数字
            $title_word = $matches_reg[$ii][2]; //h2，h3，h4的标题
            //echo "第 $ii 个的title_number为：$title_number ，title_word为 $title_word <br>";
            if ($title_number == "2") {
                $title_1++;
                $content = str_replace_limit("<h2>$title_word</h2>", "<h2 id=\"title-$title_1\">$title_word</h2>", $content);
                if ($title_3 != 0) {
                    $content_toc .= "</ul></li>";
                    $title_3 = 0;
                }
                if ($title_2 != 0) {
                    $content_toc .= "</ul></li>";
                    $title_2 = 0;
                }
                $content_toc .= "<li><a href=\"#title-$title_1\">$title_1 $title_word</a>";
            } else if ($title_number == "3") {
                if ($title_2 == 0) $content_toc .= "<ul>";
                $title_2++;
                $content = str_replace_limit("<h3>$title_word</h3>", "<h3 id=\"title-$title_1-$title_2\">$title_word</h3>", $content);
                if ($title_3 != 0) {
                    $content_toc .= "</ul></li>";
                    $title_3 = 0;
                }
                $content_toc .= "<li><a href=\"#title-$title_1-$title_2\">$title_1.$title_2 $title_word</a>";

            } else if ($title_number == "4") {
                if ($title_3 == 0) $content_toc .= "<ul>";
                $title_3++;
                $content = str_replace_limit("<h4>$title_word</h4>", "<h4 id=\"title-$title_1-$title_2-$title_3\">$title_word</h4>", $content);
                $content_toc .= "<li><a href=\"#title-$title_1-$title_2-$title_3\">$title_1.$title_2.$title_3 $title_word</a></li>";
            }
        }
        if ($title_3 != 0) $content_toc .= "</ul></li>";
        if ($title_2 != 0) $content_toc .= "</ul></li>";
        $content_toc .= "</ul>";
        $flag = true;
        //修改文章内部链接完成
        $return = array($content, $content_toc, $flag);
    }
    return $return;
}

//UA显示
function HT_GetUserAgent($ua)
{
    /*
     * 由于未来 Chrome 将不再在 UA 中输出有关操作系统的字段以用于判断设备类型（PC还是手机），因此自此开始评论不再显示系统，只显示浏览器
     * 详见：https://groups.google.com/a/chromium.org/forum/#!msg/blink-dev/-2JIRNMWJ7s/yHe4tQNLCgAJ
     */

    /* 浏览器 */
    $br = "<i class='mdui-icon material-icons' style='font-size: 20px'> public</i> UNKNOWN";;
    $br_v = null;

    //Chrome
    $re = "/Chrome\/(.*?) S/i";
    if (preg_match($re, $ua, $os_matches, PREG_OFFSET_CAPTURE, 0)) {
        $br = "<i class=\"fab fa-chrome\"></i> Chrome " . $os_matches[1][0];
    }

    //Chrome Mobile & 各种基于 Chromium Mobile 的
    $re = "/Chrome\/(.*?) Mobile/i";
    if (preg_match($re, $ua, $os_matches, PREG_OFFSET_CAPTURE, 0)) {
        $br = "<i class=\"fab fa-chrome\"></i> Chrome Mobile " . $os_matches[1][0];
    }

    //Edge
    $re = "/Edge\/(.*?)$/i";
    if (preg_match($re, $ua, $os_matches, PREG_OFFSET_CAPTURE, 0)) {
        $br = "<i class=\"fab fa-edge-legacy\"></i> Edge " . $os_matches[1][0];
    }

    //Edge Chromium
    $re = "/Edg\/(.*?)$/i";
    if (preg_match($re, $ua, $os_matches, PREG_OFFSET_CAPTURE, 0)) {
        $br = "<i class=\"fab fa-edge\"></i> Edge Chromium " . $os_matches[1][0];
    }

    //Safari
    $re = "/Version\/.*?Safari\/(.*?)/i";
    if (preg_match($re, $ua, $os_matches, PREG_OFFSET_CAPTURE, 0)) {
        $br = "<i class=\"fab fa-safari\"></i> Safari";
    }

    //Firefox
    $re = "/Firefox\/(.*?)$/i";
    if (preg_match($re, $ua, $os_matches, PREG_OFFSET_CAPTURE, 0)) {
        $br = "<i class=\"fab fa-firefox-browser\"></i> Firefox " . $os_matches[1][0];
    }
    //Internet Explorer
    $re = "/Trident.*?rv:(.*?)\.0/i";
    if (preg_match($re, $ua, $os_matches, PREG_OFFSET_CAPTURE, 0)) {
        $br = "<i class=\"fab fa-internet-explorer\"></i> Internet Explorer " . $os_matches[1][0];
    }
    return $br;
}

//邮件通知 by Qiqiboy
function comment_mail_notify($comment_id)
{
    $comment = get_comment($comment_id);//根据id获取这条评论相关数据
    $content = $comment->comment_content;
    //对评论内容进行匹配
    $match_count = preg_match_all('/<a href="#comment-([0-9]+)?" rel="nofollow">/si', $content, $matchs);
    if ($match_count > 0) {//如果匹配到了
        foreach ($matchs[1] as $parent_id) {//对每个子匹配都进行邮件发送操作
            SimPaled_send_email($parent_id, $comment);
        }
    } elseif ($comment->comment_parent != '0') {//以防万一，有人故意删了@回复，还可以通过查找父级评论id来确定邮件发送对象
        $parent_id = $comment->comment_parent;
        SimPaled_send_email($parent_id, $comment);
    } else return;
}
add_action('comment_post', 'comment_mail_notify');

//发送邮件的函数 by Qiqiboy.com
function SimPaled_send_email($parent_id, $comment)
{
    $admin_email = get_bloginfo('admin_email');//管理员邮箱
    $parent_comment = get_comment($parent_id);//获取被回复人（或叫父级评论）相关信息
    $author_email = $comment->comment_author_email;//评论人邮箱
    $to = trim($parent_comment->comment_author_email);//被回复人邮箱
    $spam_confirmed = $comment->comment_approved;
    if ($spam_confirmed != 'spam' && $to != $admin_email && $to != $author_email) {

        $wp_email = get22min("email_notice",""); // e-mail 發出點, no-reply 可改為可用的 e-mail.

        $subject = '您于「' . get_the_title($comment->comment_post_ID) . '」文章的评论，有了新的回应';
        $message = '<p>您好，<code>' . trim(get_comment($parent_id)->comment_author) . '</code></p>'.
            '<p>您曾在 <code>' . get_option('blogname') . '</code> 的「' . get_the_title($comment->comment_post_ID) . '」中留下过评论:</p>'.
            '<p class="com-content">' . trim(get_comment($parent_id)->comment_content) . '</p>'.
            '<br><p>' . trim($comment->comment_author) . ' 给您的回复如下：</p>'.
            '<p class="com-content">' . trim($comment->comment_content) . '</p>'.
            '<p style="border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:15px; color:#838383;">您可以点击此链接<a href="' . htmlspecialchars(get_comment_link($parent_id, array("type" => "all"))) . '">查看完整内容</a>| 欢迎再次来访<a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>'.
            '<p style="color: #bbb;margin-top: 40px;">请不要回复该邮件，它是由程序自动发出的。</p>'.
            '<style>.com-content{background: #f3f3f3;color: #333;border-radius: 4px;margin-bottom: 1.6em;max-width: 100%;overflow: auto;padding: 1.6em;max-height: 650px;}code{color: #333;background: #f3f3f3;padding: 2px;border-radius: 4px;}</style>';
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}


//自定义评论列表模板，来自 https://dedewp.com/17366.html
function zmblog_comment($comment, $args, $depth)
{
$GLOBALS['comment'] = $comment; ?>
<li class="comment" id="li-comment-<?php comment_ID(); ?>">
    <div class="media">
        <div class="media-left">
            <?php if (function_exists('get_avatar') && get_option('show_avatars')) {
                echo get_avatar($comment, 48);
            } ?>
        </div>
        <div class="media-body">
            <?php printf(__('<p class="author_name">%s'), get_comment_author_link());
            echo " " . HT_GetUserAgent($comment->comment_agent) . "</p>"; ?>
            <div class="comment-metadata">
   			<span class="comment-pub-time">
   				<?php echo get_comment_time('Y-m-d H:i')." "; if ($comment->comment_approved == '0') echo "<em>您的评论需要等待审核…</em>"?>
   			</span>
                <?php comment_reply_link(array_merge($args, array('reply_text' => '<i class=\"fas fa-reply\"></i> 回复', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?> <?php edit_comment_link(__('(Edit)'), '&nbsp;&nbsp;', ''); ?>
            </div>
            <div class="tm-comment-text"><?php comment_text(); ?></div>
        </div>
    </div>
    <?php } ?>
    <?php

    //Ajax 评论提交
    require('resource/js/ajax-comment/main.php');
    ?>
