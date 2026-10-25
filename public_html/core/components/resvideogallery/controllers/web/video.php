<?php
/**
 * @package resvideogallery
 */

/**
 * Built-In Validators
 *
 * name                 function                                    parameter                                        example
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * blank                Is field blank?                                                                                nospam|blank
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * required             Is field is not empty?                                                                        username|required
 * --------------------+-------------------------------------------+-----------------------------------------------+--------------------------------------
 * password_confirm        Does field match value of other field?        The name of the password field                    password2|password_confirm=password
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
class ResVideoGalleryVideoController extends RvgController
{
    public $placeholders = array();

    public function initialize()
    {
        $this->modx->lexicon->load('resvideogallery:default');
        $this->setDefaultProperties(array(
            'tpl' => '',
            'validate' => '' // example 'validate'=>'id|required,user_name|required|minLength=6|maxLength=12'
        ));
    }

    public function process()
    {
        $out = array('data' => '');
        $this->loadDictionary();
        $this->loadValidator();
        //$this->validate();
        $dataType = $this->getProperty('data_type', 'json', 'isset');
        $action = $this->getProperty('action', false, 'isset');
        switch ($action) {
            case 'embed':
                $preHooks = $this->modx->getOption('resvideogallery.pre_hooks_embed', null, '');
                $tplEmbed = $this->getProperty('tpl_embed', 'resVideoGalleryEmbedTpl');
                $tplTag = $this->getProperty('tpl_tag', 'resVideoGalleryTagTpl');
                if ($video = $this->runProcessor('web/video/get')) {
                    $video['tags'] = '';
                    if ($tags = $this->rvg->getTagsById($this->getProperty('id', 0))) {
                        foreach ($tags as $idx => $tag) {
                            $tag['idx'] = $idx;
                            $video['tags'] .= $this->rvg->getChunk($tplTag, $tag);
                        }
                    }
                    $this->setProperties($video);
                    if ($this->runHooks($preHooks)) {
                        $out['data'] = $this->rvg->getChunk($tplEmbed, $this->getProperties());
                    }
                }
                break;
            case 'more':
                $result = $this->modx->runSnippet('ResVideoGallery', $this->getProperty('params', array()));
                $out['total'] = $result['total'];
                $out['data'] = $result['data'];
                break;
        }
        if ($this->validator->hasErrors()) {
            $out['error'] = $this->validator->getRawErrors();
        }
        $out['result'] = !empty($out['error']) ? false : true;
        return $dataType == 'json' ? $out : $out['data'];
    }

    public function validate()
    {
        $fields = $this->validator->validateFields($this->dictionary, $this->getProperty('validate', ''));
        if (!$this->validator->hasErrors()) {
            $this->setProperties($fields);
            return true;
        }
        return false;
    }


}

return 'ResVideoGalleryVideoController';