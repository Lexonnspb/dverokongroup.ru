<?php

/**
 * MODx Rvg Class
 *
 * @package resvideogallery
 */
class Rvg
{

    /** @var RvgController $controller */
    public $controller;
    private $useUploadTempDirectory = false;
    private $providers = array();
    /* @var ms2Gallery $ms2gallery */
    private $ms2Gallery = null;


    /**
     * Creates an instance of the Rvg class.
     *
     * @param modX &$modx A reference to the modX instance.
     * @param array $config An array of configuration parameters.
     * @return Rvg
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;
        $this->modx->lexicon->load('resvideogallery:default');
        $corePath = $modx->getOption('resvideogallery.core_path', $config, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/resvideogallery/');
        $assetsUrl = $modx->getOption('resvideogallery.assets_url', $config, $modx->getOption('assets_url') . 'components/resvideogallery/');
        $assetsPath = $modx->getOption('resvideogallery.assets_path', $config, $modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/resvideogallery/');
        $this->config = array_merge(array(
            'chunksPath' => $corePath . 'elements/chunks/',
            'controllersPath' => $corePath . 'controllers/',
            'corePath' => $corePath,
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'cachePath' => $assetsPath . 'cache/',
            'processorsPath' => $corePath . 'processors/',
            'providersPath' => $corePath . 'model/resvideogallery/providers/',
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'templatesPath' => $corePath . 'elements/templates/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'themePath' => $assetsPath . 'theme/',
            'themeUrl' => $assetsUrl . 'theme/',
            'actionUrl' => $assetsUrl . 'action.php',
            'managerUrl' => $this->modx->config['manager_url'],
            'thumbsPath' => $this->preparePath($this->modx->getOption('resvideogallery.thumbs_path', null, '{assets_path}resvideogallery/')),
            'thumbsUrl' => $this->preparePath($this->modx->getOption('resvideogallery.thumbs_url', null, '{assets_url}resvideogallery/')),
            'pageSize' => $this->modx->getOption('resvideogallery.page_size', null, 20),
            'date_format' => $this->modx->getOption('resvideogallery.date_format', null, '%d.%m.%Y %H:%M'),
            'modelPathMs2g' => MODX_CORE_PATH . 'components/ms2gallery/model/',
        ), $config);

        $this->modx->addPackage('resvideogallery', $this->config['modelPath']);

        if (file_exists($this->config['modelPathMs2g']))
            $this->modx->addPackage('ms2gallery', $this->config['modelPathMs2g']);
        //$this->ms2Gallery = $this->modx->getService('ms2gallery', 'ms2Gallery', $this->config['modelPathMs2g']);

    }

    /**
     * Load the appropriate controller
     * @param string $controller
     * @return null|RvgController
     */
    public function loadController($controller)
    {
        if ($this->modx->loadClass('RvgController', $this->config['modelPath'] . 'resvideogallery/', true, true)) {
            $classPath = $this->config['controllersPath'] . 'web/' . mb_strtolower($controller) . '.php';
            $className = 'ResVideoGallery' . $controller . 'Controller';
            if (file_exists($classPath)) {
                if (!class_exists($className)) {
                    $className = require_once $classPath;
                }
                if (class_exists($className)) {
                    $this->controller = new $className($this, $this->config);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not load controller: ' . $className . ' at ' . $classPath);
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not load controller file: ' . $classPath);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not load RvgController class.');
        }
        return $this->controller;
    }


    /**
     * Loads the Validator class.
     *
     * @access public
     * @param string $type The name to give the service on the rvg object
     * @param array $config An array of configuration parameters for the
     * RvgValidator class
     * @return RvgValidator An instance of the RvgValidator class.
     */
    public function loadValidator($type = 'validator', $config = array())
    {
        if (!$this->modx->loadClass('RvgValidator', $this->config['modelPath'], true, true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not load Validator class.');
            return false;
        }
        $this->$type = new RvgValidator($this, $config);
        return $this->$type;
    }

    /**
     * Helper function to get a chunk or tpl by different methods.
     *
     * @access public
     * @param string $name The name of the tpl/chunk.
     * @param array $properties The properties to use for the tpl/chunk.
     * @param string $type The type of tpl/chunk. Can be embedded,
     * modChunk, file, or inline. Defaults to modChunk.
     * @return string The processed tpl/chunk.
     */
    public function getChunk($name, $properties, $type = 'modChunk')
    {
        $output = '';
        switch ($type) {
            case 'embedded':
                if (!$this->modx->user->isAuthenticated($this->modx->context->get('key'))) {
                    $this->modx->setPlaceholders($properties);
                }
                break;
            case 'modChunk':
                $output .= $this->modx->getChunk($name, $properties);
                break;
            case 'file':
                $name = str_replace(array(
                    '{base_path}',
                    '{assets_path}',
                    '{core_path}',
                ), array(
                    $this->modx->getOption('base_path'),
                    $this->modx->getOption('assets_path'),
                    $this->modx->getOption('core_path'),
                ), $name);
                $output .= file_get_contents($name);
                $this->modx->setPlaceholders($properties);
                break;
            case 'inline':
            default:
                /* default is inline, meaning the tpl content was provided directly in the property */
                $chunk = $this->modx->newObject('modChunk');
                $chunk->setContent($name);
                $chunk->setCacheable(false);
                $output .= $chunk->process($properties);
                break;
        }
        return $output;
    }

    /**
     * @param modManagerController $controller
     * @param modResource $resource
     */
    public function loadVideoManagerFiles(modManagerController $controller, modResource $resource)
    {
        $modx23 = (int)$this->systemVersion();
        $jsUrl = $this->config['jsUrl'] . 'mgr/';
        $cssUrl = $this->config['cssUrl'] . 'mgr/';

        $controller->addLexiconTopic('resvideogallery:default');
        $controller->addJavascript($jsUrl . 'rvg.js');
        $controller->addLastJavascript($jsUrl . 'utils/rvg.utils.js');
        $controller->addLastJavascript($jsUrl . 'utils/ext.ddview.js');
        $controller->addLastJavascript($jsUrl . 'widgets/rvg.combo.js');
        $controller->addLastJavascript($jsUrl . 'widgets/rvg.view.js');
        $controller->addLastJavascript($jsUrl . 'widgets/rvg.window.js');
        $controller->addLastJavascript($jsUrl . 'widgets/rvg.toolbar.js');
        $controller->addLastJavascript($jsUrl . 'widgets/rvg.panel.js');

        $controller->addCss($cssUrl . 'main.css');
        if (!$modx23) {
            $controller->addCss($cssUrl . 'font-awesome.min.css');
        }

        $controller->addHtml('
		<script type="text/javascript">
			MODx.modx23 = ' . $modx23 . ';
			Rvg.config = ' . $this->modx->toJSON($this->config) . ';
		</script>');

        $insertTab = '
				tabs.add({
					xtype: "resvideogallery-page",
					id: "resvideogallery-page",
					title: _("resvideogallery"),
					record: {
						id: ' . $resource->get('id') . '
					}
				});
			';
        $controller->addHtml('
				<script type="text/javascript">
					Ext.ComponentMgr.onAvailable("modx-resource-tabs", function() {
						var tabs = this;
						tabs.on("beforerender", function() {
							' . $insertTab . '
						});
					});
				</script>');
    }

    /**
     * Accurate sorting of resource videos
     *
     * @param $resource_id
     */
    public function rankResourceVideos($resource_id)
    {
        if (!$this->modx->getOption('resvideogallery.exact_sorting', null, true, true)) {
            return;
        }

        $q = $this->modx->newQuery('RvgVideos', array('resource_id' => $resource_id));
        $q->select('id');
        $q->sortby('rank ASC, createdon', 'ASC');

        if ($q->prepare() && $q->stmt->execute()) {
            $sql = '';
            $table = $this->modx->getTableName('RvgVideos');
            if ($ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
                foreach ($ids as $k => $id) {
                    $sql .= "UPDATE {$table} SET `rank` = '{$k}' WHERE (`id` = {$id});";
                }
            }
            $sql .= "ALTER TABLE {$table} ORDER BY `rank` ASC;";
            $this->modx->exec($sql);
        }
    }

    /**
     * Compares MODX version
     *
     * @param string $version
     * @param string $dir
     *
     * @return bool
     */
    public function systemVersion($version = '2.3.0', $dir = '>=')
    {
        $this->modx->getVersionData();

        return !empty($this->modx->version) && version_compare($this->modx->version['full_version'], $version, $dir);
    }

    /**
     * @return null|string
     */
    public function getSysTempDir()
    {
        if ($this->useUploadTempDirectory) {
            //  use upload-directory when defined to allow running on environments having very restricted
            //      open_basedir configs
            if (ini_get('upload_tmp_dir') !== FALSE) {
                if ($temp = ini_get('upload_tmp_dir')) {
                    if (file_exists($temp))
                        return realpath($temp);
                }
            }
        }

        // sys_get_temp_dir is only available since PHP 5.2.1
        // http://php.net/manual/en/function.sys-get-temp-dir.php#94119
        if (!function_exists('sys_get_temp_dir')) {
            if ($temp = getenv('TMP')) {
                if ((!empty($temp)) && (file_exists($temp))) {
                    return realpath($temp);
                }
            }
            if ($temp = getenv('TEMP')) {
                if ((!empty($temp)) && (file_exists($temp))) {
                    return realpath($temp);
                }
            }
            if ($temp = getenv('TMPDIR')) {
                if ((!empty($temp)) && (file_exists($temp))) {
                    return realpath($temp);
                }
            }

            // trick for creating a file in system's temporary dir
            // without knowing the path of the system's temporary dir
            $temp = tempnam(__FILE__, '');
            if (file_exists($temp)) {
                unlink($temp);
                return realpath(dirname($temp));
            }

            return null;
        }
        // use ordinary built-in PHP function
        //	There should be no problem with the 5.2.4 Suhosin realpath() bug, because this line should only
        //		be called if we're running 5.2.1 or earlier
        return realpath(sys_get_temp_dir());
    }

    /**
     * @param string $path
     * @return string
     */
    public function preparePath($path)
    {
        $path = str_replace(array(
            '{assets_path}',
            '{assets_url}',
            '{base_path}',
            '{core_path}',
        ), array(
            $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH),
            $this->modx->getOption('assets_url', null, MODX_ASSETS_URL),
            $this->modx->getOption('base_path', null, MODX_BASE_PATH),
            $this->modx->getOption('core_path', null, MODX_CORE_PATH),
        ), $path);
        return $path;
    }

    /**
     * @param string $theme
     * @param string $postfix
     */
    public function regHheme($theme, $postfix = '.min')
    {
        if (!empty($theme)) {
            if (file_exists($this->config['themePath'] . $theme . '/js/main' . $postfix . '.js')) {
                $this->modx->regClientScript($this->config['themeUrl'] . $theme . '/js/main' . $postfix . '.js');
            }
            if (file_exists($this->config['themePath'] . $theme . '/css/main' . $postfix . '.css')) {
                $this->modx->regClientCSS($this->config['themeUrl'] . $theme . '/css/main' . $postfix . '.css');
            }
        }
    }


    /**
     * @param string $url
     * @param bool $clearCache
     * @param string $path
     * @return bool|string
     */
    public function loadImageFromUrl($url, $clearCache = true, $path = '')
    {
        if (!is_writable($this->config['cachePath'])) {
            if (!$this->modx->cacheManager->writeTree($this->config['cachePath'])) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Cache dir not writable: ' . $this->config['cachePath']);
                return '';
            }
        }
        $path = empty($path) ? $this->config['cachePath'] : $path;
        if ($clearCache) $this->clearCache($path);
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $filename = $path . md5($url . microtime(true)) . '.' . $ext;
        if ($image = file_get_contents($url)) {
            if (file_put_contents($filename, $image)) {
                return $filename;
            }
        }
        return false;
    }

    /**
     * @param $str
     * @return int
     */
    public function isUrl($str)
    {
        return preg_match('/^http|https:\/\//', $str);
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        if (empty($this->providers)) {
            $this->loadProviders();
        }
        return $this->providers;
    }

    /**
     * @param string $name
     * @return RvgProvider|null
     */

    public function getProviderByName($name)
    {
        if (empty($this->providers)) {
            $this->loadProviders();
        }
        if (empty($this->providers)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not load roviders');
        }
        if (isset($this->providers[$name]) && !empty($this->providers[$name])) {
            return $this->providers[$name];
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not find provider by name: ' . $name);
        }
        return null;
    }

    /**
     * @param bool $onlyAllowCheckBlocked
     * @return array
     */
    public function getProvidersName($onlyAllowCheckBlocked = true)
    {
        $names = array();
        if ($providers = $this->getProviders()) {
            foreach ($providers as $provider) {
                if ($onlyAllowCheckBlocked) {
                    if ($provider->hasAllowCheckBlocked()) {
                        $names[] = $provider::NAME;
                    }
                } else {
                    $names[] = $provider::NAME;
                }
            }
        }
        return $names;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function scrapeVideo($url)
    {
        if ($providers = $this->getProviders()) {
            foreach ($providers as $provider) {
                if ($provider->checkUrl($url)) {
                    return $provider->scrape();
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not find a single providers');
        }
        return false;
    }

    /**
     * @param int $duration
     * @return string
     */
    public function prepareDuration($duration)
    {
        if (gmdate('G', $duration)) {
            return gmdate("H:i:s", $duration);
        } else {
            return gmdate("i:s", $duration);
        }
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->config['thumbsPath'];
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->config['thumbsUrl'];
    }


    /**
     * @param array $video
     * @param bool $autoPlay
     * @param string $w
     * @param string $h
     * @return string
     */
    public function getEmbedCode($video, $autoPlay = true, $w = '100%', $h = '100%')
    {
        if ($provider = $this->getProviderByName($video['provider'])) {
            return $provider->getEmbedCode($video, $autoPlay, $w, $h);
        }
        return '';
    }


    /**
     * @param bool $ms2g
     * @return xPDOQuery
     */
    public function getQueryTags($ms2g = false)
    {
        if ($ms2g) {
            $q = $this->modx->newQuery('msResourceFile');
            $q->innerJoin('msResourceFile', 'Photo', 'Photo.resource_id = msResourceFile.resource_id AND Photo.parent=0');
            $q->innerJoin('msResourceFileTag', 'Tag', 'Photo.id = Tag.file_id');
            $q->select(array(
                'Tag.tag, Tag.file_id as media_id , msResourceFile.resource_id'
            ));
        } else {
            $q = $this->modx->newQuery('RvgVideos');
            $q->innerJoin('RvgVideosTags', 'Tag', 'RvgVideos.id = Tag.video_id');
            $q->select(array(
                'Tag.tag, Tag.video_id as media_id , RvgVideos.resource_id'
            ));
        }
        return $q;
    }

    /**
     * @param xPDOQuery $q
     * @return array
     */
    public function executeQueryTags(xPDOQuery $q)
    {
        if ($q->prepare() && $q->stmt->execute()) {
            if ($tags = $q->stmt->fetchAll(PDO::FETCH_ASSOC)) {
                return $tags;
            }
        }
        return array();
    }

    /**
     * @param int $id
     * @param bool $ms2g
     * @return array
     */
    public function getTagsById($id = 0, $ms2g = false)
    {
        $q = $this->getQueryTags($ms2g);
        $q->where(array('id:=' => $id));
        if ($q->prepare() && $q->stmt->execute()) {
            return $q->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return array();
    }

    /**
     * @param xPDOQuery|null $query
     * @param array $data
     * @param bool $ms2g
     * @return string
     */
    public function renderQuery(xPDOQuery $query = null, $data = array(), $ms2g = false)
    {
        $output = '';
        if ($query) {
            if ($query->prepare() && $query->stmt->execute()) {

                foreach ($query->stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    if ($ms2g) {
                        $properties = $this->modx->fromJSON($row['properties']);
                        $row['isVideo'] = 0;
                        $row['title'] = $row['alt'];
                        $row['width'] = $properties['width'];
                        $row['height'] = $properties['height'];
                        $row['theme'] = $data['theme'];
                        $row['thumb'] = $row['url'];
                        $row['url'] = str_replace($properties['width'] . 'x' . $properties['height'] . '/', '', $row['url']);
                    } else {
                        $row['isVideo'] = 1;
                        $row['thumb'] = $this->getBaseUrl() . $row['thumb'];
                        $row['embed'] = $this->getEmbedCode($row);
                        $row['theme'] = $data['theme'];
                        $row['width'] = $data['width'];
                        $row['height'] = $data['height'];
                        $row['duration'] = $this->prepareDuration($row['duration']);
                    }
                    $row['tags'] = '';
                    if ($data['includeTags'] && !empty($data['tplTag'])) {
                        if ($tags = $this->getTagsById($row['id'], $ms2g)) {
                            foreach ($tags as $idx => $tag) {
                                if (!empty($tag['tag'])) {
                                    $tag['active'] = 0;
                                    $tag['idx'] = $idx;
                                    $row['tags'] .= $this->getChunk($data['tplTag'], $tag);
                                }
                            }
                        }
                    }
                    $output .= $this->getChunk($data['tplRow'], $row);
                }
            }

        }
        return $output;
    }

    /**
     * Loads the Hooks class.
     *
     * @access public
     * @param array $config array An array of configuration parameters for the
     * hooks class
     * @return rvgHooks An instance of the rvgHooks class.
     */
    public function loadHooks($config = array())
    {
        if (!$this->modx->loadClass('resvideogallery.RvgHooks', $this->config['modelPath'], true, true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Could not load Hooks class.');
            return false;
        }
        return new RvgHooks($this, $config);
    }

    /**
     * @param modProcessor $processor
     * @param string $hooksName
     * @param array $customProperties
     * @return bool
     */
    public function runHooks(modProcessor &$processor, $hooksName = '', $customProperties = array())
    {
        $success = true;
        $p = &$processor;
        if (!empty($hooksName)) {
            $hooks = $this->loadHooks();
            $hooks->loadMultiple($hooksName, $p->getProperties(), $customProperties);
            if ($hooks->hasErrors()) {
                $success = false;
                $errorMsg = $hooks->getErrorMessage();
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Error run  (' . $hooksName . '): ' . $errorMsg);
            }
            $values = $hooks->getValues();
            if (!empty($values)) {
                $p->setProperties($values);
            }
        }
        return $success;
    }


    /**
     * @param string $path
     */
    private function clearCache($path = '')
    {
        $path = empty($path) ? $this->config['cachePath'] : $path;
        if (file_exists($path)) {
            $files = glob($path . '*');
            if (!empty($files)) {
                foreach ($files as $file) {
                    unlink($file);
                }
            }
        }
    }

    private function loadProviders()
    {
        if (empty($this->providers)) {
            foreach (glob($this->config['providersPath'] . '*.class.php') as $classPath) {
                if (!file_exists($classPath)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] not find file provider : ' . $classPath);
                    continue;
                }
                try {
                    $explode = explode('/', $classPath);
                    $className = preg_replace(array('/rvg/', '/provider.class.php/'), '', $explode[(count($explode) - 1)]);
                    $className = 'Rvg' . ucfirst($className) . 'Provider';

                    if (!class_exists($className)) {
                        $className = require_once $classPath;
                    }

                    $provider = new $className($this);
                    $this->providers[$provider::NAME] = $provider;
                } catch (Exception $e) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] ' . $e->getMessage());
                }
            }
        }
    }
}