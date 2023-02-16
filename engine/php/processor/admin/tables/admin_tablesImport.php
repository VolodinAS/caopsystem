<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$TableRM = RecordManipulation($table_id, CAOP_TABLES, 'table_id');
if ($TableRM['result'])
{
	$TableData = $TableRM['data'];
	
	$FileRM = RecordManipulation($file_id, CAOP_TABLE_FILES, 'file_id');
	if ($FileRM['result'])
	{
		$FileData = $FileRM['data'];
		
		$ProfileRM = RecordManipulation($profile_id, CAOP_TABLE_IMPORT_PROFILES, 'profile_id');
		if ($ProfileRM['result'])
		{
			$ProfileData = $ProfileRM['data'];
			
			$TableField = getarr(CAOP_TABLE_FIELDS, "field_table_id='{$TableData['table_id']}'");
			if (count($TableField) > 0)
			{
				$response['HEADERS'] = array();
				$response['debug']['$TableData'] = $TableData;
				$response['debug']['$FileData'] = $FileData;
				$response['debug']['$ProfileData'] = $ProfileData;
				$response['HEADERS']['pre']['$TableField'] = $TableField;
				
				$file_path = getFullPathOfTableFile($FileData);
				
				if ($file_path !== false)
				{
					
					$table = Xls2Array($file_path);
					
					if (count($table) > 0)
					{
						$response['debug']['$table'] = $table;
						
						$table_header = $table[$ProfileData['profile_header_index']];
//						$table_header = array_values($table_header);
						
						$table_header_count = count($table_header);
						$table_import_count = count($TableField);
						
						$response['debug']['$table_header_count'] = $table_header_count;
						$response['debug']['$table_import_count'] = $table_import_count;
//						$response['HEADERS']['pre']['$table_header'] = $table_header;
						
						if ($table_header_count >= $table_import_count)
						{
							$record_id = 0;
							if ($record_id == 0)
							{
								$Records = getarr(CAOP_TABLE_CELLS, "cell_table_id='{$TableData['table_id']}'", "ORDER BY cell_record_id DESC LIMIT 0,1");
								if (count($Records) > 0)
								{
									$Record = $Records[0];
									$record_id = $Record['cell_record_id'] + 1;
								} else $record_id = 1;
							}
							
							$response['TABLE_INFO']['$record_id'] = $record_id;
							$response['TABLE_INFO']['CYCLE'] = array();
							
							$CYCLE = 0;
							
							$go_next = false;
							while ($CYCLE < 20)
							{
								$temp_record_id = $record_id;
								
								$field_index = 0;
								$CYCLE_DATA = array();
								$is_found = false;
								$column_table = 0;
								$field_data = $TableField[$field_index];
								$table_index = -1;
								
								foreach ($table_header as $col_index => $col_title)
								{
									if ($field_data['field_title_full'] == $col_title)
									{
										$is_found = true;
										$table_index = $col_index;
									}
								}
								
								if ($is_found)
								{
									$CYCLE_DATA['$table_index'] = $table_index;
									$CYCLE_DATA['$field_index'] = $field_index;
									
									$CYCLE_DATA['$group_field'] = $TableField[$field_index];
									$CYCLE_DATA['$group_table'] = $table_header[$table_index];
									
									foreach ($table as $col_number => $col_data)
									{
										
										if ( $col_number >= (int)$ProfileData['profile_offset_data'] )
										{
											// эти данные подходят под индекс
											$cell_content_of_col_index = $col_data[$table_index];
											
											$param_cell = array(
											    'cell_table_id' => $TableData['table_id'],
												'cell_record_id' => $temp_record_id,
												'cell_field_id' => $TableField[$field_index]['field_id'],
												'cell_content' => $cell_content_of_col_index
											);
											
											$AddCell = appendData(CAOP_TABLE_CELLS, $param_cell);
											if ( $AddCell[ID] > 0 )
											{
												$temp_record_id++;
											}
										}
										
										
									}
									
									unset($table_header[$table_index]);
									unset($TableField[$field_index]);

//									$table_header = array_values($table_header);
									$TableField = array_values($TableField);
									
									$CYCLE_DATA['$table_header'] = $table_header;
									$CYCLE_DATA['$TableField'] = $TableField;
								} else
								{
									$field_index++;
								}
								
								$response['HEADERS'][$CYCLE] = $CYCLE_DATA;
								
								$CYCLE++;
								if (count($TableField) == 0)
								{
									$go_next = true;
									break;
								}
							}

							if ( $go_next )
							{
								$response['msg'] = 'Таблица успешно импортирована!';
								$response['result'] = true;
							} else $response['msg'] = 'Что-то пошло не так';
							
							
							/*
							
							
							
							while (1)
							{
								$temp_record_id = $record_id;
								
								
								if ($is_found)
								{
									$cell_field_id = $TableField[$index]['field_id'];
									foreach ($table as $col_number => $col_data)
									{
										if ( $col_number >= $ProfileData['profile_offset_data'] )
										{
											$row_content = $col_data[$col_number];
											$response['debug']['$coldata_CHECK'][] = $col_data[$col_number];
											$response['debug']['$col_number_CHECK'][] = $col_number;
											$param_cell = array(
												'cell_table_id' => $TableData['table_id'],
												'cell_record_id' => $temp_record_id,
												'cell_field_id' => $cell_field_id,
												'cell_content' => $row_content
											);
											
											$AddDataInTable = appendData(CAOP_TABLE_CELLS, $param_cell);
											if ($AddDataInTable[ID] > 0)
											{
												$temp_record_id++;
											}
										}
									}
									
									unset($TableField[$index]);
									$response['debug']['$TableField_CHECK'][] = $TableField;
									
									if ( count($TableField) == 0 )
									{
									    $go_next = true;
										break;
									}
									
								}
								
								$index++;
							}
							
							*/
							
						} else $response['msg'] = 'Столбцы таблицы не соответствуют полям импорта';
						
						
					} else $response['msg'] = 'Ошибка чтения файла';
					
				} else $response['msg'] = 'Ошибка чтения файла';
				
			} else $response['msg'] = 'У таблицы нет ячеек для импорта';
			
		} else $response['msg'] = $ProfileRM['msg'];
		
	} else $response['msg'] = $FileRM['msg'];
	
} else $response['msg'] = $TableRM['msg'];