<?php

interface RvgProviderInterface
{
    public function scrape();

}

abstract class RvgProvider implements RvgProviderInterface
{

    /* @var Rvg $rvg */
    protected $rvg;
    /* @var modX $modx */
    protected $modx;
    protected $apiUrl;
    protected $regexes = array();
    protected $videoData = array();
    protected $patternApiUrl;
    protected $patternEmbedCode;
    protected $allowCheckBlocked = false;

    public function __construct(& $rvg)
    {
        $this->rvg = &$rvg;
        $this->modx = &$rvg->modx;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function checkUrl($url)
    {
        foreach ($this->regexes as $regex) {
            if (preg_match('~' . $regex . '~imu', $url, $matches) && isset($matches[1]) && !empty($matches[1])) {
                $this->prepareApiUrl($matches);
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param array $video
     * @param bool $autoPlay
     * @param string $w
     * @param string $h
     * @return string
     */
    public function getEmbedCode($video = array(), $autoPlay = true, $w = '100%', $h = '100%')
    {
        $code = '';
        $video = empty($video) ? $this->getVideoData() : $video;
        if (!empty($video) && is_array($video)) {
            $params = array(
                'id' => $video['video_key'],
                'autoplay' => filter_var($autoPlay, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
                'w' => empty($w) ? '' : 'width="' . $w . '"',
                'h' => empty($h) ? '' : 'height="' . $h . '"',
            );
            $code = $this->getPatternEmbedCode();
            foreach ($params as $k => $v) {
                $code = str_ireplace('%' . $k . '%', $v, $code);
            }
        }
        return $code;
    }

    /**
     * @return bool
     */
    public function hasAllowCheckBlocked()
    {
        return $this->allowCheckBlocked;
    }

    /**
     * @param string $videoId
     * @return bool
     */
    public function checkBlocked($videoId = '')
    {
        return false;
    }

    /**
     * @return string
     */
    public function getPatternEmbedCode()
    {
        return $this->patternEmbedCode;
    }


    /**
     * @return array
     */
    public function getVideoData()
    {
        return $this->videoData;
    }

    /**
     * @param string $url
     * @param array $headers
     * @return string
     */
    public function request($url, $headers = array())
    {
        $result = '';
        $headers = !empty($headers) ? $headers : array(
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'content-type: application/x-www-form-urlencoded',
            'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Cookie: remixlang=0',
            'user-agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36'
        );
        $c = curl_init();
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HEADER, 1);
        curl_setopt($c, CURLOPT_URL, $url);
        @curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        //  curl_setopt($c, CURLOPT_AUTOREFERER, 0);
        //  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($c, CURLOPT_ENCODING, 'gzip');
        curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($c);
        $eo = curl_errno($c);
        $err = curl_error($c);
        if ($eo > 0) {
            $this->RaiseExeption("CURL error in '$eo' $err");
        }
        curl_close($c);
        return $result;
    }


    /**
     * @return string
     */
    protected function getThumbMaxSize()
    {
        $thumbnails = array_reverse($this->videoData['thumbnails']);
        foreach ($thumbnails as $thumb) {
            if (!empty($thumb))
                return $thumb;
        }
        return '';
    }

    /**
     * @param array $matches
     * @return void
     */
    protected function prepareApiUrl($matches)
    {
        $url = $this->patternApiUrl;
        foreach ($matches as $i => $match) {
            $url = str_ireplace('$' . $i, $match, $url);
        }
        $this->apiUrl = $url;
    }
}