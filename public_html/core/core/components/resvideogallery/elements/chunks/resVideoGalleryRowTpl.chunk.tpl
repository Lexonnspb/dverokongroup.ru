<div class="rvg-col-md-4 animated zoomIn">
    <div class="rvg-gallery-item">
        <div class="rvg-gallery-item-wrap">
            <img src="[[+thumb]]" alt="[[+title]]" width="[[+width]]" height="[[+height]]" title="[[+title]]">
            [[+isVideo:is=`1`:then=`
            <div class="rvg-gallery-item-duration">[[+duration]]</div>
            `]]
            <span class="rvg-gallery-item-overlay">
                <span class="rvg-gallery-item-btn [[+isVideo:notempty=`rvg-show`]]">
                    <span>
                        [[+isVideo:is=`1`:then=`
                        <a href="#" class="js-rvg-play" data-id="[[+id]]">
                            <span class="rvg-icon icon-rvg-play rvg-hover-1"></span>
                        </a>
                        `:else=`
                            <a href="[[+url]]" data-rel="rvg-gallery:photo">
                                <span class="rvg-icon icon-rvg-search rvg-hover-1"></span>
                            </a>
                        `]]
                        </span>
                </span>
            </span>
            [[+title:notempty=`
            <div class="rvg-gallery-item-caption">
                <span>[[+title:ellipsis=`200`]]</span>
            </div>
            `]]
        </div>
        [[+tags:notempty=`
        <div class="rvg-tags">
            [[+tags]]
        </div>
        `]]
    </div>
</div>