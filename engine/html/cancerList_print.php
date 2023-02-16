<style>
	@media print{
		@page {size: landscape}
	}
	* {
		font-size: 12pt;
	}
</style>

<?php
//debug( $request_params );

if ( ifound($request_params, "?") )
{
	$queryData = explode("?", $request_params);
//    debug($queryData);
	$request_params = $queryData[0];
}

$queryString = '';
if ( count($_GET) )
{
//	debug($_GET);
	$queryString = '?' . http_build_query($_GET);
//    debug($queryString);
}

$RequestData = explode("/", $request_params);
if (count($RequestData) != 0)
{
	if (count($RequestData) == 2)
	{
		$request_params = $RequestData[0];
		$doctor_id = $RequestData[1];
	} else
	{
		if (count($RequestData) == 1)
		{
			if ($request_params == 'onlyRoute' || $request_params == 'ignoreRoute')
			{
			
			} else
			{
				$doctor_id = $RequestData[0];
			}
		}
	}
}

$request_params = $_GET['routeType'];
$doctor_id = $_GET['doctor'];

$active_all = 'active';
$active_route = '';
$active_noroute = '';

//debug($doctor_id);

if ($doctor_id > 0)
{
	$DOCTOR_ID_CONDITION = "WHERE {$CAOP_CANCER}.cancer_doctor_id='{$doctor_id}'";
}
?>



<?php

$SortArray = array(
	'cancer_added_date' =>  array(
		'title' =>  'По добавлению',
		'field' =>  'cancer_unix'
	),
	'cancer_route_date' =>  array(
		'title' =>  'По обязательности МЛ',
		'field' =>  'journal_unix'
	),
	'rs_stage_po_pe_pr_zno_date' =>  array(
		'title' =>  'По перв. признакам',
		'field' =>  'rs_stage_po_pe_pr_zno_date'
	),
	'rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date' =>  array(
		'title' =>  'По перв. обращению',
		'field' =>  'rs_stage_fap_date;rs_stage_vr_u4_pol_date'
	),
	'rs_stage_caop_date' =>  array(
		'title' =>  'По обращению в ЦАОП',
		'field' =>  'rs_stage_caop_date'
	),
	'rs_ds_set_date' =>  array(
		'title' =>  'По установлению',
		'field' =>  'rs_ds_set_date'
	),
	'rs_stage_cure_date' =>  array(
		'title' =>  'По началу спец. лечения',
		'field' =>  'rs_stage_cure_date'
	)
);

$queryCancer = "SELECT * FROM {$CAOP_CANCER}
                    LEFT JOIN {$CAOP_PATIENTS}
                        ON {$CAOP_CANCER}.cancer_patid={$CAOP_PATIENTS}.patid_id
                    LEFT JOIN {$CAOP_ROUTE_SHEET}
                        ON {$CAOP_ROUTE_SHEET}.rs_patid={$CAOP_PATIENTS}.patid_id
                    LEFT JOIN   (
                                SELECT * FROM {$CAOP_JOURNAL} WHERE (journal_ds LIKE 'C%' OR journal_ds LIKE 'С%') OR (journal_ds LIKE 'D0%')
                                GROUP BY journal_patid
                                HAVING journal_unix = MIN(journal_unix)
                                ) last_c_visit
                        ON {$CAOP_CANCER}.cancer_patid=last_c_visit.journal_patid
                        " . $DOCTOR_ID_CONDITION . "
                        ORDER BY {$CAOP_PATIENTS}.patid_name ASC";

//$queryCancer = "SELECT * FROM {$CAOP_CANCER} cancer
//                    LEFT JOIN {$CAOP_PATIENTS} patients
//                        ON cancer.cancer_patid=patients.patid_id
//                    LEFT JOIN {$CAOP_ROUTE_SHEET} sheet
//                        ON sheet.rs_patid=patients.patid_id
//                        " . $DOCTOR_ID_CONDITION . "
//                        ORDER BY patients.patid_name ASC";
$resultCancer = mqc($queryCancer);
$CancerData = mr2a($resultCancer);

$ROUTE = 0;
$NOROUTE = 0;

$ROUTE_DATA = array();
$NOROUTE_DATA = array();

//for ($pat=0; $pat<count($CancerData)-1; $pat++)
//{
//	$VisitsData = getarr(CAOP_JOURNAL, "journal_patid='{$CancerData[$pat]['patid_id']}' AND (journal_ds LIKE 'C%' OR journal_ds LIKE 'С%')", "ORDER BY journal_id DESC LIMIT 0,1");
//	if ( count($VisitsData) == 1 )
//	{
//		$VisitData = $VisitsData[0];
//		$CancerData[$pat] = array_merge($CancerData[$pat], $VisitData);
//	}
//}

