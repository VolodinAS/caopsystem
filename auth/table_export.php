<?php
if ( strlen($request_params) > 0 )
{
	$RequestData = explode("/", $request_params);
	$table_id = $RequestData[0];
	
	$table_data = table_to_array($table_id);
	
	if ( $table_data !== false )
	{
		$Table = getarr(CAOP_TABLES, "table_id='{$table_id}'")[0];
		debug($Table);
		
		$DATA = $table_data['data'];
		$HEADERS = $table_data['headers'];
		
		$columnPosition = 0;
		$startLine = 1;
		$document = new \PHPExcel();
		$sheet = $document->setActiveSheetIndex(0); // Выбираем первый лист в документе
		
		// Массив с названиями столбцов
		$columns = $HEADERS;
		
		// Указатель на первый столбец
		$currentColumn = $columnPosition;
		
		// Формируем шапку
		foreach ($columns as $column) {
			// Красим ячейку
			$sheet->getStyleByColumnAndRow($currentColumn, $startLine)
				->getFill()
				->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
				->getStartColor()
				->setRGB('4dbf62');
			
			$sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $column);
			
			// Смещаемся вправо
			$currentColumn++;
		}
		
		// Формируем список
		foreach ($DATA as $key=>$patientItem) {
//			debug($key);
//			debug($patientItem);
			// Перекидываем указатель на следующую строку
			$startLine++;
			// Указатель на первый столбец
			$currentColumn = $columnPosition;
			// Вставляем порядковый номер
//			$sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $key+1);
//			debug($patientItem);
			// Ставляем информацию об имени и цвете
			foreach ($patientItem as $value) {
				$sheet->setCellValueByColumnAndRow($currentColumn, $startLine, $value);
				$currentColumn++;
			}
		}
		$file = $Table['table_title'] . ' ('.date("Y.m.d H.i.s", time()).').xls';
//		$file = 'file.xls';
		$file_main = TABLES_EXPORT . $file;
		$file_bad = __DIR__ . '/file.xls';
		$objWriter = new PHPExcel_Writer_Excel5($document);
		$objWriter->save($file_main);
//		$objWriter->save('php://output');
		$file_link = 'https://'.DOMAIN.'/engine/temp/tables/export/' . $file;
		bt_notice(wrapper('Скачать файл: '). '<a href="'.$file_link.'">'.$file.'</a>');
		
	} else bt_notice('Такой таблицы не существует', BT_THEME_WARNING);
	
} else bt_notice('Неправильный запрос', BT_THEME_WARNING);