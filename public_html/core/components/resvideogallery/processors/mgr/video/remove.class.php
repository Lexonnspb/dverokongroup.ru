<?php

class ResVideoGalleryRemoveProcessor extends modObjectRemoveProcessor {
	public $classKey = 'RvgVideos';
	public $languageTopics = array('resresvideogallery:default');
	/** @var Rvg $rvg */
	public $rvg;

	public function initialize() {
		if (!$this->rvg = $this->modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
			return 'Could not load class ResVideoGallery!';
		}
		return parent::initialize();
	}

	/**
	 * @return array|string
	 */
	public function process() {
		$id = $this->getProperty('id');
		if (empty($id)) {
			return $this->failure($this->modx->lexicon('resresvideogallery.err.ns'));
		}

		$resource_id = 0;
		$postHooks = $this->modx->getOption('resvideogallery.post_hooks_remove', null, '');
		$preHooks = $this->modx->getOption('resvideogallery.pre_hooks_remove', null, '');

		if(!$this->rvg->runHooks($this,$preHooks)) {
			return $this->modx->error->failure();
		}
		/* @var RvgVideos $video */
		if ($video = $this->modx->getObject('RvgVideos', $id)) {
			$resource_id = $video->get('resource_id');
			$video->remove();
		}

		$this->rvg->rankResourceVideos($resource_id);
		$this->rvg->runHooks($this,$postHooks, array('object'=> & $this->object));

		return $this->success();
	}

}

return 'ResVideoGalleryRemoveProcessor';