<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( strlen($task_text) >= 5 )
{
	$appendValues = array(
		'needadd_text'  =>  mres($task_text),
		'needadd_add_unix'  =>  time(),
		'needadd_upd_unix'  =>  time()
	);
	$AppendData = appendData(CAOP_NEEDADD, $appendValues);
	if ( $AppendData[ID] > 0 )
	{
		$updateValues = array(
			'needadd_priority'  =>  $AppendData[ID]
		);
		$UpdateData = updateData(CAOP_NEEDADD, $updateValues, $AppendData, "needadd_id='{$AppendData[ID]}'");
		if ( $UpdateData['stat'] == RES_SUCCESS )
		{
			$response['result'] = true;
		} else
		{
			$response['msg'] = 'Проблема с обновлением приоритета';
		}
	} else
	{
		$response['msg'] = 'Проблема с добавлением задания';
	}
	
	
} else
{
	$response['msg'] = 'Слишком короткий текст задания';
}
