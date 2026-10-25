<?php
/**
 * @package resvideogallery
 */

/**
 * Built-In Validators
 *
 * name                 function	                                parameter	                                    example
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * blank                Is field blank?		                                                                        nospam|blank
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * required             Is field is not empty?		                                                                username|required
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * password_confirm	    Does field match value of other field?	    The name of the password field	                password2|password_confirm=password
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * email                Is a valid email address?                                                                   emailaddr|email
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * minLength            Is field at least X characters long?        The min length.                                 password|minLength=6
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * maxLength            Is field no more than X characters long?    The max length.                                 password|maxLength=12
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * minValue             Is field at least X?                        The minimum value.                              donation|minValue=1
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * maxValue             Is field no higher than X?                  The maximum value.                              cost|maxValue=1200
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * contains             Does field contain string X?                The string X.                                   title|contains=Hello
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * strip                Strip a certain string from the field.      The string to strip.                            message|strip=badword
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * stripTags            Strip all tags from the field.              An optional list of allowed tags.               message|stripTags
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 *  allowTags           Note that this is on by default.
 *                      Allow tags in the field.                                                                    content|allowTags
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * isNumber             Is the field a numeric value?                                                               cost|isNumber
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * isDate               Is the field a date?                        An optional format to format the date.          startDate|isDate=%Y-%m-%d
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * regexp               Does field match an expected format?        A valid regular expression to match against.    secretPin|regexp=/[0-9]{4}/
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 */

class ResVideoGalleryAddController extends RvgController {
    /** @var boolean $hasPosted */
    public $hasPosted = false;

    public function initialize() {
        $this->modx->lexicon->load('resvideogallery:video');
        $this->setDefaultProperties(array(
            'tpl' => 'resVideoGalleryFormTpl',
            'js'=> 'form.min.js',
            'css'=> 'form.min.css',
            'onlyAuth'=> 1,
            'active' => 1,
            'multiple' => 1,
            'tags' => '',
            'usergroups' => '',
            'resource' => $this->modx->resource->get('id'),
            'validate' => 'url|required',
            'allowTags' => '',
        ));
    }

    public function process() {
        $out = array();
        $this->loadDictionary();
        $this->loadValidator();
        $this->checkForPost();

        if (!$this->hasPosted) {
            if($this->isPermission()) {
                $jquery = filter_var($this->modx->getOption('resvideogallery.jquery', null, 0), FILTER_VALIDATE_BOOLEAN);
                if ($jquery) $this->modx->regClientScript($this->config['jsUrl'] . 'web/lib/jquery-2.2.4.min.js');
                if ($this->getProperty('js'))
                    $this->modx->regClientScript($this->rvg->config['jsUrl'] . 'web/' . $this->getProperty('js'));
                if ($this->getProperty('css'))
                    $this->modx->regClientCSS($this->rvg->config['cssUrl'] . 'web/' . $this->getProperty('css'));
                return $this->rvg->getChunk($this->getProperty('tpl'), array(
                    'multiple'=> $this->getProperty('multiple')
                ));
            } else {
                return '';
            }
        }

        switch($this->dictionary->get('action','')){
            case 'scrape':
                $videoData = $this->rvg->scrapeVideo($this->dictionary->get('url',''));
                if($videoData === false) {
                    $this->validator->addError('scrape', $this->modx->lexicon('video.err.scrape'));
                } else {
                    $out['video'] =  array (
                        'title'=>$videoData['title'],
                        'description'=>$videoData['description'],
                        'duration'=>  $this->rvg->prepareDuration($videoData['duration']),
                        'tags'=>$videoData['tags'],
                        'thumb'=>$videoData['primary_thumb'],
                    );
                }
                break;
            case 'add':
                if($this->validate()) {
                    if($this->runProcessor('web/video/add')) {
                        $out['success'] = $this->modx->lexicon('video.success');
                    } else {
                        $this->validator->addError('notice', $this->modx->lexicon('video.err.save'));
                    }
                }
                break;
            default:
                return '';
        }

        if($this->validator->hasErrors()){
            $out['error'] = $this->validator->getRawErrors();
        }
        $out['result'] = !empty($out['error']) ? false : true;
        return  $this->modx->toJSON($out);
    }


    /**
     * @return bool
     */

    private function validate() {
        $fields = $this->validator->validateFields($this->dictionary,$this->getProperty('validate',''));
        if (!$this->validator->hasErrors()) {
            $resourceId = $this->getProperty('resource', 0);
            if (!$resource = $this->modx->getObject('modResource', $resourceId)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[resVideoGallery]' . $this->modx->lexicon('video.err.nf_resource') . ' ID '.$resourceId);
                $this->validator->addError('notice', $this->modx->lexicon('video.err.nf_resource'));
                return false;
            }
            $this->dictionary->fromArray($fields);
            $tags = array_map('trim', explode(',',$this->getProperty('tags','')));
            $this->setProperty('tags',  array_diff($tags, array('')));
            return $this->isPermission();
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isPermission() {
        if($this->getProperty('onlyAuth',1) && !$this->modx->user->isAuthenticated('web'))
            return false;
        if($this->getProperty('usergroups','')) {
            $usergroups = array_map('trim', explode(',',$this->getProperty('usergroups','')));
            return $this->modx->user->isMember($usergroups);
        }
        return true;
    }


    /**
     * Check for a POST submission
     * @return void
     */
    private function checkForPost() {
        $this->hasPosted = !empty($_POST) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }
}
return 'ResVideoGalleryAddController';