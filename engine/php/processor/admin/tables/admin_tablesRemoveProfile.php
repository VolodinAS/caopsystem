<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DeleteProfile = deleteitem(CAOP_TABLE_IMPORT_PROFILES, "profile_id='{$profile_id}'");
if ($DeleteProfile ['result'] === true)
{
	$response['result'] = true;
} else $response['msg'] = 'Ошибка при удалении профиля';