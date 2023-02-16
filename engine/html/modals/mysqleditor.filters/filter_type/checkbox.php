<?php
//$FieldSet_query = "
//SELECT {$field} FROM {$table} WHERE 1 ORDER BY {$field} ASC
//";
$FieldSet_query = $queryset;
//$response['htmlData'] .= debug_ret($FieldSet_query);
$FieldSet_result = mqc($FieldSet_query);
$FieldSet = mr2a($FieldSet_result);
if ( count($FieldSet) > 0 )
{
	$PreparedData = [];
	$is_use_id = false;
	foreach ($FieldSet as $field_data)
	{
		$value = $field_data[$field];
		if ( strlen($preprocessor) > 0 )
		{
			if ( function_exists($preprocessor) )
			{
				$value = call_user_func($preprocessor, $value);
			} else
			{
				$response['htmlData'] .= 'Функции-препроцессора "'.$preprocessor.'" не существует';
				break;
			}
		}
//		$response['htmlData'] .= debug_ret($value);
		if ( ifound($value, '~') )
		{
//			$response['htmlData'] .= debug_ret($value);
			$is_use_id = true;
		    $value_data = explode('~', $value);
			$PreparedData[$value_data[0]] = $value_data[1];
		} else $PreparedData[] = $value;
		
	}
	
	$PreparedData = array_unique($PreparedData);
	asort($PreparedData);
	if ( !$is_use_id )
		$PreparedData = array_values($PreparedData);
	
//	$response['htmlData'] .= debug_ret($PreparedData);
	
	$index = 0;
	foreach ($PreparedData as $id=>$mkb)
	{
//		$response['htmlData'] .= debug_ret($id);
//		$response['htmlData'] .= debug_ret($mkb);
		$checked = '';
		$default_arr = $_SESSION['mef_filters'][$filter]['data'];
		
//		$response['htmlData'] .= bt_divider(1);
//		$response['htmlData'] .= '>';
//		$response['htmlData'] .= debugnr($is_use_id, '$is_use_id');
//		$response['htmlData'] .= '<';
//		$response['htmlData'] .= bt_divider(1);
//		$response['htmlData'] .= debugnr($default_arr, 'default_arr');
		
		if ( $is_use_id )
		{
			foreach ($default_arr as $key=>$default)
			{
				if ( $id == $default )
				{
					$checked = 'checked';
					break;
				}
			}
		} else
		{
			foreach ($default_arr as $default)
			{
				if ( $mkb == $default )
				{
					$checked = 'checked';
					break;
				}
			}
		}
		
		$ider = ( $is_use_id ) ? $id : $mkb;
		
		$response['htmlData'] .= '
		<input class="form-check-input " type="checkbox" name="filter_data[]" id="filter_data_'.$index.'" value="'.$ider.'" '.$checked.'>
		<label class="form-check-label box-label" for="filter_data_'.$index.'"><span></span>'.$mkb.'</label>
		<div class="dropdown-divider"></div>
		';
		$index++;
	}
 
} else $response['htmlData'] .= 'Для выбранного поля нет значений для фильтрации';
