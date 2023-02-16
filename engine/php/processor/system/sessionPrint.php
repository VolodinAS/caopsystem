<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

if ( count($_POST) > 0 )
{
	extract($_POST);

	if ( isset($doctype) )
	{

		switch ( $doctype )
		{
			case "stat_howmany_phys_face_cured":
			case "stat_howmany_visits":
			case "dispancer_report":
				if ( strlen($date_from)> 0 && strlen($date_to)>0 )
				{
					$_SESSION[$doctype]['date_from'] = $date_from;
					$_SESSION[$doctype]['date_to'] = $date_to;
					
					if ( $doctype == "dispancer_report" )
					{
					    $_SESSION[$doctype]['doctor_id'] = $doctor_id;
					    $_SESSION[$doctype]['showRepeats'] = $showRepeats;
					}
					$response['result'] = true;
					$response['url'] = '/documentPrint/' . $doctype;
//					$response['debug']['$_SESSION'] = $_SESSION;
				} else
				{
					$response['stat'] = 'nodoctype';
					$response['msg'] = 'Не указан тип сортировки';
				}
			break;
			case "nosology_report":
				$_SESSION[$doctype]['filter'] = $filter;
				$_SESSION[$doctype]['caop']['date_from'] = $caop_date_from;
				$_SESSION[$doctype]['caop']['date_to'] = $caop_date_to;
				$_SESSION[$doctype]['caop']['doctor_id'] = $caop_doctor_id;
				$_SESSION[$doctype]['caop']['diagnosis'] = $caop_diagnosis;
				
				$_SESSION[$doctype]['ds']['date_from'] = $ds_date_from;
				$_SESSION[$doctype]['ds']['date_to'] = $ds_date_to;
				$_SESSION[$doctype]['ds']['doctor_id'] = $ds_doctor_id;
				$_SESSION[$doctype]['ds']['diagnosis'] = $ds_diagnosis;
				$response['url'] = '/documentPrint/' . $doctype;
//				$response['debug']['$_SESSION'] = $_SESSION;
				$response['result'] = true;
				
				switch ($filter)
				{
				    case "caop":
					    $_SESSION[$doctype]['columns'] = 27;
				    break;
				    case "ds":
					    $_SESSION[$doctype]['columns'] = 1;
				    break;
				}
			break;
			case "uzi_single_day":
				$_SESSION[$doctype]['dates_id'] = $dates_id;
				$response['url'] = '/documentPrint/' . $doctype;
				$response['result'] = true;
			break;
			case "uzicaop_talon":
				$_SESSION[$doctype]['patuzi_id'] = $patuzi_id;
				$_SESSION[$doctype]['uzi_ids'] = $uzi_ids;
				$_SESSION[$doctype]['journal_id'] = $journal_id;
				$_SESSION[$doctype]['date_id'] = $date_id;
				$response['url'] = '/documentPrint/' . $doctype;
				$response['result'] = true;
			break;
			case "dilutionMeans":
			case "quartzingTime":
				$_SESSION[$doctype]['nurse_one_id'] = $nurse_one_id;
				$_SESSION[$doctype]['nurse_two_id'] = $nurse_two_id;
				$_SESSION[$doctype]['gen_cleans'] = $gen_cleans;
				$_SESSION[$doctype]['date_from'] = $date_from;
				$_SESSION[$doctype]['skip_weekends'] = $skip_weekends;
				$response['url'] = '/documentPrint/' . $doctype;
				$response['result'] = true;
			break;
			default:
				$response['stat'] = 'unknowndoctype';
				$response['msg'] = 'Неизвестный тип сортировки';
			break;
		}

	} else
	{
		$response['stat'] = 'nodoctype';
		$response['msg'] = 'Не указан тип сортировки';
	}

} else
{
	$response['stat'] = 'nodata';
	$response['msg'] = 'Нет параметров';
}

?>