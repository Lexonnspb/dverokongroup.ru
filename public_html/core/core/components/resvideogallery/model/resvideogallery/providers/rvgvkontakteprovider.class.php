<?php

require_once (dirname(dirname(__FILE__))) . '/rvgprovider.class.php';

class RvgVkontakteProvider extends RvgProvider {
    
    const  NAME = 'vkontakte';

    public function __construct(& $rvg){
        parent::__construct($rvg);

        $this->patternApiUrl = 'http://vk.com/video_ext.php?$1';
        $this->patternEmbedCode = '<iframe %w% %h% src="//vk.com/video_ext.php?%id%&autoplay=%autoplay%" frameborder="0"></iframe>';
        $this->regexes = array (
            '(?:http|https):\/\/(?:www\.)?vk\.com\/video_ext\.php\?(oid=\-?[0-9]+(?:&|&\#038;|&amp;)id=\-?[0-9]+(?:&|&\#038;|&amp;)hash=[0-9a-zA-Z]+)',
        );
    }

    public function scrape() {
        $apiUrl = $this->getApiUrl();
        $content = $this->request($apiUrl);
        if($content) {
            $this->videoData['provider'] = self::NAME;
            $this->videoData['description'] = '';
            $this->videoData['tags'] = '';//array();
            preg_match('/[\"\']jpg[\"\']\s*:\s*[\"\'](.*?)[\"\']/s', $content, $match);
            $this->videoData['primary_thumb'] = !empty($match[1]) ? stripcslashes($match[1]) : '';
            preg_match('/[\"\']md_title[\"\']\s*:\s*[\"\'](.*?)[\"\']/s', $content, $match);
            $this->videoData['title'] = !empty($match[1]) ? iconv('windows-1251', 'utf-8',$match[1]) : '';
            preg_match('/[\"\']duration[\"\']\s*:\s*(\d+)/s', $content, $match);
            $this->videoData['duration'] = !empty($match[1]) ? $match[1] : 0;
            preg_match('/[\"\']oid[\"\']\s*:\s*(-?\d+)/s', $content, $f1);
            preg_match('/[\"\']vid[\"\']\s*:\s*(-?\d+)/s', $content, $f2);
            preg_match('/[\"\']hash2[\"\']\s*:\s*[\"\'](.*?)[\"\']/s', $content, $f3);
            preg_match('/[\"\']hd[\"\']\s*:\s*(\d+)/s', $content, $f4);
            $this->videoData['video_key'] = 'oid='.$f1[1].'&id='.$f2[1].'&hash='.$f3[1];
            if(!empty($f4[1]) && $f4[1] > 0) {
                $this->videoData['video_key'] .= '&hd='.$f4[1];
            }
            $this->videoData['embed_url'] = '//vk.com/video_ext.php?'.$this->videoData['video_key'];
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape vkontakte error load data: uri= '.$apiUrl);
        }
        
        return $this->videoData;
    }

}

return 'RvgVkontakteProvider';