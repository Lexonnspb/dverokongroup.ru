jQuery(function ($) {
    function getFilterUrl(filter) {
        filter = filter || '';
        var url = window.location.href.indexOf('?') > -1 ? window.location.href.replace(/\?(.+)/, '') : window.location.href;
        return url + '?filter=' + filter;
    }

    $(document).on('click', '.js-rvg-play', function (e) {
        e.preventDefault();
        var $this = $(this),
            $gallery = $this.closest('.js-rvg-gallery'),
            id = $this.data('id');
        if (id) {
            lightcase.start({
                swipe: true,
                href: '/assets/components/resvideogallery/action.php',
                type: 'ajax',
                ajax: {
                    type: 'post',
                    data: {
                        controller: 'video',
                        action: 'embed',
                        tpl_embed: $gallery.data('tpl-embed'),
                        tpl_tag: $gallery.data('tpl-tag'),
                        auto_play: $gallery.data('auto-play'),
                        data_type: 'html',
                        id: id
                    }
                }
            });
        } else {
            console.log("Error video ID not find!");
        }
    });

    $(document).on('click', '.js-rvg-more', function (e) {
        e.preventDefault();
        var $this = $(this)
            , params = $this.data('params') || null
            , target = $this.data('target') || null;
        if (params) {
            var $gallery = $this.closest('.js-rvg-gallery')
                , $target = target ? $('#' + target) : $gallery.find('.js-rvg-holder');
            if ($target.length) {
                var offset = parseInt(params.offset);
                $gallery.addClass('rvg-loading');
                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    cache: false,
                    url: '/assets/components/resvideogallery/action.php',
                    data: {
                        action: 'more',
                        controller: 'video',
                        params: params
                    },
                    success: function (e) {
                        $gallery.removeClass('rvg-loading');
                        if (e.result == true) {
                            if (params.offset == 0 && params.filter != undefined) {
                                $target.html('');
                            }
                            $target.append(e.data);
                            $('a[data-rel^=rvg-gallery]').lightcase();
                            var total = e.total ? e.total : params.total;
                            if (parseInt(total) <= (parseInt(params.offset) + parseInt(params.limit))) {
                                $this.hide();
                            } else {
                                $this.show();
                            }
                            params.offset = offset + parseInt(params.limit);
                        } else {
                            for (var key in e.error) {
                                console.log(e.error[key]);
                            }
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            } else {
                console.log("Error not find target");
            }
        } else {
            console.log("Error not set params");
        }
    });

    $(document).on('click', '.js-rvg-tag-clear', function (e) {
        e.preventDefault();
        var $this = $(this)
            , $btnMore = $('.js-rvg-more')
            , params = $btnMore.data('params') || null;
        $('.rvg-tag-active').removeClass('rvg-tag-active');
        if (params) {
            params.offset = 0;
            params.filter = '';
            $btnMore.data(params).trigger('click');
        }
    });

    $(document).on('click', '.js-rvg-tag', function (e) {
        e.preventDefault();
        var $this = $(this)
            , $btnMore = $('.js-rvg-more')
            , filter = []
            , tag = $(this).text();
        if ($this.hasClass('rvg-tag-active')) {
            $('.rvg-tag:contains("' + tag + '")').each(function () {
                if ($(this).text() === tag)
                    $(this).removeClass('rvg-tag-active');
            });

        } else {
            $('.rvg-tag:contains("' + tag + '")').each(function () {
                if ($(this).text() === tag)
                    $(this).addClass('rvg-tag-active');
            });
        }
        $('.rvg-tag-active').each(function () {
            var tag = $(this).text();
            if ($.inArray(tag, filter) == -1) filter.push(tag);
        });

        if ($btnMore.length) {
            var params = $btnMore.data('params') || null;
            if (params) {
                params.offset = 0;
                params.filter = filter.length ? filter.join() : '';
                $btnMore.data(params).trigger('click');
            }
        } else {
            window.location.href = getFilterUrl(filter.join())

        }
    });

    $('a[data-rel^="rvg-gallery:photo"]').lightcase();
});