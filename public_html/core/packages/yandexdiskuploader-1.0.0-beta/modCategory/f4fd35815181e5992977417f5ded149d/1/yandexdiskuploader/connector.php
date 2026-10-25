<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var YandexDiskUploader $YandexDiskUploader */
$YandexDiskUploader = $modx->getService('yandexdiskuploader', 'YandexDiskUploader', $modx->getOption('yandexdiskuploader_core_path', null, $modx->getOption('core_path') .
                                                                                                   'components/yandexdiskuploader/') .
                                                      'model/yandexdiskuploader/');
$modx->lexicon->load('yandexdiskuploader:default');

// handle request
$corePath = $modx->getOption('yandexdiskuploader_core_path', null, $modx->getOption('core_path') . 'components/yandexdiskuploader/');
$path = $modx->getOption('processorsPath', $YandexDiskUploader->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));