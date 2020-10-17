//Ajax 评论翻页，来自 https://www.mzihen.com/wordpress-ajax-comments-pages/
$(document).ready(function ($) {
    $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');//commentnav ajax
    $(document).on('click', '.comment-navigation a', function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            beforeSend: function () {
                $('.comment-navigation').remove();
                $('.commentlist').remove();
                $('.comments-loading').slideDown();
            },
            dataType: "html",
            success: function (out) {
                result = $(out).find('.commentlist');
                nextlink = $(out).find('.comment-navigation');
                $('.comments-loading').slideUp(550);
                $('.comments-loading').after(result.fadeIn(800));
                $('.commentlist').after(nextlink);

            }
        });
    });
});
