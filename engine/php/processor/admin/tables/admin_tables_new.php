<?php
$response['stage'] = $action;
$response['msg'] = 'begin';


$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( strlen($table_description) > 0 )
{
    if ( strlen($table_subtitle) > 0 )
    {
        if ( strlen($table_title) > 0 )
        {
        	$TABLE_ID = (int)$table_id;
        	
        	if ( $TABLE_ID > 0 )
	        {
	        	// обновление данных о таблице
		        
		        $TableRM = RecordManipulation($TABLE_ID, CAOP_TABLES, 'table_id');
		        if ( $TableRM['result'] )
		        {
		            $TableData = $TableRM['data'];
			
			        $param_values = $HTTP;
			        $param_values['table_unix_updated'] = time();
			        unset($param_values['table_id']);
			        
			        $UpdateTable = updateData(CAOP_TABLES, $param_values, $TableData, "table_id='{$TABLE_ID}'");
			        if ( $UpdateTable['stat'] == RES_SUCCESS )
			        {
			        	$response['result'] = true;
			        	$response['msg'] = 'Таблица успешно обновлена!';
			        }
		        
		        } else $response['msg'] = $TableRM['msg'];
		        
	        } else
	        {
		        $param_values = $HTTP;
		        $param_values['table_doctor_id'] = $USER_PROFILE['doctor_id'];
		        $param_values['table_unix_created'] = time();
		        $param_values['table_unix_updated'] = time();
		        unset($param_values['table_id']);
		        $NewTable = appendData(CAOP_TABLES, $param_values);
		
		        if (  $NewTable[ID] > 0 )
		        {
			
			        $response['result'] = true;
					$response['msg'] = 'Таблица успешно создана!';
			
		        } else
		        {
			        $response['msg'] = 'Проблема с добавлением таблицы';
			        $response['debug']['$NewTable'] = $NewTable;
		        }
	        }
        } else $response['msg'] = 'Не указано название таблицы';
    } else $response['msg'] = 'Не указано подназвание таблицы';
} else $response['msg'] = 'Не указано описание таблицы';
