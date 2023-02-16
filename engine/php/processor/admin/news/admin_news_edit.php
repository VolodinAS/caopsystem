<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$NewsRM = RecordManipulation($table, CAOP_NEWS, 'news_id');
if ( $NewsRM['result'] )
{
	$NewsData = $NewsRM['data'];
	
	if ( $act == "get" )
	{
		$response['result'] = true;
		$response['data'] = $NewsData;
	} elseif ( $act == "remove" )
	{
		$DeleteNews = deleteitem(CAOP_NEWS, "news_id='{$NewsData['news_id']}'");
		if( $DeleteNews ['result'] === true )
		{
			
			$response['result'] = true;
			$response['msg'] = 'Новость успешно удалена!';
		} else $response['msg'] = 'НОВОСТЬ НЕ УДАЛЕНА';
	}
	
} else $response['msg'] = $NewsRM['msg'];