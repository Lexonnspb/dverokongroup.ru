<?php

require_once (dirname(dirname(__FILE__))) . '/rvgprovider.class.php';

class RvgVimeoProvider extends RvgProvider {
    
    const  NAME = 'vimeo';

    public function __construct(& $rvg){
        parent::__construct($rvg);

        $this->patternApiUrl = 'http://vimeo.com/api/v2/video/$1.json';
        $this->patternEmbedCode = '<iframe %w% %h% src="//player.vimeo.com/video/%id%?badge=0&autoplay=%autoplay%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        $this->regexes = array (
            '(?:http|https):\/\/(?:www\.)?[player.vimeo|vimeo]+\.com.*\/([0-9]{1,12})',
        );
    }

    public function scrape() {
        $apiUrl = $this->getApiUrl();
        $content = file_get_contents($apiUrl);
        if($content && $json = json_decode($content,true)) {
            $item = $json[0];
            $this->videoData['video_key'] = $item['id'];
            $this->videoData['title'] = $item['title'];
            $this->videoData['description'] = $item['description'];
            $this->videoData['embed_url'] = '//player.vimeo.com/video/'.$this->videoData['video_key'].'?badge=0';
            $this->videoData['tags'] =  !empty($item['tags']) ? array_map('trim', explode(',', $item['tags'])) : array();
            $this->videoData['tags'] = implode(',', $this->videoData['tags']);
            $this->videoData['provider'] = self::NAME;
            $this->videoData['duration'] =  $item['duration'];
            $this->videoData['thumbnails'] = array (
                'default'=> '',
                'small'=> $item['thumbnail_small'],
                'medium'=> isset($item['thumbnail_medium']) ? $item['thumbnail_medium'] : '',
                'large'=> isset($item['thumbnail_large']) ? $item['thumbnail_large'] : '',
                'huge'=> isset($item['thumbnail_huge']) ? $item['thumbnail_huge'] : '',
                'maxres'=> '',
            );
            $this->videoData['primary_thumb'] = $this->getThumbMaxSize();
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape vimeo error load data: uri= '. $apiUrl);
        }
        return $this->videoData;
    }

}

return 'RvgVimeoProvider';