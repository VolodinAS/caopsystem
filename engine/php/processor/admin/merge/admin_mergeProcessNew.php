<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['UPDATED'] = [];

/*
 * $BASE - тот, КОМУ надо всё слить
 * $TARGET - тот, ОТ КОГО надо всё слить, затем - удалить
*/

/*
 * ГЛАВНЫЕ ТАБЛИЦЫ С ИНФОРМАЦИЕЙ О ПАЦИЕНТЕ
 *
 * $CAOP_PATIENTS - список пациентов
 *
 * $CAOP_CANCER - информация о раках пациента
 * $CAOP_CITOLOGY - информация о цитологии пациента
 * $CAOP_JOURNAL - информация о визитах пациента
 * $CAOP_RESEARCH - информация об обследованиях пациентов
 * $CAOP_ROUTE_SHEET - информация о маршрутных листах пациента
 *
 * $CAOP_SCHEDULE_UZI_PATIENTS - информация об УЗИ пациентах
 * $CAOP_NOTICE_F1A - информация об 1А кл. гр.
 * $CAOP_MSKT_MDC - направления на МСКТ в МДЦ
 * $CAOP_DAILY - дневники
 * $CAOP_BLANK_DECLINE - бланки отказа
 * */

$response['debug']['$PATIENTS_DATAS'] = $PATIENTS_DATAS;


$TOTAL_UPDATES = count($PATIENTS_DATAS);
$CURRENT_UPDATES = 0;

foreach ($PATIENTS_DATAS as $desc_index=>$table_patient_data)
{
	$response['debug']['update_rules'][$desc_index] = $table_patient_data;
	
	$new_param_update = array(
	    $table_patient_data['field_patid'] => $base
	);
	$response['debug']['update_rules'][$desc_index]['$new_param_update'] = $new_param_update;
	
	$CheckTargetData = getarr($table_patient_data['table'], "{$table_patient_data['field_patid']}='{$target}'");
	$response['debug']['update_rules'][$desc_index]['count'] = count($CheckTargetData);
	if ( count($CheckTargetData) > 0 )
	{
		$UpdateData = updateData($table_patient_data['table'], $new_param_update, [], "{$table_patient_data['field_patid']}='{$target}'");
		if ($UpdateData['stat'] == RES_SUCCESS)
		{
			$response['debug']['update_rules'][$desc_index]['updated'] = true;
			$CURRENT_UPDATES++;
		} else
			$response['msg'] = 'ПРОИЗОШЛА ОШИБКА ПРИ ОБНОВЛЕНИИ '.$desc_index.'!';
			$response['debug']['update_rules'][$desc_index]['fatal_error'] = true;
			break;
	} else
	{
		$CURRENT_UPDATES++;
		$response['debug']['update_rules'][$desc_index]['updated'] = 'not required';
	}
}

$response['debug']['$CURRENT_UPDATES'] = $CURRENT_UPDATES;
$response['debug']['$TOTAL_UPDATES'] = $TOTAL_UPDATES;

if ( $CURRENT_UPDATES == $TOTAL_UPDATES )
{
	$DeleteTargetPatient = deleteitem(CAOP_PATIENTS, "patid_id='{$target}'");
	if ( $DeleteTargetPatient['result'] === true )
	{
		
		$UpdatePersonalData = updateData(CAOP_PATIENTS, $patientData, [], "patid_id='{$base}'");
		if ( $UpdatePersonalData['stat'] == RES_SUCCESS )
		{
			$response['result'] = true;
		} else
		{
			$response['msg'] = 'Ошибка обновления персональных данных!';
		}
		
	}
} else
{
	$response['msg'] .= "\nКОЛИЧЕСТВО ПРОВЕДЕННЫХ ОБНОВЛЕНИЙ НЕ СОВПАДАЕТ С КОЛИЧЕСТВОМ ВСЕХ ОБНОВЛЕНИЙ";
}