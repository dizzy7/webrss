$(function () {
    var doc = $(document);

    $(doc).on('scroll', function () {
        $("ul.posts-list>li.active").each(function (i, e) {
            var pos = $(e).offset().top;
            if (pos < doc.scrollTop() + 100) {
                $(e).removeClass('active').find('.btn-read').click();
            } else {
                return false;
            }
        });
    });
});