<?php return array (
  '54d6d6ff5573c715fa82b817416a6bad' => 
  array (
    'criteria' => 
    array (
      'name' => 'tpl.ms2Gallery',
    ),
    'object' => 
    array (
      'id' => 91,
      'source' => 1,
      'property_preprocess' => 0,
      'name' => 'tpl.ms2Gallery',
      'description' => '',
      'editor_type' => 0,
      'category' => 0,
      'cache_type' => 0,
      'snippet' => '<div class="ms2Gallery">
    {if $files?}
        <div class="fotorama"
             data-nav="thumbs"
             data-thumbheight="45"
             data-allowfullscreen="true"
             data-swipe="true"
             data-autoplay="5000">
            {foreach $files as $file}
                <a href="{$file[\'url\']}" target="_blank">
                    <img src="{$file[\'small\']}" alt="{$file[\'name\']}" title="{$file[\'name\']}">
                </a>
            {/foreach}
        </div>
    {else}
        <img src="{(\'assets_url\' | option) ~ \'components/ms2gallery/img/web/ms2_medium.png\'}" alt="" title=""/>
    {/if}
</div>',
      'locked' => 0,
      'properties' => NULL,
      'static' => 0,
      'static_file' => 'core/components/ms2gallery/elements/chunks/chunk.ms2gallery.tpl',
      'content' => '<div class="ms2Gallery">
    {if $files?}
        <div class="fotorama"
             data-nav="thumbs"
             data-thumbheight="45"
             data-allowfullscreen="true"
             data-swipe="true"
             data-autoplay="5000">
            {foreach $files as $file}
                <a href="{$file[\'url\']}" target="_blank">
                    <img src="{$file[\'small\']}" alt="{$file[\'name\']}" title="{$file[\'name\']}">
                </a>
            {/foreach}
        </div>
    {else}
        <img src="{(\'assets_url\' | option) ~ \'components/ms2gallery/img/web/ms2_medium.png\'}" alt="" title=""/>
    {/if}
</div>',
    ),
  ),
);