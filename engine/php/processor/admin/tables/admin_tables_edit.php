<?php

$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$TableRM = RecordManipulation($table, CAOP_TABLES, 'table_id');
if ($TableRM['result'])
{
	$TableData = $TableRM['data'];
	
	if ($act == "get")
	{
		$response['result'] = true;
		$response['data'] = $TableData;
	} elseif ($act == "remove")
	{
		require_once ("engine/html/admin/tables/action_delete.php");
	} elseif ($act == "import")
	{
		require_once ("engine/html/admin/tables/action_uploader.php");
	} elseif ($act == "upload")
	{
		require_once ("engine/html/admin/tables/action_upload.php");
	} elseif ($act == "files")
	{
		require_once ("engine/html/admin/tables/action_files.php");
	} elseif ($act == "file_import")
	{
		require_once ("engine/html/admin/tables/action_file_import.php");
	} elseif ($act == "file_remove")
	{
		require_once ("engine/html/admin/tables/action_file_remove.php");
	}
	
} else $response['msg'] = $TableRM['msg'];