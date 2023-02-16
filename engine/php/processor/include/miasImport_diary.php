<?php
$go_next = false;

$isDiaryFields = false;
$DiaryFieldsArray = [];
foreach ($miasTextMainData as $miasDiaryString)
{
	if ($miasDiaryString == '""')
	{
		$isDiaryFields = true;
		continue;
	}
	if ($miasDiaryString == "Ссылка")
	{
		$isDiaryFields = false;
	}
	if ($isDiaryFields) $DiaryFieldsArray[] = $miasDiaryString;
	
}
//		//$response['debug']['$DiaryFieldsArray'] = $DiaryFieldsArray;

// ИЗ ДНЕВНИКА
// 3 - парсим согласно данным из дневника
// 4 - Собираем доступные поля
// 5 - Минимальный список полей - "Время", "Пациент", "Дата рождения"
// 5.1 - При наличии дополнительных полей - сравнить со списком допустимых полей
// 5.2 - Добавить в поля к сформированному списку
// 6 - Пробегаемся по списку полей
// 7 - Формируем данные для добавления в БД
// 8 - Формируем данные для добавления в Приём
//
// ЭТО ДНЕВНИКОВЫЕ ДАННЫЕ, не поддающиеся систематизации. Они должны быть в УНИКАЛЬНОЙ спецификации

$isPatientData = false;
$PatientList = [];
for ($i = 0; $i < count($miasTextMainData); $i++)
{
	
	$str = $miasTextMainData[$i];
	
	if (ifound($str, "Ссылка"))
	{
		$isPatientData = true;
		continue;
	} else if (ifound($str, "записей")) break;
	
	if ($isPatientData)
	{
		$PatientData['TIME'] = trim($miasTextMainData[$i]);
		$i += 2;
		$PatientData['DATA'] = explode("\t", $miasTextMainData[$i]);
		$PatientList[] = $PatientData;
		$PatientData = [];
	}
	
}
//$response['debug']['$PatientList'] = $PatientList;

foreach ($PatientList as $Patient)
{
	// обработка имени "фамилия и о"
	$IMPORT_PATIENT_DATA = [];
	$name = $Patient['DATA'][0];
	$nameData = explode(" ", $name);
	$familia = mb_strtolower(trim($nameData[0]), UTF8);
	$imaot = trim($nameData[1]);
	$imaotData = explode(".", $imaot);
	$ima = mb_strtolower(trim(str_replace(".", "", $imaotData[0])), UTF8);
	$ot = mb_strtolower(trim(str_replace(".", "", $imaotData[1])), UTF8);
	
	$IMPORT_PATIENT_DATA['import_journal_time'] = $Patient['TIME'];
	$IMPORT_PATIENT_DATA['import_patient_name'] = $familia . ' ' . $ima . ' ' . $ot;
	$IMPORT_PATIENT_DATA['import_patient_birth'] = $Patient['DATA'][1];
	$IMPORT_PATIENT_DATA['import_journal_status'] = end($Patient['DATA']);
	if (in_array("Номер карты", $DiaryFieldsArray))
	{
		$IMPORT_PATIENT_DATA['import_patient_ident'] = $Patient['DATA'][2];
	}
	
	$IMPORT_PATIENT_LIST[] = $IMPORT_PATIENT_DATA;
}

$go_next = true;

//$response['debug']['$IMPORT_PATIENT_LIST'] = $IMPORT_PATIENT_LIST;