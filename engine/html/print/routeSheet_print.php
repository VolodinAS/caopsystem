<?php
$PrintParams = explode('/', $request_params);

if ( $PrintParams[0] > 0 )
{
	$RouteSheetPrint = getarr(CAOP_ROUTE_SHEET, "rs_id='{$PrintParams[0]}'");
	if ( count($RouteSheetPrint) > 0 )
	{
		$RouteSheetPrint = $RouteSheetPrint[0];
		$PatientData = getPatientDataById($RouteSheetPrint['rs_patid']);
		if ($PatientData['result'] == true && $PatientData['error'] == false)
		{
			$PatientData = $PatientData['data']['personal'];
//			debug($RouteSheetPrint);
			$DoctorData = $DoctorsListId['id' . $RouteSheetPrint['rs_doctor_id']];
			$RouteSheetPrint['rs_ds_dead_date'] = ( strlen($RouteSheetPrint['rs_ds_dead_date']) > 0 ) ? $RouteSheetPrint['rs_ds_dead_date'] : '&nbsp';
			require_once ( "engine/html/title_begin_print.php" );
			require_once ( "engine/html/routeSheetHtml.php" );
			require_once ( "engine/html/title_end_print.php" );
		} else
		{
			bt_notice('Такого пациента не существует',BT_THEME_DANGER);
		}

		exit();

	} else bt_notice('Такого бланка не существует',BT_THEME_DANGER);

} else bt_notice('Не выбран бланк для распечатки',BT_THEME_DANGER);