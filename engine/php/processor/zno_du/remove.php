<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$Delete = deleteitem(CAOP_ZNO_DU, "zno_id='{$zno_id}'");
if ( $Delete ['result'] === true )
{
	$response['result'] = true;
} else $response['msg'] = 'Проблема удаления записи';