$CancerDataSorter = array();
if ( isset($_GET['sortType']) && $_GET['sortType']!='' )
{
	if ( !notnull($_GET['sortFrom']) && !notnull($_GET['sortTo']) )
	{
	} else
	{
		for ($pat=0; $pat<count($CancerData); $pat++)
		{
			$SortType = $SortArray[$_GET['sortType']];
//			debug('sorttype: ' . $SortType['field']);
			if ( notnull($SortType) )
			{
				$from_date_unix = strtotime($_GET['sortFrom']);
				$from_date = date("d.m.Y", $from_date_unix);
				
				$to_date_unix = strtotime($_GET['sortTo']);
				$to_date = date("d.m.Y", $to_date_unix);
				
				if ( $SortType['field']=="cancer_unix" || $SortType['field']=="journal_unix" )
				{
					$from_MAIN = $from_date_unix;
					$to_MAIN = $to_date_unix;
				}
//		        debug($from_MAIN);
//		        debug($to_MAIN);
//		        debug($CancerData[$pat]);
				
				if ( strlen($from_MAIN) > 0 )
				{
					if ( $CancerData[$pat][ $SortType['field'] ] >= $from_MAIN )
					{
						if ( strlen($to_MAIN) > 0 )
						{
							if ( $CancerData[$pat][ $SortType['field'] ] <= $to_MAIN )
							{
								$CancerDataSorter[] = $CancerData[$pat];
							}
						} else
						{
							$CancerDataSorter[] = $CancerData[$pat];
						}
					}
				}
				if ( strlen($to_MAIN) > 0 )
				{
					if ( $CancerData[$pat][ $SortType['field'] ] <= $to_MAIN )
					{
						$CancerDataSorter[] = $CancerData[$pat];
					}
				}
				
			}
//			break;
		}
//        exit();
	}
} else
{
	$CancerDataSorter = $CancerData;
}
//debug($CancerDataSorter);
//exit();
$CancerData = $CancerDataSorter;
$TOTAL = count($CancerData);
unset($CancerDataSorter);

foreach ($CancerData as $cancerDatum)
{
	if ($cancerDatum['rs_id'] > 0)
	{
		$ROUTE++;
		$ROUTE_DATA[] = $cancerDatum;
	} else
	{
		$NOROUTE++;
		$NOROUTE_DATA[] = $cancerDatum;
	}
}
$MAIN_ARRAY = $CancerData;
unset($CancerData);
$doctor_link = '';
switch ($request_params)
{
	case "onlyRoute":
		$active_all = '';
		$active_route = 'active';
		$active_noroute = '';
		$MAIN_ARRAY = $ROUTE_DATA;
		unset($ROUTE_DATA);
		$doctor_link = '/' . $request_params;
		break;
	
	case "ignoreRoute":
		$active_all = '';
		$active_route = '';
		$active_noroute = 'active';
		$MAIN_ARRAY = $NOROUTE_DATA;
		unset($NOROUTE_DATA);
		$doctor_link = '/' . $request_params;
		break;
	default:
		$active_all = 'active';
		$active_route = '';
		$active_noroute = '';
		unset($ROUTE_DATA);
		unset($NOROUTE_DATA);
		//$doctor_link = '/' . $request_params;
		break;
}


?>

<?php
$did = '';
if ($doctor_id > 0)
{
	$did = '/' . $doctor_id;
}
?>

<div style="width: 100%; text-align: center;">
	<span style="font-size: 16pt; font-weight: bolder">ПАЦИЕНТЫ БЕЗ МАРШРУТНОГО ЛИСТА</span><br>
	По приказу от "01" июня 2021 г.
</div><br>

