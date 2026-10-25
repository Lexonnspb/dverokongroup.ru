<?php

class ResVideoGalleryUpdateProcessor extends modObjectUpdateProcessor {
	/* @var RvgVideos $object */
	public $object;
	/** @var Rvg $rvg */
	public $rvg;
	public $classKey = 'RvgVideos';
	public $languageTopics = array('core:default', 'resresvideogallery:default');

	public function initialize() {
		if (!$this->rvg = $this->modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
			return 'Could not load class ResVideoGallery!';
		}
		return parent::initialize();
	}
	
	/**
	 * @return array|bool|string
	 */
	public function beforeSet() {
		$preHooks = $this->modx->getOption('resvideogallery.pre_hooks_update', null, '');
		if (!$this->getProperty('id')) {
			return $this->failure($this->modx->lexicon('resresvideogallery.err.ns'));
		}
        $scrapeData = $this->rvg->scrapeVideo($this->object->url);
        if($scrapeData !== false) {
            $this->setProperty('primary_thumb', $scrapeData['primary_thumb']);
        } else {
            return $this->failure($this->modx->lexicon('resvideogallery.err.scrape'));
        }

		return ($this->rvg->runHooks($this,$preHooks) && parent::beforeSet());
	}

	public function afterSave() {
		$postHooks = $this->modx->getOption('resvideogallery.post_hooks_update', null, '');
		if($save = parent::afterSave()) {
			$this->rvg->runHooks($this,$postHooks, array('object'=> & $this->object));
		}
		return $save;
	}

}

return 'ResVideoGalleryUpdateProcessor';