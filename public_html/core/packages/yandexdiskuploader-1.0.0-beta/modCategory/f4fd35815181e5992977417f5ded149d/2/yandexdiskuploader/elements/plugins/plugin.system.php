<?php
/** @var modX $modx */
/** @var YandexDiskUploader $yandexdiskuploader */

$path = MODX_CORE_PATH . 'components/yandexdiskuploader/model/yandexdiskuploader/';
if (!is_object($modx->yandexdiskuploader)) {
    $yandexdiskuploader = $modx->getService('yandexdiskuploader', 'yandexdiskuploader', $path);
} else {
    $yandexdiskuploader = $modx->yandexdiskuploader;
}
$className = 'yandexdiskuploader' . $modx->event->name;
$modx->loadClass('yandexdiskuploaderPlugin', $yandexdiskuploader->config['pluginsPath'], true, true);
$modx->loadClass($className, $yandexdiskuploader->config['pluginsPath'], true, true);
if (class_exists($className)) {
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
} else {
    // Удаляем событие у плагина, если такого класса не существует
    $event = $modx->getObject('modPluginEvent', array(
        'pluginid' => $modx->event->plugin->get('id'),
        'event' => $modx->event->name,
    ));
    if ($event instanceof modPluginEvent) {
        $event->remove();
    }
}
return;