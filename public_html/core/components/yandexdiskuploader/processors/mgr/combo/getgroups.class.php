<?php

class yandexdiskuploaderComboGroupGetListProcessor extends modProcessor
{
    /** @var YandexDiskUploader $yandexdiskuploader */
    protected $yandexdiskuploader;

    /**
     * @return bool
     */
    public function initialize()
    {
        $path = MODX_CORE_PATH . 'components/yandexdiskuploader/model/yandexdiskuploader/';
        $this->yandexdiskuploader = $this->modx->getService('yandexdiskuploader', 'YandexDiskUploader', $path);
        $this->yandexdiskuploader->initialize($this->modx->context->get('key'));

        return parent::initialize();
    }

    /**
     * @return string
     */
    public function process()
    {
        $filter = $this->getProperty('filter', false);
        $notempty = $this->getProperty('notempty', true);
        if ($filter) {
            $output = array(
                array(
                    'display' => '(Все)',
                    'value' => '',
                ),
            );
        } else {
            $output = array();
        }

        $rows = array(
            '',
            'Group 1',
            'Group 2',
            'Group 3',
            'Group 4',
        );
        foreach ($rows as $v) {
            $tmp = null;
            if (empty($v)) {
                if ($filter || !$notempty) {
                    $tmp = array(
                        'display' => '(Не указано)',
                        'value' => '_',
                    );
                }
            } else {
                $tmp = array(
                    'display' => $v,
                    'value' => preg_replace('/\s+/', '_', strtolower($v)),
                );
            }
            if (!empty($tmp)) {
                $output[] = $tmp;
            }
        }

        return $this->outputArray($output);
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('yandexdiskuploader:default');
    }
}

return 'yandexdiskuploaderComboGroupGetListProcessor';