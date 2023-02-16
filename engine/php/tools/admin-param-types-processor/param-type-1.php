<?php
// $PARAM_DATA
$param_checked = ((int)$PARAM_DATA['value'] == 1) ? ' checked' : '';

$switcher_checkbox_options = array(
	'mye' => array(
		'table' => CAOP_PARAMS,
		'field_id' => $PARAM_DATA['fieldid'],
		'id' => $PARAM_DATA['id'],
		'field' => $PARAM_DATA['field'],
		'unixfield' => $PARAM_DATA['unix']
	),
	'addon' => $param_checked
);
$switcher = checkbox_switcher($switcher_checkbox_options);

echo $switcher;