<?php

/**
 *
 * @package resvideogallery
 * @subpackage processors
 */
class ResVideoGalleryGetProcessor extends RvgProcessor
{
    public function process()
    {
        $id = (int)$this->controller->getProperty('id', 0);
        if ($video = $this->modx->getObject('RvgVideos', $id)) {
            $data = $video->toArray();
            $data['embed'] = $this->rvg->getEmbedCode($data, $this->controller->getProperty('auto_play', 1, 'isset'));
            $data['duration'] = $this->rvg->prepareDuration($data['duration']);
            $data['thumb'] = $this->rvg->getBaseUrl() . $data['thumb'];
            return $data;
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGalleryGetProcessor] ' . $this->modx->lexicon('resvideogallery.err.nf_video' . ' ID=' . $id));
            return false;
        }
    }
}

return 'ResVideoGalleryGetProcessor';