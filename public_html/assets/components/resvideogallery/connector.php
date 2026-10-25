<?php
/**
 * ResVideoGallery Connector
 * @package resvideogallery
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('resvideogallery.core_path',null,$modx->getOption('core_path').'components/resvideogallery/');
require_once $corePath.'model/resvideogallery/rvg.class.php';
$modx->rvg = new Rvg($modx);

$modx->lexicon->load('resvideogallery:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->rvg->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
