<?php
header('Content-Type: application/json; charset=UTF-8');
define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';

$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');

/** @var Rvg $rvg */
$rvg = $modx->getService('resvideogallery','Rvg',$modx->getOption('resvideogallery.core_path',null, $modx->getOption('core_path').'components/resvideogallery/').'model/resvideogallery/', array());

if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    $modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
}
elseif (!empty($_REQUEST['controller'])) {
        $out = array('result'=>false);
        $dataType = isset($_REQUEST['data_type']) ? $_REQUEST['data_type'] : 'json';
        if($controller = $rvg->loadController(ucfirst($_REQUEST['controller']))){
            unset($_REQUEST['controller']);
            $out =  $controller->run($_REQUEST);
        } else {
            $out['error'] = array('controller'=>'invalid load controller');
        }
        echo $dataType == 'json'  ? $modx->toJSON($out) : $out;
}
@session_write_close();

/**
 * Example Ajax request
 *
 *
    $.ajax({
        dataType: 'json',
        type: 'POST',
        cache: false,
        url: '/assets/components/resvideogallery/action.php',
        data: {
            controller: 'controller_name',  //required
            action: 'create',               //required; default: create|update|data|list|remove
            name: 'my_name',
            email:'my_email'
        },
        success: function (e) {
            if (e.result == true) {
                console.log(e.data);
            } else {
                for(var key in e.error){
                    console.log(e.error[key]);
                }
            }
        },
        error: function (e) {
            console.log('error',e);
        }
    });
 *
 *
 */