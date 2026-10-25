<?php
/**
 * ResVideoGallery
 * @package resvideogallery
 */
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
switch ($modx->event->name) {
    case 'OnDocFormRender':
        /** @var modResource $resource */
        if ($mode == 'new') {
            return;
        }
        $template = $resource->get('template');
        $templates = array_map('trim', explode(',', $modx->getOption('resvideogallery.disable_for_templates')));
        if (!empty($templates) && in_array($template, $templates)) {
            return;
        }

        /** @var Rvg $rvg */
        if ($rvg = $modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
            $rvg->loadVideoManagerFiles($modx->controller, $resource);
        }
        break;

    case 'OnBeforeEmptyTrash':
        if (empty($scriptProperties['ids']) || !is_array($scriptProperties['ids'])) {
            return;
        }

        /** @var Rvg $rvg */
        if (!$rvg = $modx->getService('resvideogallery', 'Rvg', MODX_CORE_PATH . 'components/resvideogallery/model/resvideogallery/')) {
            return;
        }

        $resources = $modx->getIterator('modResource', array('id:IN' => $scriptProperties['ids']));

        /** @var modResource $resource */
        foreach ($resources as $resource) {
            $resource_id = $resource->get('id');
            $videos = $modx->getIterator('RvgVideos', array('resource_id' => $resource_id));
            /** @var RvgVideos $video */
            foreach ($videos as $video) {
                $video->remove();
            }

            $dir = $rvg->config['thumbsPath'] . $resource_id;
            if(is_dir($dir)) {
                $modx->cacheManager->deleteTree($dir,array('deleteTop' => true));
            }
        }
        break;
}