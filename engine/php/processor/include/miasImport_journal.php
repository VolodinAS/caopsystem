<?php
$go_next = false;
// ИЗ ЖУРНАЛА
// 3 - парсим согласно данным из журнала

// 1 - парсим поля
$GotFields = array();
$isFields = false;
$lastFieldIndex = -1;

$fieldsString = '';
foreach ($miasTextMainData as $miasString)
{
	//	определяем строку, в которой содержатся столбцы
	if ( ifound($miasString, 'Номер карты') )
	{
	    $fieldsString = $miasString;
	}
}
if ( strlen($fieldsString) > 0 )
{
	$GotFields = explode("\t", $fieldsString);
    if ( count($GotFields) > 0 )
    {
	    $go_next = true;
    }
}

/**
 * Данный цикл уже не требуется, так как столбцы в одной строке
 */
//foreach ($miasTextMainData as $miasString)
//{
//	if ($isFields)
//	{
//		if (ifound($miasString, $IsListText))
//		{
//
//		} else
//		{
//			if (ifound($miasString, $EndListText))
//			{
//				break;
//			} else $GotFields[] = $miasString;
//		}
//
//	} else
//	{
//		if (ifound($miasString, $StartFieldText))
//		{
//			$isFields = true;
//		}
//	}
//}

if ( $go_next )
{
	$go_next = false;
	$response['debug']['$GotFields'] = $GotFields;
	
	// 2 - парсим список пациентов
	$PatientsListText = array();
	foreach ($miasTextMainData as $miasString)
	{
		if ( ifound($miasString, "Номер карты") )
		{
			// Так как столбы находятся теперь в таблице
		} else
		{
			$miasString = trim($miasString);
			$miasPatientData = explode($IsListText, $miasString);
//			$response['debug']['$miasPatientData'][] = $miasPatientData;
			if (count($miasPatientData) == count($GotFields))
			{
				$PatientsListText[] = $miasPatientData;
			}
		}
	}
	
	if ( count($PatientsListText) > 0 )
	{
	    $go_next = true;
	}
}

if ( $go_next )
{
	$go_next = false;
//	$response['debug']['$PatientsListText'] = $PatientsListText;
	
	if ($importSettings == "ignore")
	{
		$UsefulFields[] = 'Статус';
	}

	// 3 - сверяем переданные поля с необходимыми
	$isCorrect = true;
	$IndexedFields = array();
	foreach ($UsefulFields as $usefulField)
	{
		if (!in_array($usefulField, $GotFields))
		{
			$isCorrect = false;
		} else
		{
			$index = array_search($usefulField, $GotFields);
			$IndexedFields[$usefulField] = $index;
		}
	}
	// 4 - Если все поля сформированы верно
	if ($isCorrect)
	{
		$go_next = true;
	} else
	{
		$response['msg'] = 'Переданы некорректные типы столбцов!';
	}
}

if ( $go_next )
{
	$go_next = false;
	// 5 - Корректируем адрес пациента
	foreach ($PatientsListText as $patientData)
	{
		$PatientData = array();
		
		foreach ($IndexedFields as $indexedField => $index)
		{
			if (ifound($indexedField, 'ический адрес пациента'))
			{
				if (strlen($patientData[$index]) < 5)
				{
//						//$response['debug'][] = array($patientData, $index, $indexedField, $IndexedFields['Адрес регистрации пациента']);
					$patientData[$index] = $patientData[$IndexedFields['Адрес регистрации пациента']];
				}
			}
			$PatientData[$indexedField] = $patientData[$index];
		}
	}
	
	//$response['debug']['$PatientsListText'] = $PatientsListText;
	
	foreach ($PatientsListText as $PatientData)
	{
		$IMPORT_PATIENT_DATA = [];
		foreach ($MaximalFieldPatientsList as $listName => $listData)
		{
			$indexThrowIndexlist = $IndexedFields[$listName];
			if ( notnull($indexThrowIndexlist) )
			{
				$PatientDataItem = $PatientData[$indexThrowIndexlist];
				$IMPORT_PATIENT_DATA[$listData['title']] = $PatientDataItem;
			} else
			{
				//$IMPORT_PATIENT_DATA[$listName . '_error'] = $indexThrowIndexlist;
			}
			
		}
		$IMPORT_PATIENT_LIST[] = $IMPORT_PATIENT_DATA;
	}
}

if ( count($IMPORT_PATIENT_LIST) > 0 )
{
    $go_next = true;
}