<?php
/**
 *
 * @package resvideogallery
 * @subpackage processors
 */

class ResVideoGalleryAddProcessor extends RvgProcessor {
    public function process() {
        $ids = array();
        $allowTags = $this->controller->getProperty('allowTags','');
        $resourceId = $this->controller->getProperty('resource',0);
        $snTags = $this->controller->getProperty('tags',array());
        foreach ($this->dictionary->get('video',array()) as $video) {
            if(isset($video['url']) && !empty($video['url'])) {
                $videoData = array();
                $scrapeData = $this->rvg->scrapeVideo($video['url']);
                if($scrapeData !== false) {
                    $scrapeTags = isset($scrapeData['tags'])  ? array_map('trim', explode(',',$scrapeData['tags'])) : array();
                    $tags = array_merge($scrapeTags,$snTags);
                    $tags = array_diff($tags, array(''));
                    $videoData['url'] = $video['url'];
                    $videoData['video_key'] = $scrapeData['video_key'];
                    $videoData['provider'] = $scrapeData['provider'];
                    $videoData['duration'] = $scrapeData['duration'];
                    $videoData['primary_thumb'] = $scrapeData['primary_thumb'];
                    $videoData['title'] = isset($video['title']) ? strip_tags($video['title'],$allowTags) : '';
                    $videoData['description'] = isset($video['description']) ? strip_tags($video['description'],$allowTags) : '';
                    $videoData['resource_id'] = $resourceId;
                    $videoData['active'] = $this->controller->getProperty('active',1);
                    $videoData['createdby'] = $this->modx->user->id;
                    $videoData['createdon'] = date('Y-m-d H:i:s');
                    $videoData['rank'] = $this->modx->getCount('RvgVideos', array('resource_id' => $resourceId));
                    if($video = $this->modx->newObject('RvgVideos', $videoData)) {
                        if(!$video->save()) {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, '[resVideoGallery] error save user video '.print_r($videoData,1));
                        } else {
                            $ids[] = $video->id;
                            foreach ($tags as $tag) {
                                $tag = strip_tags($tag);
                                if(!empty($tag)) {
                                    $videosTags = $this->modx->newObject('RvgVideosTags');
                                    $videosTags->set('video_id', $video->id);
                                    $videosTags->set('tag', strip_tags($tag));
                                    if (!$videosTags->save()) {
                                        $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] error save tag "' . $tag . '" for video ID ' . $video->id);
                                    }
                                }
                            }
                        }
                    } else {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] error create video object for data '.print_r($videoData,1));
                    }

                } else  {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] error scrape video  data  for url '.$video['url']);
                }
            }
        }
        return $ids;
    }
}
return 'ResVideoGalleryAddProcessor';