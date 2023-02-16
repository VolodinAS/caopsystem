<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$TableRM = RecordManipulation($table_id, CAOP_TABLES, 'table_id');
if ( $TableRM['result'] )
{
    $TableData = $TableRM['data'];

    switch ($act)
    {
        case "removeRecord":
            if ( count($checks) > 0 )
            {
            
            	$records = implode(', ', $checks);
            	
            	$DeleteRecords = deleteitem(CAOP_TABLE_CELLS, "cell_table_id='{$TableData['table_id']}' AND cell_record_id IN ({$records})");
            	if ( $DeleteRecords ['result'] === true )
	            {
	            	$response['result'] = true;
	            } else $response['msg'] = 'Проблемы при удалении строк';
            	
            	
            
            } else $response['msg'] = 'Не отмечены строки для удаления';
        break;
        case "newRecord":
            $record_id = 1;
            $Record = getarr(CAOP_TABLE_CELLS, "cell_table_id='{$TableData['table_id']}'", "ORDER BY cell_record_id DESC LIMIT 0, 1");
            if ( count($Record) > 0 )
            {
	            $Record = $Record[0];
	            $record_id = $Record['cell_record_id'] + 1;
            }
            
            $Fields = getarr(CAOP_TABLE_FIELDS, "field_table_id='{$TableData['table_id']}'", "ORDER BY field_id ASC");
            $count_Fields = count($Fields);
            if ( $count_Fields > 0 )
            {
	            $success = 0;
	            foreach ($Fields as $field)
	            {
		            $param_record = array(
			            'cell_table_id' => $TableData['table_id'],
			            'cell_record_id' => $record_id,
			            'cell_field_id' => $field['field_id']
		            );
		            $AddCell = appendData(CAOP_TABLE_CELLS, $param_record);
		            if ( $AddCell[ID] > 0 )
		            {
		            	$success++;
		            }
            	}
	            
	            if ( $success == $count_Fields )
	            {
//	            	$response['result'] = true;
		            $RecordsCount = getrows(CAOP_TABLE_CELLS, "cell_table_id='{$TableData['table_id']}'", ' DISTINCT celL_record_id');
		            $npp = $RecordsCount['count'];
//		            $response['debug']['$RecordsCount'] = $RecordsCount;
		            $new_cell = getarr(CAOP_TABLE_CELLS, "cell_table_id='{$TableData['table_id']}' AND cell_record_id='{$record_id}'", "ORDER BY cell_id ASC");
		            $new_cell_content = table_record_divider($new_cell, 'cell_record_id');
		            foreach ($new_cell_content as $record_id => $record_data)
			            $result = table_constructor_row_content($TableData['table_id'], $npp, $record_id, $record_data, $Fields);
		            
		            $response['new_record'] = $result;
		            $response['record_id'] = $npp;
		            
		            $response['result'] = true;
	            } else $response['msg'] = 'Проблема с добавлением записи';
             
            } else $response['msg'] = 'У данной таблицы нет столбцов';
            
            
            
        break;
    }

} else $response['msg'] = $TableRM['msg'];