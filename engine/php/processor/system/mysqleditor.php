<?php

$DATA_SEPARATOR = '|||';

$response['stage'] = 'mysqleditor';
$response['post'] = $_POST;
$response['debug']['$_COOKIE'] = $_COOKIE;
$response['needFocus'] = false;
//$TABLE = ($response['post']['data_assoc'] == "1") ? $TablesT[$response['post']['data_table']] : $response['post']['data_table'];
$TABLE = $response['post']['data_table'];
$FIELD_ID = $response['post']['data_fieldid'];
$ID = $response['post']['data_id'];
$FIELD = $response['post']['data_field'];
$VALUE = mres($response['post']['data_value']);
$ACTION = $response['post']['data_action'];
$UNIX_UPDATE = $response['post']['data_unixfield'];
$ADEQUATE = $response['post']['data_adequate'];
$RETURN_FUNC = $response['post']['data_return_func'];
$SEPARATOR = $response['post']['data_separator'];

//$response['response']['params']['$TABLE'] = $TABLE;
//$response['response']['params']['$FIELD_ID'] = $FIELD_ID;
//$response['response']['params']['$ID'] = $ID;
//$response['response']['params']['$FIELD'] = $FIELD;
//$response['response']['params']['$VALUE'] = $VALUE;
$response['response']['params']['$ACTION'] = $ACTION;
//$response['response']['params']['$UNIX_UPDATE'] = $UNIX_UPDATE;
//$response['response']['params']['$ADEQUATE'] = $ADEQUATE;
//$response['response']['params']['$RETURN_FUNC'] = $RETURN_FUNC;
//$response['response']['params']['$SEPARATOR'] = $SEPARATOR;
$response['ID'] = '-2';

