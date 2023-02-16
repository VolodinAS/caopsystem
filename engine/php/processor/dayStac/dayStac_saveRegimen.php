<?php
$response['stage'] = $action;
//$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$excludeVars = array('visreg_isTemplate');

$varName = array(
	'regimen_dose'                       =>  'Дозировка препарата',
	'regimen_dose_measure_type'          =>  'Измерение дозировки',
	'regimen_dose_period_type'           =>  'Время дозировки',
	'regimen_drug'                       =>  'Название препарата',
	'regimen_freq_amount'                =>  'Количество введений',
	'regimen_freq_period_amount'         =>  'Периодичность',
	'regimen_freq_period_type'           =>  'Временной промежуток',
	'regimen_title'                      =>  'Название схемы лечения'
);

$VisregRM = RecordManipulation($regimen_id, CAOP_DS_REGIMENS, 'regimen_id');
//$response['debug']['$VisregRM'] = $VisregRM;
if ( $VisregRM['result'] )
{
	$VisregData = $VisregRM['data'];
	
	//$response['debug']['$VisregData'] = $VisregData;
	
	$youAreNotFillText = '';
	$isEmptyFound = false;
	foreach ($HTTP as $key=>$value)
	{
		if ( !in_array($key, $excludeVars) )
		{
			$isEmpty = false;
			if ( strlen($value) > 0 )
			{
				if ( ctype_digit($value) )
				{
					if($value==0)
					{
						$isEmpty = true;
					}
				} else
				{
					if ( $value == '0' )
					{
						$isEmpty = true;
					}
				}
			} else
			{
				$isEmpty = true;
			}
			
			if ($isEmpty)
			{
				$isEmptyFound = true;
				if ( strlen($youAreNotFillText) == '' )
				{
					$youAreNotFillText .= 'Вы не заполнили поля: ' . $varName[$key];
				} else
				{
					$youAreNotFillText .= ', ' . $varName[$key];
				}
			}
		}
		
	}
	
	if ( !$isEmptyFound )
	{
		
		$RegimenData = array(
			'regimen_doctor_id'              =>  $USER_PROFILE['doctor_id'],
			'regimen_title'                  =>  $regimen_title,
			'regimen_drug'                   =>  $regimen_drug,
			'regimen_dose'                   =>  $regimen_dose,
			'regimen_dose_measure_type'      =>  $regimen_dose_measure_type,
			'regimen_dose_period_type'       =>  $regimen_dose_period_type,
			'regimen_freq_amount'            =>  $regimen_freq_amount,
			'regimen_freq_period_amount'     =>  $regimen_freq_period_amount,
			'regimen_freq_period_type'       =>  $regimen_freq_period_type,
			'regimen_dasigna'                =>  $regimen_dasigna
		);
		
		//$response['debug']['$RegimenData'] = $RegimenData;
		
		$UpdateRegiment = updateData(CAOP_DS_REGIMENS, $RegimenData, $VisregData, "regimen_id='{$VisregData['regimen_id']}'");
		if ( $UpdateRegiment['stat'] == RES_SUCCESS )
		{
			$response['result'] = true;
			$response['msg'] = 'Назначение сохранено';
		} else
		{
			$response['msg'] = 'Ошибка';
		}
		
	} else $response['msg'] = $youAreNotFillText;
	
	
} else $response['msg'] = $VisregRM['msg'];

