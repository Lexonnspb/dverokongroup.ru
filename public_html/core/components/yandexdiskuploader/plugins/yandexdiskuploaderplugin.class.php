<?php

abstract class yandexdiskuploaderPlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var YandexDiskUploader $yandexdiskuploader */
    protected $yandexdiskuploader;
    /** @var array $sp */
    protected $sp;

    public function __construct(&$modx, &$sp)
    {
        $this->sp = &$sp;
        $this->modx = &$modx;
        $this->yandexdiskuploader = $this->modx->yandexdiskuploader;

        if (!is_object($this->yandexdiskuploader)) {
            $path = MODX_CORE_PATH . 'components/yandexdiskuploader/model/yandexdiskuploader/';
            $this->yandexdiskuploader = $this->modx->getService('yandexdiskuploader', 'yandexdiskuploader', $path, $this->sp);
        }
        if (!$this->yandexdiskuploader->initialized[$this->modx->context->key]) {
            $this->yandexdiskuploader->initialize($this->modx->context->key);
        }
    }

    abstract public function run();
}