<?php

class ResVideoGalleryInActivateProcessor extends modObjectProcessor {
	public $classKey = 'msResourceFile';
	public $languageTopics = array('resresvideogallery:default');


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$id = $this->getProperty('id')) {
			return $this->failure($this->modx->lexicon('resresvideogallery.err.ns'));
		}

		/* @var RvgVideos $video */
		if ($video = $this->modx->getObject('RvgVideos', $id)) {
			$video->set('active', 0);
			$video->save();
		}

		return $this->success();
	}
}

return 'ResVideoGalleryInActivateProcessor';