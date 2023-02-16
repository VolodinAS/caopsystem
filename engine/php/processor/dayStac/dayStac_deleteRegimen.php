<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DeleteVisreg = deleteitem(CAOP_DS_REGIMENS, "regimen_id='{$regimen_id}'");
if ( $DeleteVisreg['result'] )
{
	$response['result'] = true;
} else
{
	$response['msg'] = 'Ошибка удаления назначения';
	$response['debug']['$DeleteVisreg'] = $DeleteVisreg;
}