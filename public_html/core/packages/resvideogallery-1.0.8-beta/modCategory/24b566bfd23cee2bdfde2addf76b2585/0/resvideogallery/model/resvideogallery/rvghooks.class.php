<?php
/**
 * Base Hooks handling class.
 *
 * Hooks can be either a predefined list by ResVideoGallery, or custom MODX Snippets. They can also be chained to allow for
 * order-of-execution processing. Returning false from a Hook will end the chain.
 *
 * @package resvideogallery
 */
class RvgHooks {
    /**
     * A collection of all the processed errors so far.
     * @var array $errors
     * @access public
     */
    public $errors = array();
    /**
     * A collection of all the processed hooks so far.
     * @var array $hooks
     * @access public
     */
    public $hooks = array();
    /**
     * A reference to the modX instance.
     * @var modX $modx
     * @access public
     */
    public $modx = null;
    /**
     * A reference to the Rvg instance.
     * @var Rvg $rvg
     * @access public
     */
    public $rvg = null;

    /**
     * The current stored and parsed fields for the Rvg call.
     * @var array $fields
     */
    public $fields = array();

    /**
     * The type of Hook request that this represents
     * @var string $type
     */
    public $type;

    /**
     * The constructor for the rvgHooks class
     *
     * @param Rvg &$rvg A reference to the Rvg class instance.
     * @param array $config Optional. An array of configuration parameters.
     * @param string $type The type of hooks this service class is loading
     * @return rvgHooks
     */
    function __construct(Rvg &$rvg,array $config = array(),$type = '') {
        $this->rvg =& $rvg;
        $this->modx =& $rvg->modx;
        $this->config = array_merge(array(
        ),$config);
        $this->type = $type;
    }

    /**
     * Loads an array of hooks. If one fails, will not proceed.
     *
     * @access public
     * @param array $hooks The hooks to run.
     * @param array $fields The fields and values of the form
     * @param array $customProperties An array of extra properties to send to the hook
     * @return array An array of field name => value pairs.
     */
    public function loadMultiple($hooks,array $fields = array(),array $customProperties = array()) {
        if (empty($hooks)) return array();
        if (is_string($hooks)) $hooks = explode(',',$hooks);

        $this->hooks = array();
        $this->fields =& $fields;
        foreach ($hooks as $hook) {
            $hook = trim($hook);
            $success = $this->load($hook,$this->fields,$customProperties);
            if (!$success) return $this->hooks;
            /* dont proceed if hook fails */
        }
        return $this->hooks;
    }

    /**
     * Load a hook. Stores any errors for the hook to $this->errors.
     *
     * @access public
     * @param string $hookName The name of the hook. May be a Snippet name.
     * @param array $fields The fields and values of the form.
     * @param array $customProperties Any other custom properties to load into a custom hook.
     * @return boolean True if hook was successful.
     */
    public function load($hookName,array $fields = array(),array $customProperties = array()) {
        $success = false;
        if (!empty($fields)) $this->fields =& $fields;
        $this->hooks[] = $hookName;

        $reserved = array('load','_process','__construct','getErrorMessage');
        if (method_exists($this,$hookName) && !in_array($hookName,$reserved)) {
            /* built-in hooks */
            $success = $this->$hookName($this->fields);

        /** @var modSnippet $snippet */
        } else if ($snippet = $this->modx->getObject('modSnippet',array('name' => $hookName))) {
            /* custom snippet hook */
            $properties = array_merge($this->rvg->config,$customProperties);
            $properties['rvg'] =& $this->rvg;
            $properties['hook'] =& $this;
            $properties['fields'] = $this->fields;
            $properties['errors'] =& $this->errors;
            $success = $snippet->process($properties);
        } else {
            /* search for a file-based hook */
            $this->modx->parser->processElementTags('',$hookName,true,true);
            if (file_exists($hookName)) {
                $success = $this->_loadFileBasedHook($hookName,$customProperties);
            } else {
                /* no hook found */
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[ResVideoGallery] Could not find hook "'.$hookName.'".');
                $success = true;
            }
        }

        if (is_array($success) && !empty($success)) {
            $this->errors = array_merge($this->errors,$success);
            $success = false;
        } else if ($success != true) {
            if (!isset($this->errors[$hookName])) $this->errors[$hookName] = '';
            $this->errors[$hookName] .= ' '.$success;
            $success = false;
        }
        return $success;
    }

    /**
     * Attempt to load a file-based hook given a name
     * @param string $path The absolute path of the hook file
     * @param array $customProperties An array of custom properties to run with the hook
     * @return boolean True if the hook succeeded
     */
    private function _loadFileBasedHook($path,array $customProperties = array()) {
        $scriptProperties = array_merge($this->rvg->config,$customProperties);
        $rvg =& $this->rvg;
        $hook =& $this;
        $fields = $this->fields;
        $errors =& $this->errors;
        $modx =& $this->modx;
        $success = false;
        try {
            $success = include $path;
        } catch (Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[ResVideoGallery] '.$e->getMessage());
        }
        return $success;
    }

    /**
     * Gets the error messages compiled into a single string.
     *
     * @access public
     * @param string $delim The delimiter between each message.
     * @return string The concatenated error message
     */
    public function getErrorMessage($delim = "\n") {
        return implode($delim,$this->errors);
    }

    /**
     * Adds an error to the stack.
     *
     * @access private
     * @param string $key The field to add the error to.
     * @param string $value The error message.
     * @return string The added error message with the error wrapper.
     */
    public function addError($key,$value) {
        if (!isset($this->errors[$key])) $this->errors[$key] = '';
        $this->errors[$key] .= $value;
        return $this->errors[$key];
    }

    /**
     * See if there are any errors in the stack.
     *
     * @return boolean
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Get all errors for this current request
     * 
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Sets the value of a field.
     *
     * @param string $key The field name to set.
     * @param mixed $value The value to set to the field.
     * @return mixed The set value.
     */
    public function setValue($key,$value) {
        $this->fields[$key] = $value;
        return $this->fields[$key];
    }

    /**
     * Sets an associative array of field name and values.
     *
     * @param array $values A key/name pair of fields and values to set.
     */
    public function setValues(array $values = array()) {
        foreach ($values as $key => $value) {
            $this->setValue($key,$value);
        }
    }

    /**
     * Gets the value of a field.
     *
     * @param string $key The field name to get.
     * @return mixed The value of the key, or null if non-existent.
     */
    public function getValue($key) {
        if (array_key_exists($key,$this->fields)) {
            return $this->fields[$key];
        }
        return null;
    }

    /**
     * Gets an associative array of field name and values.
     *
     * @return array $values A key/name pair of fields and values.
     */
    public function getValues() {
        return $this->fields;
    }

    /**
     * Processes string and sets placeholders
     *
     * @param string $str The string to process
     * @param array $placeholders An array of placeholders to replace with values
     * @return string The parsed string
     */
    public function _process($str,array $placeholders = array()) {
        if (is_string($str)) {
            foreach ($placeholders as $k => $v) {
                if (is_scalar($k) && is_scalar($v)) {
                    $str = str_replace('[[+'.$k.']]',$v,$str);
                }
            }
        }
        $this->modx->parser->processElementTags('',$str,true,false);
        return $str;
    }


}
