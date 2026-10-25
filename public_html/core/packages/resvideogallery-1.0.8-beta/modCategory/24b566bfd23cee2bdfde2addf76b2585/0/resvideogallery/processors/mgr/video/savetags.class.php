<?php

class ResVideoGalleryTagsProcessor extends modProcessor {

	/**
	 * @return array|string
	 */
	public function process() {
		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->success();
		}

		foreach ($ids as $id) {
			/** @var modProcessorResponse $response */
			$response = $this->modx->runProcessor('mgr/video/update',
				array('id' => $id, 'tags' => $this->getProperty('tags')),
				array('processors_path' => MODX_CORE_PATH . 'components/resvideogallery/processors/')
			);
			if ($response && $response->isError()) {
				return $response->getResponse();
			}
		}

		return $this->success();
	}

}

return 'ResVideoGalleryTagsProcessor';