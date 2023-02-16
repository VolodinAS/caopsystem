<?php
$response['stage'] = $action;

$params_data = json_decode($_POST['params'], 1);
$table = $_POST['table'];

$query_ClearTable = "TRUNCATE {$table}";
$result_ClearTable = mqc($query_ClearTable);

foreach ($params_data as $params_datum)
{
	$paramsValue = array(
		'type_title'    =>  $params_datum['TYPE_TITLE'],
		'type_enabled'    =>  $params_datum['TYPE_ENABLED'],
		'type_order'    =>  $params_datum['TYPE_ORDER'],
		'type_addon'    =>  $params_datum['TYPE_ADDON']
	);
	$InsertField = appendData($table, $paramsValue);
	if ( $InsertField[ID] > 0 )
	{
		$response['debug']['$InsertField'][] = $InsertField[ID];
	}
}

$response['result'] = true;