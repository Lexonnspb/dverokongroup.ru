<?php return array (
  'c7a3129e913cd681335e113f1d862693' => 
  array (
    'criteria' => 
    array (
      'name' => 'tvtable',
    ),
    'object' => 
    array (
      'name' => 'tvtable',
      'path' => '{core_path}components/tvtable/',
      'assets_path' => '',
    ),
  ),
  'bb18c7c4981a60d077fb15da81377bb9' => 
  array (
    'criteria' => 
    array (
      'category' => 'TVTable',
    ),
    'object' => 
    array (
      'id' => 29,
      'parent' => 0,
      'category' => 'TVTable',
      'rank' => 0,
    ),
  ),
  '88544a0157bfdaca7aa8b6c70f5c68bc' => 
  array (
    'criteria' => 
    array (
      'name' => 'TVTable',
    ),
    'object' => 
    array (
      'id' => 23,
      'source' => 1,
      'property_preprocess' => 0,
      'name' => 'TVTable',
      'description' => '',
      'editor_type' => 0,
      'category' => 29,
      'cache_type' => 0,
      'snippet' => '/** @var modX $modx */
/** @var array $scriptProperties */
if (!isset($tv)){
   return;
}

$tv = (int)$tv;
$classname = isset($classname) ? $classname : \'pricelist\';
$did =isset($id) ? $id : $modx->resource->id;

if ($tvObject = $modx->getObject(\'modTemplateVarResource\', array(\'tmplvarid\' => $tv, \'contentid\' => $did ))){
    $tvv = $tvObject->get(\'value\');
}

if (!$tvv || $tvv==\'[["",""],["",""]]\') return;
$tvtArr=json_decode($tvv);

$output=\'<table class="\'.$classname.\'">\'."\\n";
$output .=\'<tr>\'."\\n";
for($i=0; $i<count($tvtArr[0]); $i++) $output .=\'<th\'.($i ? \'\' : \' class="first"\').\'>\'.$tvtArr[0][$i].\'</th>\'."\\n";
$output.=\'</tr>\'."\\n";
for($row=1; $row<count($tvtArr); $row++) {
	$output .=\'<tr\'.(($row%2) ? \'\' : \' class="altrow"\').\'>\'."\\n";
	for($i=0; $i<count($tvtArr[$row]); $i++) $output .=\'<td\'.($i ? \'\' : \' class="first"\').\'>\'.$tvtArr[$row][$i].\'</td>\'."\\n";
	$output.=\'</tr>\'."\\n";
}
$output.=\'</table>\';
return $output;',
      'locked' => 0,
      'properties' => 'a:0:{}',
      'moduleguid' => '',
      'static' => 0,
      'static_file' => 'core/components/tvtable/elements/snippets/snippet.tvtable.php',
      'content' => '/** @var modX $modx */
/** @var array $scriptProperties */
if (!isset($tv)){
   return;
}

$tv = (int)$tv;
$classname = isset($classname) ? $classname : \'pricelist\';
$did =isset($id) ? $id : $modx->resource->id;

if ($tvObject = $modx->getObject(\'modTemplateVarResource\', array(\'tmplvarid\' => $tv, \'contentid\' => $did ))){
    $tvv = $tvObject->get(\'value\');
}

if (!$tvv || $tvv==\'[["",""],["",""]]\') return;
$tvtArr=json_decode($tvv);

$output=\'<table class="\'.$classname.\'">\'."\\n";
$output .=\'<tr>\'."\\n";
for($i=0; $i<count($tvtArr[0]); $i++) $output .=\'<th\'.($i ? \'\' : \' class="first"\').\'>\'.$tvtArr[0][$i].\'</th>\'."\\n";
$output.=\'</tr>\'."\\n";
for($row=1; $row<count($tvtArr); $row++) {
	$output .=\'<tr\'.(($row%2) ? \'\' : \' class="altrow"\').\'>\'."\\n";
	for($i=0; $i<count($tvtArr[$row]); $i++) $output .=\'<td\'.($i ? \'\' : \' class="first"\').\'>\'.$tvtArr[$row][$i].\'</td>\'."\\n";
	$output.=\'</tr>\'."\\n";
}
$output.=\'</table>\';
return $output;',
    ),
  ),
  'fa69e40e83d3579e5735a2d045fd93ce' => 
  array (
    'criteria' => 
    array (
      'name' => 'TVTable',
    ),
    'object' => 
    array (
      'id' => 22,
      'source' => 1,
      'property_preprocess' => 0,
      'name' => 'TVTable',
      'description' => '',
      'editor_type' => 0,
      'category' => 29,
      'cache_type' => 0,
      'plugincode' => '/** @var modX $modx */
$corePath = $modx->getOption(\'tvtable.core_path\', null, $modx->getOption(\'core_path\') . \'components/tvtable/\');

switch ($modx->event->name) {
	case \'OnTVInputRenderList\':
		$modx->event->output($corePath . \'elements/tv/input/\');
		break;
	case \'OnTVInputPropertiesList\':
		$modx->event->output($corePath . \'elements/tv/inputoptions/\');
		break;
	case \'OnDocFormPrerender\':
		$modx->regClientStartupScript(\'//code.jquery.com/jquery-1.11.2.min.js\');
		break;
}',
      'locked' => 0,
      'properties' => NULL,
      'disabled' => 0,
      'moduleguid' => '',
      'static' => 0,
      'static_file' => 'core/components/tvtable/elements/plugins/plugin.tvtable.php',
      'content' => '/** @var modX $modx */
$corePath = $modx->getOption(\'tvtable.core_path\', null, $modx->getOption(\'core_path\') . \'components/tvtable/\');

switch ($modx->event->name) {
	case \'OnTVInputRenderList\':
		$modx->event->output($corePath . \'elements/tv/input/\');
		break;
	case \'OnTVInputPropertiesList\':
		$modx->event->output($corePath . \'elements/tv/inputoptions/\');
		break;
	case \'OnDocFormPrerender\':
		$modx->regClientStartupScript(\'//code.jquery.com/jquery-1.11.2.min.js\');
		break;
}',
    ),
  ),
  '34f15253c7f4a146a0c61c5758b9cb3e' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 22,
      'event' => 'OnTVInputRenderList',
    ),
    'object' => 
    array (
      'pluginid' => 22,
      'event' => 'OnTVInputRenderList',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
  'ad1781704612a094dd88215f9c6eefa3' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 22,
      'event' => 'OnTVInputPropertiesList',
    ),
    'object' => 
    array (
      'pluginid' => 22,
      'event' => 'OnTVInputPropertiesList',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
  'fe10a1d4fdfbc65d56dcae9457fc1183' => 
  array (
    'criteria' => 
    array (
      'pluginid' => 22,
      'event' => 'OnDocFormPrerender',
    ),
    'object' => 
    array (
      'pluginid' => 22,
      'event' => 'OnDocFormPrerender',
      'priority' => 0,
      'propertyset' => 0,
    ),
  ),
);