<?php
/**
 * @package resvideogallery
 * @subpackage request
 */
abstract class RvgController {
    /** @var modX $modx */
    public $modx;
    /** @var Rvg $rvg */
    public $rvg;
    /** @var array $config */
    public $config = array();
    /** @var array $scriptProperties */
    protected $scriptProperties = array();
    /** @var RvgValidator $validator */
    public $validator;
    /** @var RvgDictionary $dictionary */
    public $dictionary;
    /** @var array $placeholders */
    protected $placeholders = array();

    /**
     * @param Rvg $rvg A reference to the Rvg instance
     * @param array $config
     */
    function __construct(Rvg &$rvg,array $config = array()) {
        $this->rvg =& $rvg;
        $this->modx =& $rvg->modx;
        $this->config = array_merge($this->config,$config);
    }

    public function run($scriptProperties) {
        $this->setProperties($scriptProperties);
        $this->initialize();
        return $this->process();
    }

    abstract public function initialize();
    abstract public function process();

    /**
     * Set the default options for this module
     * @param array $defaults
     * @return void
     */
    protected function setDefaultProperties(array $defaults = array()) {
        $this->scriptProperties = array_merge($defaults,$this->scriptProperties);
    }

    /**
     * Set an option for this module
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setProperty($key,$value) {
        $this->scriptProperties[$key] = $value;
    }
    /**
     * Set an array of options
     * @param array $array
     * @return void
     */
    public function setProperties($array) {
        foreach ($array as $k => $v) {
            $this->setProperty($k,$v);
        }
    }

    /**
     * Return an array of REQUEST options
     * @return array
     */
    public function getProperties() {
        return $this->scriptProperties;
    }

    /**
     * @param $key
     * @param null $default
     * @param string $method
     * @return mixed
     */
    public function getProperty($key,$default = null,$method = '!empty') {
        $v = $default;
        switch ($method) {
            case 'empty':
            case '!empty':
                if (!empty($this->scriptProperties[$key])) {
                    $v = $this->scriptProperties[$key];
                }
                break;
            case 'isset':
            default:
                if (isset($this->scriptProperties[$key])) {
                    $v = $this->scriptProperties[$key];
                }
                break;
        }
        return $v;
    }

    public function setPlaceholder($k,$v) {
        $this->placeholders[$k] = $v;
    }
    public function getPlaceholder($k,$default = null) {
        return isset($this->placeholders[$k]) ? $this->placeholders[$k] : $default;
    }
    public function setPlaceholders($array) {
        foreach ($array as $k => $v) {
            $this->setPlaceholder($k,$v);
        }
    }
    public function getPlaceholders() {
        return $this->placeholders;
    }

    /**
     * @param array $data
     * @param string $hooksName
     * @param array $customProperties
     * @return bool
     */
    public function runHooks($hooksName ='', $customProperties = array()) {
        $success = true;
        if (!empty($hooksName)) {
            $hooks = $this->rvg->loadHooks();
            $hooks->loadMultiple($hooksName,$this->getProperties(),$customProperties);
            if ($hooks->hasErrors()) {
                $success = false;
                $errorMsg = $hooks->getErrorMessage();
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[ResVideoGallery] Error run  ('.$hooksName.'): ' . $errorMsg);
            }

            $values = $hooks->getValues();
            if (!empty($values)) {
                $this->setProperties($values);
            }
        }
        return $success;
    }


    /**
     * Load the Dictionary class
     * @return RvgDictionary
     */
    public function loadDictionary() {
        $classPath = $this->getProperty('dictionaryClassPath',$this->rvg->config['modelPath'].'resvideogallery/');
        $className = $this->getProperty('dictionaryClassName','RvgDictionary');
        if ($this->modx->loadClass($className,$classPath,true,true)) {
            $this->dictionary = new RvgDictionary($this->rvg);
            $this->dictionary->gather();
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Rvg] Could not load RvgDictionary class from ');
        }
        return $this->dictionary;
    }

    /**
     * Loads the RvgValidator class.
     *
     * @access public
     * @param array $config An array of configuration parameters for the
     * RvgValidator class
     * @return RvgValidator An instance of the RvgValidator class.
     */
    public function loadValidator($config = array()) {
        if (!$this->modx->loadClass('RvgValidator',$this->config['modelPath'].'resvideogallery/',true,true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Rvg] Could not load Validator class.');
            return false;
        }
        $this->validator = new RvgValidator($this->rvg,$config);
        return $this->validator;
    }


    /**
     * @param string $processor
     * @return mixed|string
     */
    public function runProcessor($processor) {
        $output = '';
        $processor = $this->loadProcessor($processor);
        if (empty($processor)) return $output;

        return $processor->process();
    }

    /**
     * @param $processor
     * @return bool|RvgProcessor
     */
    public function loadProcessor($processor) {
        $processor = strtolower($processor);
        $processorFile = $this->config['processorsPath'].$processor.'.php';
        if (!file_exists($processorFile)) {
            return false;
        }
        try {
            $explode = explode('/',$processor);
            $processor = $explode[(count($explode) -1)];
            $className = 'Rvg'.ucfirst($processor).'Processor';
            if (!class_exists($className)) {
                $className = include_once $processorFile;
            }
            /** @var RvgProcessor $processor */
            $processor = new $className($this->rvg,$this);
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Rvg] '.$e->getMessage());
        }
        return $processor;
    }

}

/**
 * Abstracts processors into a class
 * @package resvideogallery
 */
abstract class RvgProcessor {
    /** @var Rvg $rvg */
    public $rvg;
    /** @var RvgController $controller */
    public $controller;
    /** @var RvgDictionary $dictionary */
    public $dictionary;
    /** @var array $config */
    public $config = array();

    /**
     * @param Rvg &$rvg A reference to the Rvg instance
     * @param RvgController &$controller
     * @param array $config
     */
    function __construct(Rvg &$rvg,RvgController &$controller,array $config = array()) {
        $this->rvg =& $rvg;
        $this->modx =& $rvg->modx;
        $this->controller =& $controller;
        $this->dictionary =& $controller->dictionary;
        $this->config = array_merge($this->config,$config);
    }

    abstract function process();
}