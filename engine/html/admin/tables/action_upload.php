<?php
if (isset($_POST['my_file_upload']))
{
	// ВАЖНО! тут должны быть все проверки безопасности передавемых файлов и вывести ошибки если нужно
	
	$uploaddir = 'engine/temp/tables'; // . - текущая папка где находится submit.php
	
	// cоздадим папку если её нет
//			if (!is_dir($uploaddir)) mkdir($uploaddir, 0777);
	
	$files = $_FILES; // полученные файлы
	$done_files = array();
	$fail_files = array();
	
	$TOTAL = count($files);
	$SUCCESS = 0;
	
	// переместим файлы из временной директории в указанную
	foreach ($files as $file)
	{
		
		// TODO ДОБАВИТЬ добавление файла в БД. При загрузке выполнять проверку по md5-файла
		$file_info = pathinfo($file['tmp_name']);
		$file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$file_name = md5_file($file['tmp_name']);
		$full_file = $file_name . '.' . $file_ext;
		$full_upload_path = $uploaddir . '/' . $full_file;
		
		$is_uploaded = move_uploaded_file($file['tmp_name'], $full_upload_path);
		
		if ($is_uploaded)
		{
			$done_files[] = realpath($full_upload_path);
			
			
			$param_table_file = array(
				'file_name' => $file['name'],
				'file_ext' => $file_ext,
				'file_md5' => $file_name,
				'file_doctor_id' => $USER_PROFILE['doctor_id'],
				'file_table_id' => $table,
				'file_upload_unix' => time()
			);
			
			// Функционал для обновления версии
			$options = array(
				'field_selector' => array(
					'field' => 'file_md5',
					'value' => $file_name
				),
				'field_version' => 'file_version',
				'field_params' => $param_table_file,
				'field_equal' => array(
					'field' => 'file_name',
					'value' => $file['name']
				),
				'field_id' => 'file_id'
			);
			$response['debug']['$options'] = $options;
			$AddFile = appendVersion(CAOP_TABLE_FILES, $options);
			$response['debug']['$AddFile'] = $AddFile;
			if ($AddFile ['result'] === true)
			{
//						$response['result'] = true;
				$SUCCESS++;
			} else $response['msg'] = $AddFile['msg'];
			
			
		} else
		{
			$fail_files[] = $file['name'];
		}
		
		$file_debug = array(
			'$file' => $file,
			'$file_info' => $file_info,
			'$file_ext' => $file_ext,
			'$file_name' => $file_name,
			'$full_file' => $full_file,
			'$full_upload_path' => $full_upload_path,
			'$is_uploaded' => $is_uploaded
		);
		
		$response['debug']['$file_debug'][] = $file_debug;
		
		// ОДИН ФАЙЛ
		break;
	}
	
	$response['debug']['SUCCESS / TOTAL'] = $SUCCESS . ' / ' . $TOTAL;
	$response['debug']['$done_files'] = $done_files;
	if ($SUCCESS == $TOTAL)
	{
		$response['result'] = true;
	} else
	{
		
		$response['msg'] = (strlen($response['msg']) > 0) ? $response['msg'] : 'Некоторые файлы не загрузились';
		$response['fail_files'] = $fail_files;
	}
} else $response['msg'] = 'Нет файлов для загрузки';