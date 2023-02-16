<?php


$CheckFiles = getarr(CAOP_TABLE_FILES, "file_table_id='{$TableData['table_id']}'");
$files_count = count($CheckFiles);
$files_removed = 0;
$go_next = false;
$files_removed_arr = [];
if ($files_count > 0)
{
	foreach ($CheckFiles as $file)
	{
		$file_path = getFullPathOfTableFile($file);
		if ($file_path !== false)
		{
			$is_removed = unlink($file_path);
			if ($is_removed)
			{
				$files_removed++;
				$files_removed_arr[] = $file['file_id'];
			}
		}
	}
	
	if ($files_removed == $files_count)
	{
		$file_ids = implode(', ', $files_removed_arr);
		$DeleteFiles = deleteitem(CAOP_TABLE_FILES, "file_id IN ({$file_ids})");
		if ($DeleteFiles ['result'] === true)
		{
			$go_next = true;
		} else $response['msg'] = 'Проблема с удалением файлов';
	} else
	{
		$response['msg'] = 'Не все файлы были удалены';
	}
	
} else
{
	$go_next = true;
}

if ($go_next)
{
	
	$DeleteProfiles = deleteitem(CAOP_TABLE_IMPORT_PROFILES, "profile_table_id='{$TableData['table_id']}'");
	
	if ($DeleteProfiles ['result'] === true)
	{
		
		$DeleteTable = deleteitem(CAOP_TABLES, "table_id='{$TableData['table_id']}'");
		if ($DeleteTable ['result'] === true)
		{
			
			$DeleteFields = deleteitem(CAOP_TABLE_FIELDS, "field_table_id='{$TableData['table_id']}'");
			
			if ($DeleteFields ['result'] === true)
			{
				$DeleteRows = deleteitem(CAOP_TABLE_CELLS, "cell_table_id='{$TableData['table_id']}'");
				
				if ($DeleteRows ['result'] === true)
				{
					
					$response['result'] = true;
					$response['msg'] = 'Таблица успешно удалена!';
					
				} else $response['msg'] = 'ЯЧЕЙКИ ТАБЛИЦЫ НЕ УДАЛЕНЫ';
			} else $response['msg'] = 'КОЛОНКИ ТАБЛИЦЫ НЕ УДАЛЕНЫ';
		} else $response['msg'] = 'ТАБЛИЦА НЕ УДАЛЕНА';
		
		
	} else $response['msg'] = 'Проблема удаления профилей таблицы';
	
}

