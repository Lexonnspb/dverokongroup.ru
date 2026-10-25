<?php

class ResVideoGallerySortProcessor extends modObjectProcessor {
	/** @var Rvg $rvg */
	public $rvg;

	/**
	 * It is adapted code from
	 * https://github.com/splittingred/Gallery/blob/a51442648fde1066cf04d46550a04265b1ad67da/core/components/gallery/processors/mgr/item/sort.php
	 *
	 * @return array|string
	 */

	public function initialize() {
		if (!$this->rvg = $this->modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
			return 'Could not load class ResVideoGallery!';
		}
		return parent::initialize();
	}
	public function process() {
		/* @var RvgVideos $source */
		$source = $this->modx->getObject('RvgVideos', $this->getProperty('source'));
		/* @var RvgVideos $target */
		$target = $this->modx->getObject('RvgVideos', $this->getProperty('target'));
		$resource_id = $this->getProperty('resource_id');
		$postHooks = $this->modx->getOption('resvideogallery.post_hooks_sort', null, '');
		$preHooks = $this->modx->getOption('resvideogallery.pre_hooks_sort', null, '');

		if (empty($source) || empty($target) || empty($resource_id)) {
			return $this->modx->error->failure();
		}

		if(!$this->rvg->runHooks($this,$preHooks,array('target'=> & $target, 'source'=> & $source))) {
			return $this->modx->error->failure();
		}

		if ($source->get('rank') < $target->get('rank')) {
			$this->modx->exec("UPDATE {$this->modx->getTableName('RvgVideos')}
				SET rank = rank - 1 WHERE
					resource_id = " . $resource_id . "
					AND rank <= {$target->get('rank')}
					AND rank > {$source->get('rank')}
					AND rank > 0
			");
			$newRank = $target->get('rank');
		}
		else {
			$this->modx->exec("UPDATE {$this->modx->getTableName('RvgVideos')}
				SET rank = rank + 1 WHERE
					resource_id = " . $resource_id . "
					AND rank >= {$target->get('rank')}
					AND rank < {$source->get('rank')}
			");
			$newRank = $target->get('rank');
		}
		$source->set('rank', $newRank);
		$source->save();

		$this->rvg->rankResourceVideos($resource_id);
		$this->rvg->runHooks($this,$postHooks, array('target'=> & $target, 'source'=> & $source));

		return $this->modx->error->success();
	}
}

return 'ResVideoGallerySortProcessor';
