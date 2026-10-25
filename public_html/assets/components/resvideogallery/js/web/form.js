(function ($) {
    var num = 0
        ,tplVideo = null;

    $(document).on('keydown paste','.js-rvg-form-url', function (e) {
       var $this = $(this);

        if(e.which==13) e.preventDefault();
        if(e.which==13 || e.type == 'paste') {
            setTimeout(function () {
                scrape($this);
            },100)
        }
    });

    $(document).on('submit','.js-rvg-form', function (e) {
        e.preventDefault();
        var $this = $(this)
            ,$notice = $('.js-rvg-form-notice',$this)
            ,$container = $('.js-rvg-form-container',$this);

        $this.addClass('rvg-form-loading');
        $notice.hide();

        $.ajax({
            dataType: 'json',
            type: 'POST',
            cache: false,
            url: $this.attr('action'),
            data: $this.serialize(),
            success: function (e) {
                if (e.result == true) {
                    $container.find('.js-rvg-form-video:not(:first)').remove();
                    $('.js-rvg-form-video-info',$this).css('display', 'none');
                    $('.js-rvg-form-thumb', $this).attr('src','');
                    $this[0].reset();
                    $notice
                        .html(e.success)
                        .show();
                    setTimeout(function () {
                        $notice.slideUp();
                    },1500);
                } else {
                    if(e.error) {
                        for (var key in e.error) {
                            $notice
                                .html(e.error[key])
                                .show();
                        }
                    }
                }
            },
            complete: function (e) {
                $this.removeClass('rvg-form-loading');
            },
            error: function (e) { console.log(e); }
        });
    });

    $(document).on('click','.js-rvg-form-btn-remove', function (e) {
        e.preventDefault();
        var $video =  $(this).closest('.js-rvg-form-video');

        $video.slideUp({complete:function () {
            $video.remove();
        }});

    });


    function scrape($this) {
        if($this.val()) {
            var $form = $('.js-rvg-form')
                , $parent = $this.closest('.js-rvg-form-video')
                , $container = $parent.closest('.js-rvg-form-container');

            $form.addClass('rvg-form-loading');
            $('.js-rvg-form-error', $parent).hide();
            $('.js-rvg-form-notice', $form).hide();

            $.ajax({
                dataType: 'json',
                type: 'POST',
                cache: false,
                url: $form.attr('action'),
                data: {
                    action: 'scrape',
                    url: $this.val()
                },
                success: function (e) {
                    if (e.result == true) {
                        for (var key in e.video) {
                            if (key != 'thumb') {
                                $('.js-rvg-form-' + key, $parent).val(e.video[key]);
                            } else {
                                $('.js-rvg-form-thumb', $parent).attr('src', e.video[key]);
                            }
                        }
                        $('.js-rvg-form-video-info', $parent).slideDown();
                        if (!$parent.hasClass('rvg-form-scraped')) {
                            $parent.addClass('rvg-form-scraped');
                            if ($form.data('multiple') == '1') {
                                if (!tplVideo) tplVideo = $('#rvg-tpl-form-video').html();
                                if(tplVideo) {
                                    num++;
                                    $container.append(tplVideo.replace(/\{\{num\}\}/g, num));
                                }
                            }
                        }
                    } else {
                        if (e.error) {
                            for (var key in e.error) {
                                $('.js-rvg-form-error', $parent)
                                    .html(e.error[key])
                                    .show();
                            }
                        }
                    }
                },
                complete: function (e) {
                    $form.removeClass('rvg-form-loading');
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }
    }
})(jQuery);