<table border="1" class="table tbc">
	<thead>
	<tr>
		<th scope="col" class="text-center" width="1%" data-title="npp">№</th>
		<!--        <th scope="col" class="text-center" width="1%" data-title="patid_id">ID</th>-->
		<!--        <th scope="col" class="text-center" width="1%" data-title="patid_ident">Карта</th>-->
		<th scope="col" class="text-center" data-title="patid_name">Фамилия И.О.</th>
		<th scope="col" class="text-center" data-title="patid_birth">Дата рож.</th>
		<th scope="col" class="text-center" width="1%" data-title="cancer_route_date">Дата МЛ</th>
		<th scope="col" class="text-center" width="1%" data-title="cancer_ds">МКБ</th>
		<th scope="col" class="text-center" data-title="cancer_ds_text">Диагноз</th>
		<th scope="col" class="text-center" width="1%" data-title="rs_stage_po_pe_pr_zno_date">Перв. пр-ки
		</th>
		<th scope="col" class="text-center" width="1%" data-title="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date">
			Перв. обр-ие
		</th>
		<th scope="col" class="text-center" width="1%"
		    data-title="rs_stage_caop_date">Обр-ие в ЦАОП</th>
		<th scope="col" class="text-center" width="1%" data-title="rs_ds_set_date" width="1%">Уст-ен</th>
		<th scope="col" class="text-center" width="1%"
		    data-title="rs_stage_cure_date">Нач. спецлеч-я</th>
		<th scope="col" class="text-center" width="1%" data-title="cancer_doctor_id" width="1%">Врач</th>
	</tr>
	</thead>
	<tbody>
	
	
	<?php
	//$PatientData = array();
	//foreach ($CancerData as $patientRouteSheets)
	//{
	//    $patientDatum = $PatientData['id' . $patientRouteSheets['patid_id']];
	//    if ( notnull($patientDatum) )
	//    {
	//
	//    } else
	//    {
	//
	//    }
	//}
	
	$unixML = birthToUnix('01.06.2021');
	
	$npp = count($MAIN_ARRAY);
	$rs_warning = '<span ' . super_bootstrap_tooltip('Маршрутный лист не найден') . '>' . BT_ICON_CASE_WARNING . '</span>';
	foreach ($MAIN_ARRAY as $cancerDatum)
	{
		
		extract($cancerDatum);
		
		$RouteSheetsData = array_search($patid_id, array_column($MAIN_ARRAY, 'patid_id'));
		
		$empty_rs_stage_caop_date = false;
		
		$firstAID = '';
		
		if (count($RouteSheetsData) == 1)
		{
			if (notnull($rs_id))
			{
				$route_sheet_link = '<a class="dropdown-item" href="javascript:openRouteSheet(' . $patid_id . ')">Маршрутные листы (' . count($RouteSheetsData) . ')</a>';
				$cancer_ds_icon_rs = '<span ' . super_bootstrap_tooltip('Маршрутный лист заполнен') . '>' . BT_ICON_CASE_ALLGOOD . '</span>';
				
				$firstAID = $rs_stage_fap_date;
				if (strlen($rs_stage_fap_date) < 3) $firstAID = $rs_stage_vr_u4_pol_date;
				if (strlen($firstAID) < 3)
				{
					if (strlen($cancer_first_aid_date) > 0)
					{
						$firstAID = '<span ' . super_bootstrap_tooltip('Дата взята из базы МИАС') . '><u>' . $cancer_first_aid_date . '</u></span>';
					} else $firstAID = $rs_warning;
				}
			} else
			{
				if (strlen($cancer_first_aid_date) > 0)
				{
					$firstAID = '<span ' . super_bootstrap_tooltip('Дата взята из базы МИАС') . '><u>' . $cancer_first_aid_date . '</u></span>';
				} else $firstAID = $rs_warning;
				$rs_ds_set_date = $rs_warning;
				$rs_stage_caop_date = $rs_warning;
				$rs_stage_po_pe_pr_zno_date = $rs_warning;
				$empty_rs_stage_caop_date = true;
				$rs_stage_cure_date = $rs_warning;
				$route_sheet_link = '<span class="font-weight-bolder dropdown-item">Маршрутных листов нет</span>';
				$cancer_ds_icon_rs = '<span ' . super_bootstrap_tooltip('Маршрутный лист отсутствует') . '>' . BT_ICON_CASE_WARNING . '</span>';
			}
		} else
		{
			// БОЛЬШЕ ОДНОГО МАРШРУТНОГО ЛИСТА
		}
		
		$rs_ds_set_date = (strlen($rs_ds_set_date) > 1) ? wrapper($rs_ds_set_date) : nbsper('не указано');
		$rs_stage_caop_date = (strlen($rs_stage_caop_date) > 1) ? wrapper($rs_stage_caop_date) : nbsper('не указано');
		$rs_stage_cure_date = (strlen($rs_stage_cure_date) > 1) ? wrapper($rs_stage_cure_date) : nbsper('не указано');
		$rs_stage_po_pe_pr_zno_date = (strlen($rs_stage_po_pe_pr_zno_date) > 1) ? wrapper($rs_stage_po_pe_pr_zno_date) : nbsper('не указано');
		
		if (strlen($rs_stage_cure_comm) > 1) $rs_stage_cure_date .= '<br>' . $rs_stage_cure_comm;
		
		$morphology_data = '';
		if (strlen($cancer_morph_type) > 2) $morphology_data = '<br><div style="font-size: 10pt; font-weight: bolder">' . $cancer_morph_type . ' №' . $cancer_morph_number . ' от ' . $cancer_morph_date . ' - ' . $cancer_morph_text . '</div>';
		
		$cancer_date = ($cancer_unix != 0) ? date("d.m.Y H:i:s", $cancer_unix) : nbsper('не указано');
		
		$DoctorData = $DoctorsListId['id' . $cancer_doctor_id];
		$DoctorDataJournal = $DoctorsListId['id' . $journal_doctor];
		
		$DoctorName = nbsper('не указано');
		
		if (notnull($DoctorDataJournal)) $DoctorName = '<span ' . super_bootstrap_tooltip('Врач взят из визита по журналу') . '>' . nbsper(docNameShort($DoctorsListId['id' . $journal_doctor])) . '</span>';
		
		if (notnull($DoctorData)) $DoctorName = nbsper(docNameShort($DoctorData));
		
		if ($empty_rs_stage_caop_date)
		{
			if ( strlen($journal_unix) > 1)
			{
				$rs_stage_caop_date = '<span ' . super_bootstrap_tooltip('Дата взята из визита по журналу') . '>' . date("d.m.Y", $journal_unix) . '</span>';
			}
			
		}
		
		$visitUnix = $journal_unix;
		$ml_warning = '';
		if ($visitUnix >= $unixML)
		{
			$ml_warning = 'bg-danger';
		}
		
		if (notnull($rs_id)) $ml_warning = '';
		
		?>
		<tr class="<?= $ml_warning; ?>">
			<td data-cell="npp" class="text-center font-weight-bold"><?= $npp; ?>)</td>
			<!--            <td data-cell="patid_id" class="text-center">--><?//= $patid_id;
			?><!--</td>-->
			<!--            <td data-cell="patid_ident" class="text-center">--><?//= $patid_ident;
			?><!--</td>-->
			<td data-cell="patid_name"
			    class="patient-name"><?=mb_ucwords($patid_name);?>
				<br>
				[<?=$patid_ident;?>]
			</td>
			<td data-cell="patid_birth" class="text-center"><?= $patid_birth; ?></td>
			<td data-cell="cancer_route_date" class="text-center"><?= date("d.m.Y", $journal_unix); ?></td>
			<td data-cell="cancer_ds" class="text-center font-weight-bolder"><?= $cancer_ds; ?></td>
			<td data-cell="cancer_ds_text" class="">
				<?= $cancer_ds_text; ?>
				<?= $morphology_data; ?>
				<!--                --><?//= debug($cancerDatum);; ?>
				<?php
				//				debug($queryVisit);
				//				debug($amountVisit);
				//				debug($VisitData);
				//				if (notnull($rs_id))
				//				{
				//					spoiler_begin('Маршрутный лист #' . $rs_id, 'patient_route_' . $patid_id);
				//					{
				//						debug($cancerDatum);
				//					}
				//					spoiler_end();
				//				}
				//
				?>
			</td>
			<td data-cell="rs_stage_po_pe_pr_zno_date" class="text-center"><?= $rs_stage_po_pe_pr_zno_date; ?></td>
			<td data-cell="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date" class="text-center"><?= $firstAID; ?></td>
			<td data-cell="rs_stage_caop_date" class="text-center"><?= $rs_stage_caop_date; ?></td>
			<td data-cell="rs_ds_set_date" class="text-center"><?= $rs_ds_set_date; ?></td>
			<td data-cell="rs_stage_cure_date" class="text-center"><?= $rs_stage_cure_date ?></td>
			<td data-cell="cancer_doctor_id" class="text-center"><?= $DoctorName; ?></td>
		
		</tr>
		<?php
//	}
//	spoiler_end();
		$npp--;
	}
	?>
	</tbody>
</table>


<script defer type="text/javascript" src="/engine/js/cancerList.js?<?= rand(0, 999999); ?>"></script>
<script defer language="JavaScript" type="text/javascript"
        src="/engine/js/allpatients.js?<?= rand(0, 1000000); ?>"></script>

<style>
	.active a {
		color: #212529 !important;
	}
</style>