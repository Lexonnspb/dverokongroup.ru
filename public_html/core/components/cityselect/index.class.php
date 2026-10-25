<?php

require_once dirname(__FILE__) . '/model/cityselect/cityselect.class.php';
/**
 * Class cityselectMainController
 */
abstract class cityselectMainController extends modExtraManagerController {
	/** @var cityselect $cityselect */
	public $cityselect;


	/**
	 * @return void
	 */
	public function initialize() {
		$this->cityselect = new cityselect($this->modx);

		$this->modx->regClientCSS($this->cityselect->config['cssUrl'] . 'mgr/main.css');
		$this->modx->regClientStartupScript($this->cityselect->config['jsUrl'] . 'mgr/cityselect.js');
		$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
		Ext.onReady(function() {
			cityselect.config = ' . $this->modx->toJSON($this->cityselect->config) . ';
			cityselect.config.connector_url = "' . $this->cityselect->config['connectorUrl'] . '";
		});
		</script>');

		parent::initialize();
	}

	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('cityselect:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends cityselectMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}