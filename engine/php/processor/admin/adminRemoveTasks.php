<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( count($tasks) > 0 )
{
	$tasks_query = getLinearIds($tasks, 999);
	
	$DeleteQuery = "DELETE FROM {$CAOP_NEEDADD} WHERE needadd_id IN {$tasks_query}";
	$DeleteResult = mqc($DeleteQuery);
	
	if ( $DeleteResult )
	{
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Проблема при удалении';
		$response['debug']['$DeleteQuery'] = $DeleteQuery;
	}
	
} else
{
	$response['msg'] = 'Не выбраны таски для удаления';
}