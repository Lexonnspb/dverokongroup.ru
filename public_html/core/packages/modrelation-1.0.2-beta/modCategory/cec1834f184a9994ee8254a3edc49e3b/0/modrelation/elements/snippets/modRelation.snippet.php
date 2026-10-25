<?php
if (empty($field)) {
  $field = 'pagetitle';
}
if (empty($separator)) {
  $separator = ' ';
}
if (empty($element)) {
  $element = 'pdoResources';
}
if (empty($limitWord)) {
  $limitWord = 4;
} else {
  $limitWord = intval($limitWord);
}
$pagetitle = $modx->resource->get($field);
if (!$pagetitle) {
  return ;
}
$arr = explode($separator, $pagetitle);
$rand = array_rand($arr, 1);
while (iconv_strlen($arr[$rand]) < $limitWord):
  $rand = array_rand($arr, 1);
endwhile;

$rand_pt = $arr[$rand];
if ($substr) {
  $rand_pt = mb_substr($rand_pt, 0, $substr, 'UTF-8');
}
//echo $rand_pt;
$where = array('where' => array(
    $field.':LIKE' => '%'.$rand_pt.'%',
    'id:!=' => $modx->resource->id
));

$scriptProperties = array_merge($where, $scriptProperties);
$pdoTools = $modx->getService('pdoTools');
return $pdoTools->runSnippet($element, $scriptProperties);