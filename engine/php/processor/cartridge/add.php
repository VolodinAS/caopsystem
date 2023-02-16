<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$CartridgeCheck = getarr(CAOP_CARTRIDGE, "cartridge_ident='$cartridge_ident'");
if ( count($CartridgeCheck) > 0 )
{
    $response['msg'] = 'Картридж с таким идентификатором уже есть в системе';
} else
{
	$param_add = $HTTP;
	$param_add['cartridge_update_unix'] = time();
	
	$NewCartridge = appendData(CAOP_CARTRIDGE, $param_add);
	if ( $NewCartridge[ID] > 0 )
	{
		$response['msg'] = 'Картридж '.$cartridge_ident.' успешно добавлен';
		$response['result'] = true;
	} else
	{
		$response['msg'] = 'Проблема добавления картриджа в список';
		$response['debug']['$NewCartridge'] = $NewCartridge;
	}
}