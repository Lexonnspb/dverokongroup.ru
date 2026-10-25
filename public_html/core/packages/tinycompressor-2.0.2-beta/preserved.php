<?php return array (
  '10874585552b47849f955f76ebd402b2' => 
  array (
    'criteria' => 
    array (
      'name' => 'tinycompressor',
    ),
    'object' => 
    array (
      'name' => 'tinycompressor',
      'path' => '{core_path}components/tinycompressor/',
      'assets_path' => '',
    ),
  ),
  '8fa9d7427bed585c851bef6bd6bd7dcd' => 
  array (
    'criteria' => 
    array (
      'key' => 'tinycompressor_ilovepdf_compression_level',
    ),
    'object' => 
    array (
      'key' => 'tinycompressor_ilovepdf_compression_level',
      'value' => 'recommended',
      'xtype' => 'textfield',
      'namespace' => 'tinycompressor',
      'area' => 'ILovePDF',
      'editedon' => NULL,
    ),
  ),
  '796843705877881123456dc5477d6201' => 
  array (
    'criteria' => 
    array (
      'category' => 'tinyCompressor',
    ),
    'object' => 
    array (
      'id' => 19,
      'parent' => 0,
      'category' => 'tinycompressor',
      'rank' => 0,
    ),
  ),
  '85eb0eabeb7f481299b8e4f9f3d3275e' => 
  array (
    'criteria' => 
    array (
      'name' => 'tinyCompressor',
    ),
    'object' => 
    array (
      'id' => 14,
      'source' => 1,
      'property_preprocess' => 0,
      'name' => 'tinycompressor',
      'description' => 'Smart PNG and JPEG compression for MODx Revolution.',
      'editor_type' => 0,
      'category' => 19,
      'cache_type' => 0,
      'plugincode' => '/** @var modX $modx */

$tcFile = $modx->getOption(\'core_path\') . \'components/tinycompressor/model/tinycompressor/tinycompressor.class.php\';
switch ($modx->event->name) {
    case \'OnFileManagerUpload\':

        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compress($files, $directory);

        break;

    case \'OnFileManagerFileCreate\':

        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compress($files, $directory);

        break;

    case \'OnPhpThumbRenderToFile\':
        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compressImage($filename);
        break;
}',
      'locked' => 0,
      'properties' => NULL,
      'disabled' => 0,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => 'core/components/tinycompressor/elements/plugins/plugin.tinycompressor.php',
      'content' => '/** @var modX $modx */

$tcFile = $modx->getOption(\'core_path\') . \'components/tinycompressor/model/tinycompressor/tinycompressor.class.php\';
switch ($modx->event->name) {
    case \'OnFileManagerUpload\':

        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compress($files, $directory);

        break;

    case \'OnFileManagerFileCreate\':

        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compress($files, $directory);

        break;

    case \'OnPhpThumbRenderToFile\':
        /**
         * @var tinycompressor $tinycompressor
         */
        require_once($tcFile);
        $tinycompressor = new tinycompressor($modx);
        $tinycompressor->compressImage($filename);
        break;
}',
    ),
  ),
  'a89ef3040d400185c1961ac34c12d74e' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 14,
      'event' => 'OnFileManagerUpload',
    ),
    'object' => 
    array (
      'pluginid' => 14,
      'event' => 'OnFileManagerUpload',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
  '4426ab0e63f731ab15045c75f618173f' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 14,
      'event' => 'OnFileManagerFileCreate',
    ),
    'object' => 
    array (
      'pluginid' => 14,
      'event' => 'OnFileManagerFileCreate',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
  'e337b60f63bec341be1e8d40567e0d36' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 14,
      'event' => 'OnPhpThumbRenderToFile',
    ),
    'object' => 
    array (
      'pluginid' => 14,
      'event' => 'OnPhpThumbRenderToFile',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
);