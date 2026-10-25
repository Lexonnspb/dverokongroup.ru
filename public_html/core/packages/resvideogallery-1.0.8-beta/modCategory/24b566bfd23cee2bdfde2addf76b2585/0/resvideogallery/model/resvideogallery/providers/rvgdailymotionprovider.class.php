<?php

require_once (dirname(dirname(__FILE__))) . '/rvgprovider.class.php';

class RvgDailymotionProvider extends RvgProvider {
    
    const  NAME = 'dailymotion';

    public function __construct(& $rvg){
        parent::__construct($rvg);

        $this->patternApiUrl = 'https://api.dailymotion.com/video/$1?fields=title,description,duration,id,tags,thumbnail_120_url,thumbnail_360_url,thumbnail_480_url,thumbnail_720_url,thumbnail_url';
        $this->patternEmbedCode = '<iframe %w% %h% src="//www.dailymotion.com/embed/video/%id%?autoplay=%autoplay%" frameborder="0" allowfullscreen></iframe>';
        $this->regexes = array (
            '(?:http|https):\/\/(?:www\.)?(?:dai|dailymotion)\.(?:com|ly|alice\.it)\/(?:(?:[^"]*?)?video|swf)?\/?([a-z0-9]{1,18})',
        );
    }

    public function scrape() {
        $apiUrl = $this->getApiUrl();
        $content = file_get_contents($apiUrl);
        if($content) {
            if($item =  json_decode($content,1)) {
                $this->videoData['video_key'] = $item['id'];
                $this->videoData['title'] = $item['title'];
                $this->videoData['description'] = $item['description'];
                $this->videoData['embed_url'] = '//www.dailymotion.com/embed/video/'.$this->videoData['video_key'];
                $this->videoData['tags'] = implode(',', $item['tags']);
                $this->videoData['provider'] = self::NAME;
                $this->videoData['duration'] =   $item['duration'];
                $this->videoData['thumbnails'] = array(
                    'default'=> '',
                    'small'=> $item['thumbnail_120_url'],
                    'medium'=> $item['thumbnail_360_url'],
                    'large'=> $item['thumbnail_480_url'],
                    'huge'=> $item['thumbnail_720_url'],
                    'maxres'=> '',
                );
                $this->videoData['primary_thumb'] = $this->getThumbMaxSize();
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape dailymotion error json decode: uri= '.$apiUrl. ' error info: '.json_last_error());
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape dailymotion error load data: uri= '.$apiUrl);
        }
        return $this->videoData;
    }

}

return 'RvgDailymotionProvider';