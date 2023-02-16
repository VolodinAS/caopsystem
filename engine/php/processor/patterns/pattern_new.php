<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$RequiredFields = array(
    'pattern_title',
	'pattern_description',
	'pattern_codes'
);

$isChecked = checkRequiredFields($RequiredFields, $_POST);

if ($isChecked === true)
{

	$mkb_arr = explode(';', $pattern_codes);
	
	if ( count($mkb_arr) > 0 )
	{
		$isMKBCorrect = true;
		$mkb_incorrect = [];
		$mkb_correct = [];
		foreach ($mkb_arr as $mkb)
		{
			$MKB_DATA = CheckMKBCode($mkb);
			$mkb_check = $MKB_DATA['value'];
//			$response['debug'][$mkb] = $mkb_check;
			if ( $mkb_check !== false)
			{
				$mkb_correct[] = $mkb_check;
			} else
			{
				$isMKBCorrect = false;
				$mkb_incorrect[] = $mkb;
			}
		}
		if ( $isMKBCorrect )
		{
			$param_values = $_POST;
			$param_values['pattern_codes'] = implode(';', $mkb_correct);
//			$response['debug']['$param_values'] = $param_values;
			$param_values['pattern_created_unix'] = time();
			$param_values['pattern_updated_unix'] = time();
			
			$NewPattern = appendData(CAOP_DIAGNOSIS_PATTERNS, $param_values);
			if ( $NewPattern[ID] > 0 )
			{
				$response['result'] = true;
				$response['msg'] = 'Паттерн успешно добавлен!';
			} else
			{
				$response['msg'] = 'Проблема при добавлении данных';
				$response['debug']['$NewPattern'] = $NewPattern;
			}
		} else
		{
			$response['msg'] = 'Один или несколько из полученных диагнозов написаны некорректно: ' . implode(', ', $mkb_incorrect);
		}
	} else
	{
		$response['msg'] = 'Невозможно распознать паттерн диагнозов!';
	}
	
	

} else
{
	$response['msg'] = 'Вы не заполнили обязательные поля: ' . implode(', ', $isChecked);
}
