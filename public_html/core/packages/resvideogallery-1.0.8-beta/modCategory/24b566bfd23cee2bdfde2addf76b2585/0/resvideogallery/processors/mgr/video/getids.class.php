<?php

class ResVideoGalleryGetIdsProcessor extends modObjectGetListProcessor {
    public $classKey = 'RvgVideos';
    public $defaultSortField = 'rank';
    public $defaultSortDirection = 'DESC';
    public $languageTopics = array('default', 'resvideogallery:default');

    public function initialize() {
        $this->setProperty('limit',0);
        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $resourceId =  $this->getProperty('id');
        if (!empty($resourceId)) {
            $c->where(array('resource_id' => $resourceId));
        }
        return $c;
    }

    /**
     * Iterate across the data
     *
     * @param array $data
     * @return array
     */
    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) continue;
            $objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray['id'];
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }

}

return 'ResVideoGalleryGetIdsProcessor';