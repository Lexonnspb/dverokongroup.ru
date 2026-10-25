<?php

require_once (dirname(dirname(__FILE__))) . '/rvgprovider.class.php';

class RvgYoutubeProvider extends RvgProvider {

    const NAME = 'youtube';
    private  $youtubeAPIKey;
    protected $allowCheckBlocked = true;

    public function __construct(& $rvg){
        parent::__construct($rvg);
        
        $this->youtubeAPIKey = $this->modx->getOption('resvideogallery.youtube_api_key',null, 'AIzaSyAby5MgCKEZBJtAQLjl_RglzzKvdmormbQ');
        $this->patternApiUrl = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,status,contentDetails&key='.$this->youtubeAPIKey.'&id=$1';
        $this->patternEmbedCode = '<iframe %w% %h% src="//www.youtube.com/embed/%id%?rel=0&showinfo=0&autoplay=%autoplay%" frameborder="0" allowfullscreen></iframe>';
        $this->regexes = array (
            '(?:http|https):\/\/(?:video\.google\.(?:com|com\.au|co\.uk|de|es|fr|it|nl|pl|ca|cn)\/(?:[^"]*?))?(?:(?:www|au|br|ca|es|fr|de|hk|ie|in|il|it|jp|kr|mx|nl|nz|pl|ru|tw|uk)\.)?(?:youtube\.com(?:[^"]*?)?(?:&|&amp;|/|\?|;|\%3F|\%2F)(?:video_id=|v(?:/|=|\%3D|\%2F))([0-9A-Za-z-_]{11}))',
            '(?:http|https):\/\/youtu\.be\/([0-9A-Za-z-_]{11})',
        );
    }

    /**
     * @return array
     */
    public function scrape() {
        $apiUrl = $this->getApiUrl();
        $content = file_get_contents($apiUrl);
        if($json = json_decode($content,true)) {
            if(!empty($json['items'])) {
                if (isset($json['items'][0])) {
                    $item = $json['items'][0];
                    $this->videoData['video_key'] = $item['id'];
                    $this->videoData['title'] = $item['snippet']['title'];
                    $this->videoData['description'] = $item['snippet']['description'];
                    $this->videoData['embed_url'] = '//www.youtube.com/embed/' . $this->videoData['video_key'];
                    $this->videoData['thumbnails'] = array(
                        'default' => '',
                        'small' => isset($item['snippet']['thumbnails']['default']['url']) ? $item['snippet']['thumbnails']['default']['url'] : '',
                        'medium' => isset($item['snippet']['thumbnails']['medium']['url']) ? $item['snippet']['thumbnails']['medium']['url'] : '',
                        'large' => isset($item['snippet']['thumbnails']['high']['url']) ? $item['snippet']['thumbnails']['high']['url'] : '',
                        'huge' => isset($item['snippet']['thumbnails']['standard']['url']) ? $item['snippet']['thumbnails']['standard']['url'] : '',
                        'maxres' => isset($item['snippet']['thumbnails']['maxres']['url']) ? $item['snippet']['thumbnails']['maxres']['url'] : '',
                    );
                    $this->videoData['primary_thumb'] = $this->getThumbMaxSize();
                    $this->videoData['tags'] = '';
                    if (isset($item['snippet']['tags']) && is_array($item['snippet']['tags'])) {
                        $this->videoData['tags'] = implode(',', $item['snippet']['tags']);
                    }
                    $this->videoData['provider'] = self::NAME;
                    $this->videoData['duration'] = $this->convertDuration($item['contentDetails']['duration']);
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery]: Scrape  YouTube error load xml: uri= '. $apiUrl . ' error info: '.print_r(libxml_get_errors(),1));
        }
        return $this->videoData;
    }


    /**
     * @param string $duration
     * @return int
     */
    private function convertDuration($duration=''){
        preg_match_all('/PT(\d+H)?(\d+M)?(\d+S)?/', $duration, $matches);
        $hours   = strlen($matches[1][0]) == 0 ? 0 : substr($matches[1][0], 0, strlen($matches[1][0]) - 1);
        $minutes = strlen($matches[2][0]) == 0 ? 0 : substr($matches[2][0], 0, strlen($matches[2][0]) - 1);
        $seconds = strlen($matches[3][0]) == 0 ? 0 : substr($matches[3][0], 0, strlen($matches[3][0]) - 1);
        return 3600 * $hours + 60 * $minutes + $seconds;
    }

    /**
     * @param string $videoId
     * @return bool
     */
    public function checkBlocked($videoId='') {
        $apiUrl = str_ireplace('$1', $videoId, $this->patternApiUrl);
        $content = file_get_contents($apiUrl);
        if($json = json_decode($content,true)) {
            if(isset($json['items'][0])) {
                $item = $json['items'][0];
               // $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($item, 1));
                if ($item['status']['uploadStatus'] == 'processed' && $item['status']['privacyStatus'] != 'private' &&  $item['status']['embeddable']) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }

}

return 'RvgYoutubeProvider';