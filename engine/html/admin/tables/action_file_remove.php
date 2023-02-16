<?php
$response['debug']['$TableData'] = $TableData;
$FileRM = RecordManipulation($file_id, CAOP_TABLE_FILES, 'file_id');
if ($FileRM['result'])
{
	$FileData = $FileRM['data'];
	
	$response['debug']['$FileData'] = $FileData;
	
	$file_name = $FileData['file_md5'] . '.' . $FileData['file_ext'];
	$file_path = TABLES_FILES_PATH . $file_name;
	
	$response['debug']['$file_name'] = $file_name;
	$response['debug']['$file_path'] = $file_path;
	
	if (file_exists($file_path))
	{
		$delete = unlink($file_path);
		
		if ($delete)
		{
			$go_next = true;
			$response['msg'] = 'Файл успешно удалён';
		} else
		{
			$response['msg'] = "Файл {$file_name} удалить не удалось";
		}
	} else
	{
		$go_next = true;
		$response['msg'] = 'Файла в системе не существует, удалён из БД';
	}
	
	if ($go_next)
	{
		$DeleteFile = deleteitem(CAOP_TABLE_FILES, "file_id='{$FileData['file_id']}'");
		if ($DeleteFile ['result'] === true)
		{
			$response['result'] = true;
		} else $response['msg'] = 'Не удалось удалить файл из БД';
	}
	
	
} else $response['msg'] = $FileRM['msg'];