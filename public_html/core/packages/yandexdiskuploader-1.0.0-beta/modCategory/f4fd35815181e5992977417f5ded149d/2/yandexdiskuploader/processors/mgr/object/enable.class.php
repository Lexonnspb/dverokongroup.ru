<?php

class yandexdiskuploaderObjectEnableProcessor extends modObjectProcessor
{
    public $objectType = 'yandexdiskuploaderObject';
    public $classKey = 'yandexdiskuploaderObject';
    public $languageTopics = array('yandexdiskuploader:default');
    public $permission = 'save';

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('yandexdiskuploader_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var yandexdiskuploaderObject $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('yandexdiskuploader_err_nf'));
            }
            $object->set('active', true);
            $object->save();
        }

        return $this->success();
    }
}

return 'yandexdiskuploaderObjectEnableProcessor';