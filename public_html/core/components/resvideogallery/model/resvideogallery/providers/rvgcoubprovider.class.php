<?php

require_once (dirname(dirname(__FILE__))) . '/rvgprovider.class.php';

class RvgCoubProvider extends RvgProvider {
    
    const  NAME = 'coub';

    public function __construct(& $rvg){
        parent::__construct($rvg);

        $this->patternApiUrl = 'http://coub.com/api/v2/coubs/$1.json';
        $this->patternEmbedCode = '<iframe %w% %h% src="//coub.com/embed/%id%?muted=false&autostart=%autoplay%&originalSize=false&startWithHD=false&hideTopBar=false" allowfullscreen="true" frameborder="0"></iframe><script async src="//c-cdn.coub.com/embed-runner.js"></script>';
        $this->regexes = array (
            '(?:http|https):\/\/coub\.com\/view\/(\w+)',
        );
    }

    public function scrape() {
        $apiUrl = $this->getApiUrl();
        $content = file_get_contents($apiUrl);
        if($content) {
            if($item =  json_decode($content,1)) {
                $this->videoData['video_key'] = $item['permalink'];
                $this->videoData['title'] = $item['title'];
                $this->videoData['description'] = !empty($item['description']) ? $item['description'] : '';
                $this->videoData['embed_url'] = '//coub.com/embed/'.$this->videoData['video_key'].'?muted=false&autostart=false&originalSize=false&startWithHD=false';
                $this->videoData['tags'] = array();
                $this->videoData['provider'] = self::NAME;
                $this->videoData['duration'] =   $item['duration'];
                $this->videoData['thumbnails'] = array(
                    'default'=> $item['picture'],
                    'small'=> '',
                    'medium'=> '',
                    'large'=> '',
                    'huge'=> '',
                    'maxres'=> '',
                );
                $this->videoData['primary_thumb'] = $this->getThumbMaxSize();
                if(!empty($item['tags'])) {
                    foreach($item['tags'] as $tag)
                        $this->videoData['tags'][] = $tag['title'];
                    $this->videoData['tags'] = implode(',', $this->videoData['tags']);
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape coub error json decode: uri= '.$apiUrl. ' error info: '.json_last_error());
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape coub error load data: uri= '.$apiUrl);
        }
        return $this->videoData;
    }

}

return 'RvgCoubProvider';