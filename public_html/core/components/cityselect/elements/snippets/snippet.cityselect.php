<?php
$cityselect = $modx->getService('cityselect','cityselect',$modx->getOption('cityselect_core_path',null,$modx->getOption('core_path').'components/cityselect/').'model/cityselect/',$scriptProperties);
if (!($cityselect instanceof cityselect)) return '';

$cityselect->initialize($modx->context->key);

$tpl = $modx->getOption('tpl',$scriptProperties,'tpl.CitySelect.example', true);

if (!$chunk = $modx->getObject('modChunk', array('name' => $tpl))) {
	return $modx->lexicon('cityselect_err_chunk', array('name' => $tpl));
}

$output = $chunk->getContent();

if (empty($_COOKIE['city_name'])) {

	$SxGeo = new SxGeo($modx->getOption('cityselect_assets_path',null,$modx->getOption('assets_path').'components/cityselect/').'data/'.$modx->getOption('cityselect_name_db_sxgeo','SxGeoCity.dat') );
	$Sx_arr = $SxGeo->getCityFull($_SERVER['REMOTE_ADDR']);
	if (!empty($Sx_arr['city']['name_ru'])) {

		setcookie('city_name',$Sx_arr['city']['name_ru'], time() + (365*24*60*60) );

	}
	else {

		setcookie('city_name',$modx->lexicon('cityselect_is_unknown'), time() + (60*60));
	}

}

$modx->setPlaceholder('cityselect', $_COOKIE['city_name']);

$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);

if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder,$output);
	return '';
}

return $output;

/*
 * $Sx_arr = $SxGeo->getCityFull();
 * Array
(
    [city] => Array
        (
            [id] => 524901
            [lat] => 55.75222
            [lon] => 37.61556
            [name_ru] => Москва
            [name_en] => Moscow
        )

    [region] => Array
        (
            [id] => 524894
            [name_ru] => Москва
            [name_en] => Moskva
            [iso] => RU-MOW
        )

    [country] => Array
        (
            [id] => 185
            [iso] => RU
            [continent] => EU
            [lat] => 60
            [lon] => 100
            [name_ru] => Россия
            [name_en] => Russia
        )

)
 */