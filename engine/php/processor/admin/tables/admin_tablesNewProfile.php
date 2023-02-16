<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$param_profile = array(
    'profile_title' => $title,
	'profile_table_id' => $table_id,
	'profile_header_index' => $header_string,
	'profile_offset_data' => $offset,
	'profile_fields_json' => json_encode($columns),
	'profile_unix' => time()
);

$AddProfile = appendData(CAOP_TABLE_IMPORT_PROFILES, $param_profile);
if ( $AddProfile[ID] > 0 )
{
	
	$FIELDS_TOTAL = count($columns);
	if ( $FIELDS_TOTAL > 0 )
	{
		
		$FIELDS_SUCCESS = 0;
		foreach ($columns as $column)
		{
			$param_field = array(
			    'field_class' => 'mysqleditor',
				'field_order' => '0',
				'field_sorted' => '1',
				'field_title' => $column,
				'field_title_full' => $column,
				'field_type' => 'text',
				'field_table_id' => $table_id
			);
			
			$NewField = appendData(CAOP_TABLE_FIELDS, $param_field);
			if ( $NewField[ID] > 0 )
			{
				$FIELDS_SUCCESS++;
			}
		}
		
		if ( $FIELDS_SUCCESS == $FIELDS_TOTAL )
		{
			$response['result'] = true;
			$response['msg'] = 'Профиль "'.$title.'" успешно создан';
		} else
		{
			$response['msg'] = 'Не все поля были добавлены';
		}
  
	} else $response['msg'] = 'Нет полей для добавления';
	
} else $response['msg'] = 'Проблем с созданием профиля';