<?php
/**
 * ResVideoGallery
 * @package resvideogallery
 */

/**
 * @var modX $modx
 * @var array $scriptProperties
 * @var Rvg $rvg
 */

require_once $modx->getOption('resvideogallery.core_path',null,$modx->getOption('core_path').'components/resvideogallery/').'model/resvideogallery/rvg.class.php';
$rvg = new Rvg($modx,$scriptProperties);

if (!is_object($rvg) || !($rvg instanceof Rvg)) return '';

$controller = $rvg->loadController('Add');
$output = $controller->run($scriptProperties);
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    return $output;

} else if(!empty($output)) {
    die($output);
} else {
    return '';
}