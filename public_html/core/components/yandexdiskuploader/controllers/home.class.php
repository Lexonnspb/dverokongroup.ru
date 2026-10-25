<?php

class YandexDiskUploaderHomeManagerController extends modExtraManagerController
{
    /** @var YandexDiskUploader $yandexdiskuploader */
    public $yandexdiskuploader;

    /**
     *
     */
    public function initialize()
    {
        $path = MODX_CORE_PATH . 'components/yandexdiskuploader/model/yandexdiskuploader/';
        $this->yandexdiskuploader = $this->modx->getService('yandexdiskuploader', 'YandexDiskUploader', $path);

        parent::initialize();
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('yandexdiskuploader:default');
    }

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }

    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('yandexdiskuploader');
    }

    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->yandexdiskuploader->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->yandexdiskuploader->config['cssUrl'] . 'mgr/bootstrap.buttons.css');

        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/yandexdiskuploader.js');

        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/misc/ux.js');
        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/misc/combo.js');

        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/misc/default.window.js');

        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/widgets/objects.grid.js');
        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/widgets/objects.window.js');

        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->yandexdiskuploader->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('
            <script type="text/javascript">
                YandexDiskUploader.config = ' . json_encode($this->yandexdiskuploader->config) . ';
                YandexDiskUploader.config[\'connector_url\'] = "' . $this->yandexdiskuploader->config['connectorUrl'] . '";
                Ext.onReady(function() {
                    MODx.load({
                        xtype: "yandexdiskuploader-page-home",
                    });
                });
            </script>
        ');
    }

    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->yandexdiskuploader->config['templatesPath'] . 'home.tpl';
    }
}