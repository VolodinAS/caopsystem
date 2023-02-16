<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

// 1 - проверяем таблицу на существование
$TableExists_query = "
		SHOW TABLE STATUS
		FROM ".DB_NAME."
		WHERE Name = '{$table}';
		";
$TableExists_result = mqc($TableExists_query);
$TableExists = mr2a($TableExists_result);



if ( count($TableExists) > 0 )
{
	// 2 - таблица существует, проверяем наличие поля в таблице, если related=0
	$TableStructure = getcolumns2($table);
	$go_next = false;
	if ( $related == "1" )
	{
		$go_next = true;
	} else
	{
		if ( array_key_exists($field, $TableStructure) )
		{
			$go_next = true;
		} else $response['msg'] = 'Поля "'.$field.'" в таблице "'.$table.'" не существует';
	}
	
	if ( $go_next )
	{
		// 3 - проверяем наличие типа фильтрации
		if ( in_array($type, $Filters) )
		{
			$go_next = true;
		} else $response['msg'] = 'Фильтра "'.$type.'" не существует';
	}
	
	if ( $go_next )
	{
		// 4 - проверяем наличие названия фильтра
		if ( strlen($filter) > 0 )
		{
			$go_next = true;
		} else $response['msg'] = 'Не указано название для фильтра';
	}
	
	if ( $go_next )
	{
	    /*$_SESSION['mef_filters'][$filter]['enabled'] = true;
	    $_SESSION['mef_filters'][$filter]['title'] = $filter;
	    $_SESSION['mef_filters'][$filter]['table'] = $table;
	    $_SESSION['mef_filters'][$filter]['field'] = $field;
	    $_SESSION['mef_filters'][$filter]['type'] = $type;
	    $_SESSION['mef_filters'][$filter]['related'] = $related;
	    $_SESSION['mef_filters'][$filter]['preprocessor'] = $preprocessor;
	    $_SESSION['mef_filters'][$filter]['postprocessor'] = $postprocessor;
	    $_SESSION['mef_filters'][$filter]['data'] = [];*/
//		unset($_SESSION['mef_filters']);
		
		
		switch ($act)
		{
			case "modal":
				$response['result'] = true;
				$response['htmlData'] = '<h4>';
				if ( $field_header )
				{
					$response['htmlData'] .= 'Настройка фильтрации для поля "'.trim($field_header).'"';
				} else
				{
					$response['htmlData'] .= 'Настройка фильтрации';
				}
				
				$response['htmlData'] .= '</h4>';
				$response['htmlData'] .= bt_divider(1);
				
				$response['htmlData'] .= '<form id="mysqleditor_filters_form">';
				$response['htmlData'] .= '<input type="hidden" name="table" id="table" value="'.$table.'">';
				$response['htmlData'] .= '<input type="hidden" name="field" id="field" value="'.$field.'">';
				$response['htmlData'] .= '<input type="hidden" name="related" id="related" value="'.$related.'">';
				$response['htmlData'] .= '<input type="hidden" name="filter" id="filter" value="'.$filter.'">';
				$response['htmlData'] .= '<input type="hidden" name="type" id="type" value="'.$type.'">';
				$response['htmlData'] .= '<input type="hidden" name="preprocessor" id="preprocessor" value="'.$preprocessor.'">';
				$response['htmlData'] .= '<input type="hidden" name="postprocessor" id="postprocessor" value="'.$postprocessor.'">';
				$response['htmlData'] .= '<input type="hidden" name="queryset" id="queryset" value="'.$queryset.'">';
				$response['htmlData'] .= '<input type="hidden" name="use_equal" id="queryset" value="'.$use_equal.'">';
				$response['htmlData'] .= '<input type="hidden" name="title" id="title" value="'.$title.'">';
				
				$checked = 'checked';
				if ( isset($_SESSION['mef_filters'][$filter]) )
				{
					if ( !$_SESSION['mef_filters'][$filter]['enabled'] ) $checked = '';
				}
				$response['htmlData'] .= '
				<input class="form-check-input" type="checkbox" name="filter_enabled" id="filter_enabled" value="1" '.$checked.'>
				<label class="form-check-label box-label" for="filter_enabled"><span></span><b>Включить</b></label>
				';
				$response['htmlData'] .= bt_divider(1);
				
				switch ($type)
				{
					case MEF_TEXT:
						require_once("engine/html/modals/mysqleditor.filters/filter_type/text.php");
					break;
					case MEF_CHECKBOX:
						require_once("engine/html/modals/mysqleditor.filters/filter_type/checkbox.php");
					break;
					case MEF_DATE:
						require_once("engine/html/modals/mysqleditor.filters/filter_type/date.php");
					break;
					default:
						$filter_dom = "Тип фильтра в списке не найден";
					break;
				}
				
				$response['htmlData'] .= $filter_dom;
				
				$response['htmlData'] .= bt_divider(1);
				$response['htmlData'] .= '
				<button type="submit" class="btn btn-lg btn-primary col" id="mysqleditor_filters_form_button_apply">
					Применить
				</button>
				<div class="dropdown-divider"></div>
				<button type="submit" class="btn btn-sm btn-secondary col" id="mysqleditor_filters_form_button_reset">
					Сброс фильтра
				</button>
				';
				
				$response['htmlData'] .= '</form>';
			break;
			case "save":
				$_SESSION['mef_filters'][$filter]['enabled'] = $filter_enabled;
				$_SESSION['mef_filters'][$filter]['title'] = $filter;
				$_SESSION['mef_filters'][$filter]['table'] = $table;
				$_SESSION['mef_filters'][$filter]['field'] = $field;
				$_SESSION['mef_filters'][$filter]['type'] = $type;
				$_SESSION['mef_filters'][$filter]['related'] = $related;
				$_SESSION['mef_filters'][$filter]['use_equal'] = $use_equal;
				$_SESSION['mef_filters'][$filter]['preprocessor'] = $preprocessor;
				$_SESSION['mef_filters'][$filter]['postprocessor'] = $postprocessor;
				
				
				if ( $filter_data )
				{
					switch ($type)
					{
					    case MEF_TEXT:
						    $response['result'] = true;
					        $filter_data_arr = explode(';', $filter_data);
					        $filter_clean = [];
					        foreach ($filter_data_arr as $filter_content)
					        {
					        	if ( strlen($filter_content) > 0 ) $filter_clean[] = $filter_content;
					        }
						    $_SESSION['mef_filters'][$filter]['data'] = $filter_clean;
					    break;
						
						case MEF_CHECKBOX:
						    $response['result'] = true;
						    $_SESSION['mef_filters'][$filter]['data'] = $filter_data;
					    break;
						case MEF_DATE:
							$response['result'] = true;
							$_SESSION['mef_filters'][$filter]['data']['accuracy'] = $filter_data['accuracy'][0];
							$_SESSION['mef_filters'][$filter]['data']['from'] = $filter_data['from'][0];
							$_SESSION['mef_filters'][$filter]['data']['to'] = $filter_data['to'][0];
						break;
						default:
					        $response['msg'] = 'Такого типа фильтра не существует';
					    break;
					}
				} else $response['msg'] = 'Не указаны данные для фильтра';
			break;
			case "reset":
				$response['result'] = true;
			    unset($_SESSION['mef_filters'][$filter]);
			break;
			default:
				$response['msg'] = 'Неизвестный тип действия MySQLEditor.Filters';
			break;
		}
	}
	
} else $response['msg'] = 'Таблицы "'.$table.'" не существует';