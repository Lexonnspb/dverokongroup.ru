<?php

class yandexdiskuploaderObjectCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'yandexdiskuploaderObject';
    public $classKey = 'yandexdiskuploaderObject';
    public $languageTopics = array('yandexdiskuploader:default');
    public $permission = 'create';
    /** @var YandexDiskUploader $yandexdiskuploader */
    protected $yandexdiskuploader;

    /**
     * @return bool
     */
    public function initialize()
    {
        $path = MODX_CORE_PATH . 'components/yandexdiskuploader/model/yandexdiskuploader/';
        if (!$this->yandexdiskuploader = $this->modx->getService('yandexdiskuploader', 'YandexDiskUploader', $path)) {
            return false;
        }
        $this->yandexdiskuploader->initialize($this->modx->context->key);

        return parent::initialize();
    }

    /**
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::beforeSave();
    }

    /**
     * @return bool
     */
    public function beforeSet()
    {
        // Проверяем на заполненность
        $required = array(
            'group',
            'parent',
            'name:yandexdiskuploader_err_required_name',
        );
        $this->yandexdiskuploader->tools->checkProcessorRequired($this, $required, 'yandexdiskuploader_err_required');

        // Проверяем на уникальность
        $unique = array(
            'name:yandexdiskuploader_err_unique_name',
        );
        $this->yandexdiskuploader->tools->checkProcessorUnique('', 0, $this, $unique, 'yandexdiskuploader_err_unique');

        return parent::beforeSet();
    }
}

return 'yandexdiskuploaderObjectCreateProcessor';