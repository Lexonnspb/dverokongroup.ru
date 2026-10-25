<?php

class yandexdiskuploaderObjectGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'yandexdiskuploaderObject';
    public $classKey = 'yandexdiskuploaderObject';
    public $languageTopics = array('yandexdiskuploader:default');
    public $permission = 'view';

    /**
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }
}

return 'yandexdiskuploaderObjectGetProcessor';