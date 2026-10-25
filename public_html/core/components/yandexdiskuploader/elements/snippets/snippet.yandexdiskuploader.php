<?php
/** @var modX $modx */
/** @var array $scriptProperties */

$sp = &$scriptProperties;

$tpl = $modx->getOption('tpl', $sp, 'tpl.YandexDiskUploader');
$inputId = $modx->getOption('inputId', $sp, 'file');
$css = $modx->getOption('yandexdiskuploader_frontend_css', null, '[[+cssUrl]]web/default.css');
$js = $modx->getOption('yandexdiskuploader_frontend_js', null, '[[+jsUrl]]web/default.js');
$css = str_replace('[[+cssUrl]]', $modx->getOption('assets_url') . 'components/yandexdiskuploader/css/', $css);
$js = str_replace('[[+jsUrl]]', $modx->getOption('assets_url') . 'components/yandexdiskuploader/js/', $js);


if (isset($_FILES[$inputId]) && $_FILES[$inputId]) {
    $headers = array("Authorization: OAuth " . $modx->getOption('yandexdiskuploader_access_token'), "Content-Type: application/json");
    if (!isset($_SESSION['yaDiskUpload_user']) || !$_SESSION['yaDiskUpload_user']) {
        $_SESSION['yaDiskUpload_user'] = date('H_i_s');
    }
    $user = $_SESSION['yaDiskUpload_user'];
    $path = date('Y-m-d') . '/' . $inputId . '/' . $user;
    $root = 'disk:/Приложения/MODX Яндекс.Диск/';
    foreach (explode('/', $path) as $dir) {
        $root = $root . $dir . '/';
        $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources?path=' . urlencode($root));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
    $tmp = explode('.', $_FILES[$inputId]['name']);
    $ext = array_pop($tmp);
    $file_name = implode('.', $tmp) .'-'. substr(md5_file($_FILES[$inputId]['tmp_name']), 0, 5) . '.' . $ext;
    $ch = curl_init('https://cloud-api.yandex.net/v1/disk/resources/upload?path=' . urlencode($root . $file_name));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = $modx->fromJSON(curl_exec($ch));
    curl_close($ch);
    if (isset($response['href']) && $response['href']) {
        $ch = curl_init($response['href']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_INFILE, fopen($_FILES[$inputId]['tmp_name'], 'r'));
        curl_setopt($ch, CURLOPT_UPLOAD, true);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
    $output = array(
        'inputId' => $inputId,
        'url' => 'https://disk.yandex.ru/client/disk/' . str_replace('disk:/', '', $root)
    );
    print $modx->toJSON($output);
    die();
} else {
    $modx->regClientStartupScript($modx->getOption('assets_url') . 'components/yandexdiskuploader/js/web/dropzone.js');
    $modx->regClientCSS($modx->getOption('assets_url') . 'components/yandexdiskuploader/css/web/dropzone.css');
    $modx->regClientCSS($css);
    $modx->regClientScript($js);
    return $modx->getChunk($tpl, array('name' => $inputId));
}