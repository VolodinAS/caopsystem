<?php
$isPersonal = false;
if (strlen($request_params) > 0) {
	$RequestParamsData = explode('/', $request_params);
//	debug($RequestParamsData);

	if ( $RequestParamsData[0] > 0 )
	{
		$isPersonal = true;
	}
}

if ( $isPersonal )
{
	$PatientData = getPatientDataById($RequestParamsData[0]);

	if ( $PatientData['result'] && !$PatientData['error'] )
	{
		$PatientData = $PatientData['data']['personal'];

//		debug($PatientData);

		bt_notice('<b>Пациент:</b> ' . editPersonalDataLink(mb_ucwords($PatientData['patid_name']), $PatientData['patid_id']) . ', ' . $PatientData['patid_birth']. ' г.р.',BT_THEME_PRIMARY );

		echo '
		<button onclick="location.href=\'/routeSheet/'.$PatientData['patid_id'].'/add\'" type="button" class="btn btn-primary">Добавить маршрутный лист</button><br><br>
		';

		$RouteSheets = getarr(CAOP_ROUTE_SHEET, "rs_patid='{$PatientData['patid_id']}'", "ORDER BY rs_update_unix DESC");
		if ( count($RouteSheets) > 0 )
		{
			spoiler_begin('Список маршрутных листов ('.count($RouteSheets).')', 'rs_'.$PatientData['patid_id'], '');
			foreach ($RouteSheets as $routeSheet) {
				spoiler_begin('<b>Диагноз:</b> '.$routeSheet['rs_ds_text'], 'rsdata'.$routeSheet['rs_id'], '');
				echo '
				<b>Лист составлен:</b> '.$routeSheet['rs_fill_date'].'<br>
				<button onclick="window.open(\'/routeSheetPrint/'.$routeSheet['rs_id'].'\')" type="button" class="btn btn-primary">
					<i class="bi bi-printer-fill"></i>		
				</button>
				<button onclick="location.href=\'/routeSheet/'.$PatientData['patid_id'].'/edit/'.$routeSheet['rs_id'].'\'" type="button" class="btn btn-secondary">Редактировать</button>
				<button onclick="if (confirm(\'Вы действительно хотите удалить этот маршрутный лист?\')) window.location.href=\'/routeSheet/'.$PatientData['patid_id'].'/remove/'.$routeSheet['rs_id'].'\'" type="button" class="btn btn-danger">Удалить</button> 
				';
//				debug($routeSheet);

				spoiler_end();
				echo '<br>';
			}
			spoiler_end();

		} else
		{
			bt_notice('Нет маршрутных листов',BT_THEME_SECONDARY);
		}

		if ( $RequestParamsData[1] == "add" )
		{
			$rsEmpty = false;
			if ( count($RouteSheets) > 0 )
			{
				$RouteSheetEmpty = getarr(CAOP_ROUTE_SHEET, "rs_patid='{$PatientData['patid_id']}' AND rs_fill_date=''", "ORDER BY rs_id DESC LIMIT 1");
				if ( count($RouteSheetEmpty) == 0 )
				{
					$rsEmpty = true;
				} else
				{
					$RouteSheetEmpty = $RouteSheetEmpty[0];
				}
			} else
			{
				$rsEmpty = true;
			}

			if ( $rsEmpty )
			{
				$paramValues = array(
					'rs_patid'  =>  $PatientData['patid_id'],
					'rs_doctor_id'  =>  $USER_PROFILE['doctor_id'],
					'rs_update_unix'    =>  time()
				);
				$NewRS = appendData(CAOP_ROUTE_SHEET, $paramValues);
				jsrefresh();
				exit();
			}
			echo '<br>';
			bt_notice('<b>Редактирование нового маршрутного листа пациента</b>', BT_THEME_WARNING);
			$RouteSheetForm = $RouteSheetEmpty;
			include "engine/html/route_sheet.php";
		} else
		{
			if ( $RequestParamsData[1] == "edit" )
			{
				if ( $RequestParamsData[2] > 0 )
				{
					$RouteSheetEdit = getarr(CAOP_ROUTE_SHEET, "rs_id='{$RequestParamsData[2]}'");
					if ( count($RouteSheetEdit) > 0 )
					{
						$RouteSheetForm = $RouteSheetEdit[0];
						bt_notice('<b>Редактирование нового маршрутного листа пациента №'.$RouteSheetForm['rs_id'].'</b>', BT_THEME_WARNING);
						include "engine/html/route_sheet.php";
					} else bt_notice('Такого маршрутного листа не существует',BT_THEME_DANGER);

				} else bt_notice('Не выбран маршрутный лист для редактирования',BT_THEME_DANGER);
			} else
			{
				if ( $RequestParamsData[1] == "remove" )
				{
					if ( $RequestParamsData[2] > 0 )
					{
						$RouteSheetEdit = getarr(CAOP_ROUTE_SHEET, "rs_id='{$RequestParamsData[2]}'");
						if ( count($RouteSheetEdit) > 0 )
						{
							$RouteSheetEdit = $RouteSheetEdit[0];
							$DeleteRS = deleteitem(CAOP_ROUTE_SHEET, "rs_id='{$RouteSheetEdit['rs_id']}'");
							if ( $DeleteRS['result'] == true )
							{
								jsrefresh('/routeSheet/'.$PatientData['patid_id']);
								exit();
							}
						} else bt_notice('Такого маршрутного листа не существует',BT_THEME_DANGER);

					} else bt_notice('Не выбран маршрутный лист для редактирования',BT_THEME_DANGER);
				}
			}
		}

	} else
	{
		bt_notice($PatientData['msg'], BT_THEME_WARNING);
	}
} else
{
	$BLANK_TABLE_FIELD_PATID = 'rs_patid';
	$BLANK_LIST_DESC = 'Маршрутные лист ID: ';
	$BLANK_TABLE_FIELD_ID = 'rs_id';
	$BLANK_PREFIX_MAIN = 'rs';
	$BLANK_SCRIPT = 'routeSheet';
	$BLANK_LIST_FULLEMPTY = 'Список маршрутных листов пуст';
 
	$RouteSheets = getarr(CAOP_ROUTE_SHEET, 1, "ORDER BY rs_update_unix DESC");
	$CountRouteSheets = count($RouteSheets);
	if ( $CountRouteSheets > 0 )
	{
		bt_notice('Документов: ' . $CountRouteSheets);
		foreach ($RouteSheets as $blank) {
			$patient_id = $blank[$BLANK_TABLE_FIELD_PATID];
			$PatientData = getPatientDataById($patient_id);
			if ( $PatientData ['result'] === true )
			{
				$PatientData = $PatientData['data']['personal'];
				spoiler_begin($BLANK_LIST_DESC . $blank[$BLANK_TABLE_FIELD_ID] . ': ' . mb_ucwords($PatientData['patid_name']) . ', ' . $PatientData['patid_birth'] . ' г.р.', $BLANK_PREFIX_MAIN . 'all_'.$blank[$BLANK_TABLE_FIELD_ID], '');
				debug($blank);
				?>
                <button class="btn btn-sm btn-primary" onclick="window.open('/<?=$BLANK_SCRIPT;?>/<?=$PatientData['patid_id'];?>')" type="button">Весь список</button>
				<?php
				spoiler_end();
			}
		}
	} else
	{
		bt_notice($BLANK_LIST_FULLEMPTY ,BT_THEME_SECONDARY);
	}
}
?>

<script defer language="JavaScript" type="text/javascript" src="/engine/js/routeSheet.js?<?=rand(0,1000000)?>"></script>
