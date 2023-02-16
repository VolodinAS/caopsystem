<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$PageRM = RecordManipulation($page_id, CAOP_PAGES, 'pages_id');
if ( $PageRM['result'] )
{
	$PageData = $PageRM['data'];
	
	$DeletePage = deleteitem(CAOP_PAGES, "pages_id='{$PageData['pages_id']}'");
	if ( $DeletePage ['result'] === true )
	{
		$response['result'] = true;
		$response['msg'] = 'Страница успешно удалена';
	}
	
} else $response['msg'] = $PageRM['msg'];