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

?>
<td data-cell="npp" class="text-center font-weight-bold" valign="top"><?= $npp; ?>)</td>
<!--            <td data-cell="patid_id" class="text-center">--><?//= $patid_id;
?><!--</td>-->
<!--            <td data-cell="patid_ident" class="text-center">--><?//= $patid_ident;
?><!--</td>-->

<td data-cell="patid_name" valign="top" >
	<?=mb_ucwords($patid_name);?><br>
	[<?= $patid_ident; ?>]
</td>
<td valign="top" data-cell="patid_birth" class="text-center"><?= $patid_birth; ?></td>
<td data-cell="cancer_route_date" class="text-center" <?=super_bootstrap_tooltip('['.$journal_unix.']');?>><?= date("d.m.Y", $journal_unix); ?><br></td>
<!--<td valign="top" data-cell="cancer_ds" class="text-center font-weight-bolder">--><?//= $cancer_ds; ?><!--</td>-->
<td valign="top" data-cell="cancer_ds_text" class="">
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
</td>
<td valign="top" data-cell="rs_stage_po_pe_pr_zno_date" class="text-center"><?= $rs_stage_po_pe_pr_zno_date; ?></td>
<td valign="top" data-cell="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date" class="text-center"><?= $firstAID; ?></td>
<td valign="top" data-cell="rs_stage_caop_date" class="text-center"><?= $rs_stage_caop_date; ?></td>
<td valign="top" data-cell="rs_ds_set_date" class="text-center"><?= $rs_ds_set_date; ?></td>
<!--<td valign="top" data-cell="rs_stage_cure_date" class="text-center">--><?//= $rs_stage_cure_date ?><!--</td>-->
<td valign="top" data-cell="cancer_doctor_id" class="text-center"><?= $DoctorName; ?></td>