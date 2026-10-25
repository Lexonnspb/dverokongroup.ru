<?php

require_once (dirname(dirname(__FILE__))) . '/rvgprovider.class.php';

class RvgRutubeProvider extends RvgProvider {
    
    const  NAME = 'rutube';

    public function __construct(& $rvg){
        parent::__construct($rvg);

        $this->patternApiUrl = 'http://rutube.ru/api/video/$1/?format=json';
        $this->patternEmbedCode = '<iframe %w% %h% src="//rutube.ru/play/embed/%id%?autoStart=%autoplay%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>';
        $this->regexes = array (
            '[http|https]+:\/\/(?:www\.|)rutube\.ru\/[video|tracks]+\/([a-zA-Z0-9_\-]+)',
        );
    }

    public function scrape() {
        $apiUrl = $this->getApiUrl();
        $content = file_get_contents($apiUrl);
        if($content) {
            if($item =  json_decode($content,1)) {
                $this->videoData['video_key'] = $item['track_id'];
                $this->videoData['title'] = $item['title'];
                $this->videoData['description'] = $item['description'];
                $this->videoData['embed_url'] = $item['embed_url'];
                $this->videoData['tags'] =  !empty($item['tags']) ? $item['tags'] : array();
                $this->videoData['tags'] = implode(',', $this->videoData['tags']);
                $this->videoData['provider'] = self::NAME;
                $this->videoData['duration'] =   $item['duration'];
                $this->videoData['thumbnails'] = array(
                    'default'=> $item['thumbnail_url'],
                    'small'=> '',
                    'medium'=> '',
                    'large'=> '',
                    'huge'=> '',
                    'maxres'=> '',
                );
                $this->videoData['primary_thumb'] = $this->getThumbMaxSize();
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape rutube error json decode: uri= '.$apiUrl. ' error info: '.json_last_error());
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape rutube error load data: uri= '.$apiUrl);
        }
        return $this->videoData;
    }

}

return 'RvgRutubeProvider';