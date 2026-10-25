<?php
/**
 * Build the setup options form.
 */
$exists = false;
$output = null;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	case xPDOTransport::ACTION_INSTALL:

	case xPDOTransport::ACTION_UPGRADE:
		$dialog = true;
		break;

	case xPDOTransport::ACTION_UNINSTALL: break;
}

if ($dialog) {
	$ru = (bool) ($modx->getOption('manager_language') == 'ru');

	$get_code = $ru
		? 'Получить код доступа'
		: 'Get access code';

	$key = $ru ? 'Код доступа' : 'Access code';

	$output =
	'<style>
		#setup_form_wrapper {font: normal 12px Arial;line-height:18px;}
		#setup_form_wrapper a {color: #08C;}
		#setup_form_wrapper label {width: 75px; text-align: right;}
		#setup_form_wrapper input {height: 25px; border: 1px solid #AAA; border-radius: 3px; padding: 3px; width: 100%;}
		#setup_form_wrapper table {margin-top:10px;}
		#setup_form_wrapper small {font-size: 10px; color:#555; font-style:italic;}
		#setup_form_wrapper .more_info {width: 100%;}
		#setup_form_wrapper .more_info a {line-height: 21px; display:inline-block;}
		#setup_form_wrapper .more_info img {border: none; display:inline-block;padding-top:10px;}
	</style>
	<div id="setup_form_wrapper">
		<table cellspacing="5" id="setup_form">
			<tr>
				<td><label for="key">'.$key.'</label></td>
				<td><input type="password" name="code" value="" placeholder="************" id="password" /></td>
				<td>&nbsp;&nbsp;</td>
				<td><a href="https://oauth.yandex.ru/authorize?response_type=code&client_id=07dcb06e1eae4194adafca47359406c1" target="_blank">'.$get_code.'</a></td>
			</tr>
		</table>
	</div>
	';
}

return $output;