<?php

class ResVideoGalleryGetListProcessor extends modObjectGetListProcessor {
	public $classKey = 'RvgVideos';
	public $defaultSortField = 'rank';
	public $defaultSortDirection = 'DESC';
	public $languageTopics = array('default', 'resvideogallery:default');
	protected $_modx23;
	/** @var Rvg $rvg */
	public $rvg;

	public function initialize() {
		if (!$this->rvg = $this->modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
			return 'Could not load class ResVideoGallery!';
		}
		return parent::initialize();
	}

	/**
	 * {@inheritDoc}
	 * @return mixed
	 */
	public function process() {
		$this->_modx23 = $this->rvg->systemVersion();
		$beforeQuery = $this->beforeQuery();
		if ($beforeQuery !== true) {
			return $this->failure($beforeQuery);
		}
		$data = $this->getData();
		return $this->outputArray($data['results'], $data['total']);
	}


	/**
	 * Get the data of the query
	 * @return array
	 */
	public function getData() {
		$data = array();
		$limit = intval($this->getProperty('limit'));
		$start = intval($this->getProperty('start'));

		/* query for chunks */
		$c = $this->modx->newQuery($this->classKey);
		$c = $this->prepareQueryBeforeCount($c);
		$data['total'] = $this->modx->getCount($this->classKey, $c);
		$c = $this->prepareQueryAfterCount($c);
		$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));

		$sortClassKey = $this->getSortClassKey();
		$sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', array($this->getProperty('sort')));
		if (empty($sortKey)) {
			$sortKey = $this->getProperty('sort');
		}
		$c->sortby($sortKey, $this->getProperty('dir'));
		if ($limit > 0) {
			$c->limit($limit, $start);
		}

		$data['results'] = array();
		if ($c->prepare() && $c->stmt->execute()) {
			while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
				$data['results'][] = $this->prepareArray($row);
			}
		}
		else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, print_r($c->stmt->errorInfo(), true));
		}

		return $data;
	}


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$c->groupby($this->classKey . '.id');
		$c->where(array('resource_id' => $this->getProperty('resource_id')));
        $c->leftJoin('modUser','User','User.id = RvgVideos.createdby');
        $c->select($this->modx->getSelectColumns('RvgVideos','RvgVideos'));
        $c->select(array(
            'User.username'
        ));

		$query = trim($this->getProperty('query'));
		if (!empty($query)) {
			$c->where(array(
				'video_key:LIKE' => "%{$query}%",
				'OR:title:LIKE' => "%{$query}%",
				'OR:description:LIKE' => "%{$query}%",
			));
		}
		$tags = array_map('trim', explode(',',$this->getProperty('tags')));
		if (!empty($tags[0])) {
			$tags = implode("','", $tags);
			$c->innerJoin('RvgVideosTags','Tags',"`{$this->classKey}`.`id` = `Tags`.`video_id` AND `Tags`.`tag` IN ('" . $tags . "')");
			$c->groupby($this->classKey . '.id');
		}

		return $c;
	}


	/**
	 * @param array $row
	 *
	 * @return array
	 */
	public function prepareArray(array $row) {

		$emptyThumb =  MODX_ASSETS_URL . 'components/resvideogallery/img/web/empty_thumb.jpg';
		$row['thumb'] = empty($row['thumb']) ? $emptyThumb : $this->rvg->config['thumbsUrl'] . $row['thumb'];

		//$row['duration'] = $this->rvg->prepareDuration($row['duration']);

		$row['class'] = empty($row['active'])
			? 'inactive'
			: 'active';

		$row['properties'] = strpos($row['properties'], '{') === 0
			? $this->modx->fromJSON($row['properties'])
			: array();

		$row['active'] = !empty($row['active']);

		$row['tags'] = array();
		$q = $this->modx->newQuery('RvgVideosTags', array('video_id' => $row['id']));
		$q->select('tag');
		if ($q->prepare() && $q->stmt->execute()) {
			while ($tag = $q->stmt->fetchColumn()) {
				$row['tags'][] = array('tag' => $tag);
			}
		}

		$icon = 'x-menu-item-icon ' . ($this->_modx23 ? 'icon' : 'fa');
		$row['actions'] = array();

		$row['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-edit",
			'title' => $this->modx->lexicon('resvideogallery.video.update'),
			'action' => 'updateVideo',
			'button' => false,
			'menu' => true,
		);

		$row['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-tags",
			'title' => $this->modx->lexicon('resvideogallery.video.edit_tags'),
			'multiple' => $this->modx->lexicon('resvideogallery.video.edit_tags'),
			'action' => 'editTags',
			'button' => false,
			'menu' => true,
		);

		$row['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-share",
			'title' => $this->modx->lexicon('resvideogallery.video.show'),
			'action' => 'showVideo',
			'button' => false,
			'menu' => true,
		);

        if(!empty($row['createdby'])) {
            $row['actions'][] = array(
                'cls' => '',
                'icon' => "$icon $icon-user",
                'title' => $this->modx->lexicon('resvideogallery.video.user_info'),
                'action' => 'userInfo',
                'button' => false,
                'menu' => true,
            );
        }
        $row['actions'][] = array(
            'cls' => '',
            'icon' => "$icon $icon-refresh",
            'title' => $this->modx->lexicon('resvideogallery.video.reload_thumb'),
            'action' => 'reloadThumb',
            'button' => false,
            'menu' => true,
        );


		if (!$row['active']) {
			$row['actions'][] = array(
				'cls' => '',
				'icon' => "$icon $icon-power-off action-green",
				'title' => $this->modx->lexicon('resvideogallery.video.activate'),
				'multiple' => $this->modx->lexicon('resvideogallery.video.activate_multiple'),
				'action' => 'activateVideos',
				'button' => false,
				'menu' => true,
			);
		}
		else {
			$row['actions'][] = array(
				'cls' => '',
				'icon' => "$icon $icon-power-off action-yellow",
				'title' => $this->modx->lexicon('resvideogallery.video.inactivate'),
				'multiple' => $this->modx->lexicon('resvideogallery.video.inactivate_multiple'),
				'action' => 'inActivateVideos',
				'button' => false,
				'menu' => true,
			);
		}

		$row['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-trash-o action-red",
			'title' => $this->modx->lexicon('resvideogallery.video.delete'),
			'multiple' => $this->modx->lexicon('resvideogallery.video.delete_multiple'),
			'action' => 'removeVideos',
			'button' => false,
			'menu' => true,
		);

		return $row;
	}
}

return 'ResVideoGalleryGetListProcessor';