if ($response['response']['params']['$ACTION'] == "edit")
{
	$Data = getarr($TABLE, $FIELD_ID . "='{$ID}'");
	if (count($Data) == 1)
	{
//		$response['response']['params']['$Data'] = $Data;

		$ALLGOODCHECKED = true;
		if ( notnull($ADEQUATE) )
		{
			switch ($ADEQUATE)
			{
				case "DATETOUNIX":
					if ( strlen($VALUE) > 0 )
					{
						$VALUE = nospaces($VALUE);
						$date_arr = explode(".", $VALUE);
						if ( count($date_arr) == 3 )
						{
							if ( checkdate($date_arr[1], $date_arr[0], $date_arr[2]) )
							{
								$unix = birthToUnix($VALUE);
								if ( intval($unix) )
								{
									$VALUE = $unix;
								} else $VALUE = '';
							} else $VALUE = '';
						} else $VALUE = '';
					} else $VALUE = '';
				break;
				case "MKB":
					$response['debug']['ADEQUATE'] = $ADEQUATE;
					if ( strlen($VALUE) > 0 )
					{
						$response['debug']['$VALUE'] = $VALUE;
						$personal = $_COOKIE['login'] == "97002";
						$MKB_DATA = CheckMKBCode($VALUE, $personal);
						$VALUE = $MKB_DATA['value'];
						$response['debug']['$MKB_DATA'] = $MKB_DATA;
						if ( $VALUE === false )
						{
							$ALLGOODCHECKED = false;
							$response['stat'] = 'error';
							$response['msg'] = 'НЕВЕРНО ВВЕДЕН ДИАГНОЗ ПО МКБ-10' . "\n\n" . 'ДАННЫЕ ДИАГНОЗА НЕ ОБНОВЛЕНЫ!';
							$response['needFocus'] = true;
						}
					} else
					{
						$VALUE = '';
					}
				break;
				case "HOURMIN":
					$timeData = explode(':', $VALUE);
					if ( count($timeData) == 2 )
					{
						$hours = $timeData[0];
						$mins = $timeData[1];
						
						if ( is_numeric($hours) )
						{
							if ( is_numeric($mins) )
							{
								$VALUE_ARR = array(
								    $hours,
									$mins
								);
								// всё хорошо
							} else
							{
								$ALLGOODCHECKED = false;
								$response['msg'] = 'МИНУТЫ должны быть числами';
							}
						} else
						{
							$ALLGOODCHECKED = false;
							$response['msg'] = 'ЧАСЫ должны быть числами';
						}
						
					} else
					{
						$ALLGOODCHECKED = false;
						$response['msg'] = 'Неверно задано время';
					}
				break;
				case "HOLIDAYS_BEGIN":
					$VALUE = ssboy($VALUE);
					if ( $VALUE === false )
					{
						$ALLGOODCHECKED = false;
						$response['msg'] = 'Неверно указана дата начала';
					}
				break;
				case "HOLIDAYS_END":
					$VALUE = ssboy($VALUE, true);
					if ( $VALUE === false )
					{
						$ALLGOODCHECKED = false;
						$response['msg'] = 'Неверно указана дата окончания';
					}
				break;
			}
		}
		
		if ( $ALLGOODCHECKED )
		{
			
			if ( notnull($SEPARATOR) )
			{
				$separatorData = explode($DATA_SEPARATOR, $SEPARATOR);
				if ( count($separatorData) == 2 )
				{
					$separator = $separatorData[0];
					$data = $separatorData[1];
					$Fields = explode($separator, $data);
					if ( notnull($ADEQUATE) && $ADEQUATE == "HOURMIN" )
					{
						if ( count($VALUE_ARR) > 0 && count($VALUE_ARR) == count($Fields) )
						{
							$newParams = [];
							$index = 0;
							foreach ($Fields as $field)
							{
								$newParams[$field] = $VALUE_ARR[$index];
								$index++;
							}
							// TODO КОСТЫЛЬ
							$newParams['time_order'] = (int)$newParams['time_hour'] * 60 + (int)$newParams['time_min'];
//							$ALLGOODCHECKED = false; // TODO УБРАТЬ
						} else
						{
							$ALLGOODCHECKED = false;
							$response['msg'] = 'data-separator не соответвует data-adequate';
						}
					} else
					{
						$ALLGOODCHECKED = false;
						$response['msg'] = 'Неверная связка data-separator и data-adequate';
					}
				} else
				{
					$ALLGOODCHECKED = false;
					$response['msg'] = 'data-separator указан неверно';
				}
			} else
			{
				$newParams = array(
					$FIELD => mres($VALUE)
				);
			}
			
			if ( strlen($UNIX_UPDATE) > 0 )
			{
				$UnixFieldsArr = explode(",", $UNIX_UPDATE);
				foreach ($UnixFieldsArr as $UpdateField)
				{
					$newParams[$UpdateField] = time();
				}
				
			}
		
		} else
		{
//			$response['msg'] = 'Проблема с проверкой целостности данных (1)';
		}
		
//		$response['debug']['$VALUE_ARR'] = $VALUE_ARR;
//		$response['debug']['$Fields'] = $Fields;
//		$response['debug']['$newParams'] = $newParams;
		
		if ( $ALLGOODCHECKED )
		{
			$Update = updateData($TABLE, $newParams, array(), $FIELD_ID . "='{$ID}'");
			if ($Update['stat'] == RES_SUCCESS) {
				$response['ID'] = $ID;
				$response['debug']['$Update'] = $Update;
				$response['result'] = true;
				$response['stat'] = RES_SUCCESS;
//				$FUNC = $RETURN_FUNC;
				if ( strlen($RETURN_FUNC) > 0 )
				{
					switch ($RETURN_FUNC)
					{
						case "research_string":
							$query_research = "SELECT * FROM {$CAOP_RESEARCH} INNER JOIN {$CAOP_RESEARCH_TYPES} ON {$CAOP_RESEARCH}.research_type={$CAOP_RESEARCH_TYPES}.type_id WHERE {$CAOP_RESEARCH}.research_id='{$ID}'";
							$result_research = mqc($query_research);
							$amount_research = mnr($result_research);
							if ( $amount_research > 0 )
							{
								$ResearchReturn = mr2a($result_research, 1);
								$response['debug']['$ResearchReturn'] = $ResearchReturn;
								$morph = getMorphOfResearch($ResearchReturn);
								
								$response['return_data'] = $ResearchReturn['type_title'] . ' ' . $ResearchReturn['research_area'] . ' от ' . $ResearchReturn['patidcard_patient_done'] . ' - ' . $ResearchReturn['research_result'] . $morph;
							}
							break;
						case "citology_string":
							$query_citology = "SELECT * FROM {$CAOP_CITOLOGY} INNER JOIN {$CAOP_CITOLOGY_TYPE} ON {$CAOP_CITOLOGY}.citology_analise_type={$CAOP_CITOLOGY_TYPE}.type_id WHERE {$CAOP_CITOLOGY}.citology_id='{$ID}'";
							$result_citology = mqc($query_citology);
							$amount_citology = mnr($result_citology);
							if ( $amount_citology > 0 )
							{
								$CitologyReturn = mr2a($result_citology, 1);
								$response['return_data'] = 'Цитология ('.$CitologyReturn['type_title'].') №'.$CitologyReturn['citology_result_id'].' от ' . $CitologyReturn['patidcard_patient_done'] . ' - ' . $CitologyReturn['citology_result_text'];
							}
							break;
						case "journal_current_move_button":
							$response['return_data'] = ( (int)$VALUE == 0 ) ? html_img_gen("/engine/images/icons/journal_not_need_move.png", 'height="24"', 1) : html_img_gen("/engine/images/icons/journal_need_move.png", 'height="24"', 1);
							$response['return_value'] = ( (int)$VALUE == 0 ) ? 1 : 0;
							break;
						case "fast_move_icon":
							$JournalIt = getarr(CAOP_JOURNAL, "journal_id='{$ID}'");
							if ( count($JournalIt) == 1 )
							{
								$JournalIt = $JournalIt[0];
								$VALUE = $JournalIt['journal_need_move'];
								$response['return_data'] = ( strlen($VALUE) <= 1 ) ? html_img_gen("/engine/images/icons/journal_not_need_move.png", 'height="24"', 1) : html_img_gen("/engine/images/icons/journal_need_move.png", 'height="24"', 1);
							} else
							{
								$response['return_data'] = 'ERROR';
							}
							
							break;
						case "value":
							$response['return_data'] = $VALUE;
						break;
						default:
							$response['return_data'] = "Тип установленной функции возврата НЕ ОПРЕДЕЛЕН";
							break;
					}
				} else
				{
					$response['return_data'] = $VALUE;
				}
				$response['msg'] = 'Данные ' . $FIELD . '=' . $VALUE . ' успешно обновлены для ' . $FIELD_ID . '=' . $ID;
			} else {
				$response['stat'] = 'error';
				$response['msg'] = $Update;
			}
		} else
		{
//			$response['msg'] = 'Проблема с проверкой целостности данных (2)';
		}


	} else {
		if (count($Data) == 0) {
			$response['stat'] = 'error';
			$response['msg'] = 'no records';
		} else {
			$response['stat'] = 'error';
			$response['msg'] = 'to much ids records';
		}
	}
} else
	if ($response['response']['params']['$ACTION'] == "remove") {
		$Delete = deleteitem($TABLE, $FIELD_ID . "='{$ID}'");
		if ($Delete['result'] === true) {
			$response['result'] = true;
			$response['stat'] = RES_SUCCESS;
			$response['msg'] = 'Данные ' . $FIELD_ID . '=' . $ID . ' удалены';
//			$response['response']['params']['$Delete'] = $Delete;
		} else {
			$response['stat'] = 'error';
			$response['msg'] = $Delete;
		}
	} else
		if ($response['response']['params']['$ACTION'] == "add") {
			print_r($_POST);
			$preset = base64_decode($_POST['data_preset']);
			parse_str($preset, $presetArray);
			$AddArray = array(
				$_POST['data_field'] => $_POST['data_value']
			);
			$AppendArray = array_merge($AddArray, $presetArray);
			
			$response['debug']['$AppendArray'] = $AppendArray;
			
			$AppendData = appendData($TABLE, $AppendArray);
			if ($AppendData[ID] > 0) {
				$response['ID'] = $AppendData[ID];
				$response['result'] = true;
				$response['stat'] = RES_SUCCESS;
				$response['msg'] = 'Новая запись добавлена';
//				$response['response']['params']['$AppendData'] = $AppendData;
				
				if ( strlen($RETURN_FUNC) > 0 )
				{
					
					switch ($RETURN_FUNC)
					{
						case "research_string":
							$query_research = "SELECT * FROM {$CAOP_RESEARCH} INNER JOIN {$CAOP_RESEARCH_TYPES} ON {$CAOP_RESEARCH}.research_type={$CAOP_RESEARCH_TYPES}.type_id WHERE {$CAOP_RESEARCH}.research_id='{$ID}'";
							$result_research = mqc($query_research);
							$amount_research = mnr($result_research);
							if ( $amount_research > 0 )
							{
								$ResearchReturn = mr2a($result_research, 1);
								$response['return_data'] = $ResearchReturn['type_title'] . ' ' . $ResearchReturn['research_area'] . ' от ' . $ResearchReturn['patidcard_patient_done'] . ' - ' . $ResearchReturn['research_result'];
							}
							break;
						case "citology_string":
							$query_citology = "SELECT * FROM {$CAOP_CITOLOGY} INNER JOIN {$CAOP_CITOLOGY_TYPE} ON {$CAOP_CITOLOGY}.citology_analise_type={$CAOP_CITOLOGY_TYPE}.type_id WHERE {$CAOP_CITOLOGY}.citology_id='{$ID}'";
							$result_citology = mqc($query_citology);
							$amount_citology = mnr($result_citology);
							if ( $amount_citology > 0 )
							{
								$CitologyReturn = mr2a($result_citology, 1);
								$response['return_data'] = 'Цитология ('.$CitologyReturn['type_title'].') №'.$CitologyReturn['citology_result_id'].' от ' . $CitologyReturn['patidcard_patient_done'] . ' - ' . $CitologyReturn['citology_result_text'];
							}
							break;
						case "journal_current_move_button":
						    $response['return_data'] = ( (int)$VALUE == 0 ) ? BT_ICON_REGIMEN_DONE : BT_ICON_DRUG_FREQ_PERIOD;
						break;
						default:
							$response['return_data'] = "Тип установленной функции возврата НЕ ОПРЕДЕЛЕН";
						break;
					}
				} else
				{
					$response['return_data'] = $VALUE;
				}
				
			} else {
				$response['stat'] = 'error';
				$response['msg'] = $Update;
			}


		} else {
			$response['stat'] = 'error';
			$response['msg'] = 'action not exists';
		}
		
function research_string()
{
    return 'this is research string';
}