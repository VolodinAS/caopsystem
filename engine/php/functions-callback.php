<?php
/**
 *
 * Коллбэк-функция. Превращает итем диагноза в читабельный вид
 *
 * @param array $item
 * @return string
 */
function func_dispancer($item = [])
{
	global $DoctorsListId;
	$ds = $item['dispancer_ds_mkb'];
	$ds_text = $item['dispancer_ds_text'];
	$unix = $item['dispancer_accounting_begin_unix'];
	$doctor_id = $item['dispancer_doctor_id'];
	$doc_name = docNameShort($DoctorsListId['id' . $doctor_id]);
	$string = '['.$ds.']';
	if ( strlen($ds_text) > 0 ) $string .= ' ' . $ds_text;
	if ( strlen($doc_name) > 0 ) $string .= ' ('.$doc_name.')';
	$string .= ' от ' . date(DMY, $unix);
	return $string;
//	return 111;
}

/**
 *
 * Адекватное отображение имени врача
 *
 * @param array $doc_arr
 * @return string
 */
function func_doctor_name($doc_arr = [])
{
	return mb_ucwords($doc_arr['doctor_f'] . ' ' . $doc_arr['doctor_i'] . ' ' . $doc_arr['doctor_o']);
}

/**
 *
 * Вывод информации об СПО
 *
 * @param array $spo
 */
function func_spo($spo = [])
{
	global $DoctorsListId;
	$data = '';
	
	$data = date(DMY, $spo['spo_start_date_unix']);
	
	if ($spo['spo_end_date_unix'] > 0)
	{
		$data .= ' - ' . date(DMY, $spo['spo_end_date_unix']);
	} else
	{
		$unix_expired = strtotime("+3 month", $spo['spo_start_date_unix']);
		if ( $unix_expired > time() )
		{
			$data .= ' - сегодня';
		} else
		{
			$data .= ' - ' . date(DMY, $unix_expired);
		}
		
	}
	
	$disp = '';
	if ( $spo['spo_is_dispancer'] == 1 )
	{
		$data = date(DMY, $spo['spo_start_date_unix']) . ' - бессрочно';
		$disp = ' (ДИСПАНСЕРНЫЙ)';
	}
	
	$data = '['.$data.']';
	
	$data .= ', диагноз: '. $spo['spo_mkb_finished'];
	$data .= ', открыт: ' . docNameShort($DoctorsListId['id' . $spo['spo_start_doctor_id']]);
	$data .= $disp;
	
	return $data;
}