<?php
/*$queryVisit = "SELECT * FROM {$CAOP_JOURNAL} journal
			LEFT JOIN {$CAOP_DAYS} days
				ON journal.journal_day=days.day_id
			WHERE journal.journal_patid='{$patid_id}' AND (journal.journal_ds LIKE 'C%' OR journal.journal_ds LIKE 'С%')
			ORDER BY journal_id ASC LIMIT 0,1";

$resultVisit = mqc($queryVisit);
$amountVisit = mnr($resultVisit);
$VisitData = [];
if ($amountVisit == 1)
{
	$VisitData = mr2a($resultVisit);
	$VisitData = $VisitData[0];
	extract($VisitData);
}*/

//$VisitsData = getarr(CAOP_JOURNAL, "journal_patid='{$patid_id}' AND (journal_ds LIKE 'C%' OR journal_ds LIKE 'С%')", "ORDER BY journal_id DESC LIMIT 0,1");

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
if (strlen($cancer_morph_type) > 2) $morphology_data = '<br><div class="text-muted font-weight-bolder small">' . $cancer_morph_type . ' №' . $cancer_morph_number . ' от ' . $cancer_morph_date . ' - ' . $cancer_morph_text . '</div>';

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
	if ( $useHighlight )
    {
	    $ml_warning = 'bg-danger';
    }
}

if (notnull($rs_id)) $ml_warning = '';

$journal_visit_unix = nbsper('не указано<br>'.$journal_unix);
if ( !notnull($journal_unix) )
{
	$journal_visit_unix = date("d.m.Y", $journal_unix);
}

$junix_success = '';
if ( $journal_unix < birthToUnix('1.1.2021') )
{
	$junix_success = 'bg-success';
}

$ds_takeoff = '';
if ( strlen($cancer_takeoff_date) > 0 )
{
    $junix_success = '';
    $ml_warning = 'bg-primary bg-light';
    $ds_takeoff = '
    <span class="text-muted small">
        <b>Дата снятия с Д-учета:</b> '.$cancer_takeoff_date.'</span><br>
        <b>Причина снятия с Д-учета:</b> '.$cancer_takeoff_reason.'</span>
    ';
}

?>
<tr class="<?= $ml_warning; ?> <?=$junix_success;?>">
	<td data-cell="npp" class="text-center font-weight-bold"><?= $npp; ?>)</td>
	<!--            <td data-cell="patid_id" class="text-center">--><?//= $patid_id;
	?><!--</td>-->
	<!--            <td data-cell="patid_ident" class="text-center">--><?//= $patid_ident;
	?><!--</td>-->
	
	<td data-cell="patid_name"
	    class="patient-name"><?= editPersonalDataLink(shorty($patid_name), $patid_id, super_bootstrap_tooltip(mb_ucwords($patid_name))); ?>
		<br>
		[<?=$patid_ident;?>]<br>
		[ID<?=$patid_id;?>]
	</td>
	<!--            <td data-cell="patid_name"-->
	<!--                class="patient-name">Пациент ID--><?//=$patid_id;?>
	<!--            </td>-->
	<td data-cell="patid_birth" class="text-center"><?= $patid_birth; ?></td>
	<td data-cell="cancer_added_date" class="text-center"><?= $cancer_date; ?></td>
	<td data-cell="cancer_route_date" class="text-center" <?=super_bootstrap_tooltip('['.$journal_unix.']');?>><?= date("d.m.Y", $journal_unix); ?><br></td>
	<td data-cell="cancer_ds" class="text-center font-weight-bolder"><?= $cancer_ds; ?></td>
	<td data-cell="cancer_ds_text" class="">
		<?= $cancer_ds_icon_rs . ' ' . $cancer_ds_text; ?>
		<?= $morphology_data; ?>
		<!--                --><?//= debug(birthToUnix($journal_visit_unix));; ?>
		<!--                --><?//= debug(birthToUnix('1.1.2021'));; ?>
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
        <?=$ds_takeoff;?>
	</td>
	<td data-cell="rs_stage_po_pe_pr_zno_date" class="text-center"><?= $rs_stage_po_pe_pr_zno_date; ?></td>
	<td data-cell="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date" class="text-center"><?= $firstAID; ?></td>
	<td data-cell="rs_stage_caop_date" class="text-center"><?= $rs_stage_caop_date; ?></td>
	<td data-cell="rs_ds_set_date" class="text-center"><?= $rs_ds_set_date; ?></td>
	<td data-cell="rs_stage_cure_date" class="text-center"><?= $rs_stage_cure_date ?></td>
	<td data-cell="cancer_doctor_id" class="text-center"><?= $DoctorName; ?></td>
	
	<td data-cell="actions" class="text-center">
		<div class="dropdown dropleft" data-title="Действия">
			<button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="buttonPatient<?=$patid_id;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tools" viewBox="0 0 16 16">
					<path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.356 3.356a1 1 0 0 0 1.414 0l1.586-1.586a1 1 0 0 0 0-1.414l-3.356-3.356a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0zm9.646 10.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708zM3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026L3 11z"/>
				</svg>
			</button>
			<div class="dropdown-menu" aria-labelledby="buttonPatient<?=$patid_id;?>">
				<a class="dropdown-item" href="javascript:allVisits('<?= $patid_id; ?>', '1')">Открыть посещения</a>
				<a class="dropdown-item" href="javascript:openRouteSheet('<?= $patid_id; ?>')">Открыть маршрутные листы</a>
			</div>
		</div>
	</td>
</tr>