<form action="[[~[[*id]]]]" class="js-rvg-form rvg-form" data-multiple="[[+multiple]]">
    <input type="hidden" name="action" value="add">
    <div class="js-rvg-form-notice rvg-form-notice"></div>
    <div class="js-rvg-form-container">
        <!-- rvg-form-video -->
        <div class="js-rvg-form-video rvg-form-video">
            <div class="js-rvg-form-error rvg-form-error"></div>
            <label>[[%video.label.url]]
                <div class="rvg-form-url-wrap">
                    <input type="url" name="video['0'][url]" class="js-rvg-form-url" placeholder="http:\\" autofocus>
                    [[+multiple:is=`1`:then=`
                    <a href="#" class="js-rvg-form-btn-remove rvg-form-btn-remove rvg-form-btn" title="[[%video.btn.remove]]">-</a>
                    `]]
                </div>
            </label>
            <div class="js-rvg-form-video-info rvg-form-video-info">
                <img src="" class="js-rvg-form-thumb rvg-form-thumb">
                <label>[[%video.label.title]]
                    <input type="text" name="video['0'][title]" class="js-rvg-form-title">
                </label>
                <label>[[%video.label.desc]]
                    <textarea name="video['0'][description]" class="js-rvg-form-description"></textarea>
                </label>
                <label>[[%video.label.tags]]
                    <input type="text" name="video['0'][tags]" class="js-rvg-form-tags">
                </label>
            </div>
        </div>
        <!-- /.rvg-form-video -->
    </div>
    <button type="submit" class="js-rvg-form-submit rvg-form-btn">[[%video.btn.save]]</button>
</form>
[[+multiple:is=`1`:then=`
<script id="rvg-tpl-form-video" type="x-tmpl-rvg">
    <div class="js-rvg-form-video rvg-form-video">
        <div class="js-rvg-form-error rvg-form-error"></div>
        <label>[[%video.label.url]]
            <div class="rvg-form-url-wrap">
                <input type="url" name="video['{{num}}'][url]" class="js-rvg-form-url" placeholder="http:\\">
                <a href="#" class="js-rvg-form-btn-remove rvg-form-btn-remove rvg-form-btn" title="[[%video.btn.remove]]">-</a>
             </div>
        </label>
        <div class="js-rvg-form-video-info rvg-form-video-info">
            <img src="" class="js-rvg-form-thumb rvg-form-thumb">
            <label>[[%video.label.title]]
                <input type="text" name="video['{{num}}'][title]" class="js-rvg-form-title">
            </label>
            <label>[[%video.label.desc]]
                <textarea name="video['{{num}}'][description]" class="js-rvg-form-description"></textarea>
            </label>
            <label>[[%video.label.tags]]
                <input type="text" name="video['{{num}}'][tags]" class="js-rvg-form-tags">
            </label>
        </div>
    </div>
</script>
`]]