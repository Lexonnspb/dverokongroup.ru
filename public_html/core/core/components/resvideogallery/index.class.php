<?php
/**
 * @package resvideogallery
 * @subpackage controllers
 */
require_once dirname(__FILE__) . '/model/resvideogallery/rvg.class.php';

class IndexManagerController extends modExtraManagerController {
    public static function getDefaultController() { return 'index'; }
}


abstract class RvgMainController extends modExtraManagerController {
    /** @var Rvg $rvg */
    public $rvg;
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $action = call_user_func(array($className,'getDefaultController'));
        if (isset($_REQUEST['a'])) {
            $action = str_replace(array('../','./','.','-','@'),'',$_REQUEST['a']);
        }
        $className = self::getControllerClassName($action,$config['namespace']);
        $classPath = $config['namespace_path'].'controllers/default/'.$action.'.class.php';
        require_once $classPath;
        /** @var modManagerController $controller */
        $controller = new $className($modx,$config);
        return $controller;
    }
    public function initialize() {
        $this->rvg = new Rvg($this->modx);
        $this->addJavascript($this->rvg->config['jsUrl'].'mgr/rvg.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Rvg.config = '.$this->modx->toJSON($this->rvg->config).';
        });
        </script>');
        return parent::initialize();
    }

    public function getLanguageTopics() {
        return array('resvideogallery:default');
    }

    public function checkPermissions() { return true;}
}