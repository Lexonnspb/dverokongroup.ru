<?php

/**
 * @var modX $modx
 * @var Rvg $rvg
 * @var array $scriptProperties
 *
 * @package videogallery
 */

$rvg = $modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/');
if (!$rvg) return;

$src = $modx->getOption('src', $scriptProperties, '');
$src = str_replace('+', '%27', urldecode($src));
$ptOptions = $scriptProperties;
if (!$src) return;
if (empty($ptOptions['f'])) {
    $ext = pathinfo($src, PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'bmp':
            $ptOptions['f'] = $ext;
            break;
        default:
            $ptOptions['f'] = 'jpeg';
            break;
    }
}

 if($rvg->isUrl($src)) {
     if (!$file = $rvg->loadImageFromUrl($src)) return;
 } else {
     $file = $src;
 }

if (!$phpThumb = $modx->getService('modphpthumb', 'modPhpThumb', MODX_CORE_PATH . 'model/phpthumb/', array())) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'ResVideoGallery] Could not load class modPhpThumb!');
    return;
}

$phpThumb = new modPhpThumb($modx);
$phpThumb->initialize();
$phpThumb->setSourceFilename($file);

foreach ($ptOptions as $k => $v) {
    $phpThumb->setParameter($k, $v);
}

$cacheFilename .= '.' . md5(serialize($scriptProperties));
$cacheFilename .= '.' . (!empty($ptOptions['f']) ? $ptOptions['f'] : 'png');
$cacheKey = $rvg->config['cachePath'] . $cacheFilename;

if ($phpThumb->GenerateThumbnail()) {
    // imageinterlace($this->phpThumb->gdimg_output, true);
    if (!$phpThumb->renderToFile($cacheKey)) {
        $modx->log(modX::LOG_LEVEL_INFO, '[ResVideoGallery] phpThumb messages for "' . $file . '" | ' . $dst . ' . ' . print_r($phpThumb->debugmessages, 1));
        return false;
    }
} else {
    $modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not generate thumbnail for "' . $file . '" | ' . $dst . '. ' . print_r($phpThumb->debugmessages, 1));
    return false;
}

return file_get_contents($cacheKey);