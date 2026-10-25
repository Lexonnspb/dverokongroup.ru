<?php return array (
  '29765da669f36189f5b4f8dcce3e9d50' => 
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
  '10f2b3f249b095791dd8b1416335c5fb' => 
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
      'category' => 0,
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
);