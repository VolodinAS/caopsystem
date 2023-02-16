<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$mysqli_tables_file_path = ROOT . MYSQLI . '/mysqli-tables.php';

if ( file_exists($mysqli_tables_file_path) )
{
    $response['debug']['$mysqli_tables_file_path'] = $mysqli_tables_file_path;
    $file_name = basename($mysqli_tables_file_path);
    
    $response['debug']['$file_name'] = $file_name;
    
    $file_name_bak = $file_name . '.' . date("Ymdhis", time()) . '.bak';
    
    $response['debug']['$file_name_bak'] = $file_name_bak;
    
    $file_copy_path = ROOT . MYSQLI . '/' . $file_name_bak;
    
    $response['debug']['$file_copy_path'] = $file_copy_path;
    
    // 1 - создаем резервную копию
	$is_copied = copy($mysqli_tables_file_path, $file_copy_path);
	
	if ( $is_copied )
	{
		$AllTables = mysql_tables(DB_NAME, TABLES_COLUMN);
		
		// 2 - собираем строку для записи
		$script = '<?php' . "\n";
		$script .= '$PK = array();' . "\n";
//		debug($AllTables);
		foreach ($AllTables as $allTable) {
			
			$primary = getPrimaryKey($allTable);
			$script .=  '$'.strtoupper($allTable).' = \''.$allTable.'\'; define("'.strtoupper($allTable).'", "'.$allTable.'"); $PK['.strtoupper($allTable).'] = "'.$primary.'";' . "\n";
		}
		$script .= '?>';
		
		$response['debug']['$script'] = $script;
		
		$result = file_put_contents($mysqli_tables_file_path, $script);
		
		$response['debug']['$result'] = $result;
		
		if ( $result !== false )
		{
			$response['msg'] = 'Таблицы успешно перезаписаны!';
		} else
		{
			$response['msg'] = 'Проблема перезаписи файла!';
		}
		
		
	
	} else $response['msg'] = 'Копирование не удалось!';
	
} else $response['msg'] = 'Неверно указан путь к файлу';