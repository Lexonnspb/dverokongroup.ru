<?php

class ResVideoGalleryScrapeProcessor extends modProcessor {
	/** @var Rvg $rvg */
	public $rvg;
	public $languageTopics = array('resvideogallery:default');

	/**
	 * @return bool|null|string
	 */
	public function initialize() {
		if (!$this->rvg = $this->modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
			return 'Could not load class videoGallery!';
		}
		return true;
	}


	/**
	 * @return array|string
	 */
	public function process() {
		$url = $this->getProperty('url', '');
		if (empty($url)) {
			return $this->failure($this->modx->lexicon('resvideogallery.err.ns'));
		}
		$videoData = $this->rvg->scrapeVideo($url);
		if($videoData === false) {
			return $this->failure($this->modx->lexicon('resvideogallery.err.scrape'));
		}

		return $this->success('', $videoData);
	}

}

return 'ResVideoGalleryScrapeProcessor';
