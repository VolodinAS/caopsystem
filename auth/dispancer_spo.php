<?php

$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");
$DispLPUId = getDoctorsById($DispLPU, 'lpu_id');

$fields = "
spo_id,
spo_patient_id,
spo_start_doctor_id,
spo_start_date_unix,
spo_unix_accounting_set,
spo_mkb_directed,
spo_mkb_finished,
spo_end_reason_type,
spo_lpu_id,
patid_id,
patid_name,
patid_birth,
COUNT({$PK[CAOP_SPO]}) as spo_count
";

$Dispancer_query = "
SELECT {$fields} FROM ".CAOP_PATIENTS." pat
LEFT JOIN ".CAOP_SPO." spo ON spo.spo_patient_id=pat.{$PK[CAOP_PATIENTS]}
WHERE spo.spo_is_dispancer>0 AND pat.patid_isDead='0'
GROUP BY spo.spo_patient_id
ORDER BY spo_start_date_unix DESC
";

if ( $USER_PROFILE['doctor_id'] == 1 )
{
	$AllDispancer = $AdminParams['data']['dispancer_spo_all']['param_value'];
	
	if ( !$AllDispancer ) $Dispancer_query .= " LIMIT 0, 1";
}

$Dispancer_result = mqc($Dispancer_query);
$Dispancer = mr2a($Dispancer_result);
$Dispancer_count = count($Dispancer);

if ( $Dispancer_count > 0 )
{
	bt_notice('На диспансерном учете состоит: '. wrapper( $Dispancer_count.' ' . wordEnd($Dispancer_count, 'пациент', 'пациента', 'пациентов') ), BT_THEME_SUCCESS);
	bt_divider();
//	debug($Dispancer[0]);
	?>
    <table class="tbc" border="1" style="border: solid 1px #e9ecef" cellpadding="2">
        <thead>
        <tr>
            <th scope="col" class="text-center" data-title="npp" width="1%">№</th>
            <th scope="col" class="text-center" data-title="patient">Пациент</th>
            <th scope="col" class="text-center" data-title="spo_open" width="1%"><?=nbsper('Случай начат');?></th>
            <th scope="col" class="text-center" data-title="spo_doctor" width="1%">Врач</th>
            <th scope="col" class="text-center" data-title="spo_mkb_begin" width="1%">Направи-тельный диагноз</th>
            <th scope="col" class="text-center" data-title="spo_acco_begin" width="1%">Дата начала Д-учета</th>
            <th scope="col" class="text-center" data-title="spo_mkb_acco" width="1%">Диагноз Д-учета</th>
            <th scope="col" class="text-center" data-title="spo_count" width="1%">Кол-во СПО</th>
<!--            <th scope="col" class="text-center" data-title="last_visit" width="1%">Последний визит</th>-->
<!--            <th scope="col" class="text-center" data-title="spo_end" width="1%">Чем закончилось</th>-->
            <th scope="col" class="text-center" data-title="spo_button" width="1%">СПО</th>
        </tr>
        </thead>
        <tbody>
		<?php
		$npp = 1;
		
		foreach ($Dispancer as $spoItem)
		{
//		    debug($spoItem);
//		    break;
			/*$JournalLast_query = "
	        SELECT journal_id, journal_ds, journal_spo_end_reason_type, day_date, day_doctor FROM ".CAOP_JOURNAL." journal
	        LEFT JOIN ".CAOP_DAYS." days ON days.".$PK[CAOP_DAYS]."=journal.journal_day
	        WHERE journal.journal_spo_id='{$spoItem[$PK[CAOP_SPO]]}'
	        ORDER BY days.day_unix DESC
	        LIMIT 0, 1
	        ";
			
			$JournalLast_result = mqc($JournalLast_query);
			$JournalLast = mr2a($JournalLast_result);*/
			
			$last_visit = text_muted('нет');
			$spo_end = text_muted('нет');
			$doctor_last_visit = '';
			/*if ( count($JournalLast) == 1 )
			{
				$JournalLast = $JournalLast[0];
				if ( $JournalLast['journal_spo_end_reason_type'] > 0 )
				{
					$spo_end = $CaopSPOReasonsId['id' . $JournalLast['journal_spo_end_reason_type'] ] ['reason_type_title'];
				} else $spo_end = text_muted('не указано');
				
				$last_visit = $JournalLast['day_date'];
				
				$DoctorDataVisit = $DoctorsListId['id' . $JournalLast['day_doctor']];
				$doctor_last_visit = nbsper(docNameShort($DoctorDataVisit));
				
				$doctor_last_visit = '<div></div>' . text_muted($doctor_last_visit);
			}*/
			
			$SPOData = extractValueByKey($spoItem, 'spo_');
			$PatientData = extractValueByKey($spoItem, 'patid_');
			
			$age = ( $PatientData['patid_birth'] ) ? ageByBirth($PatientData['patid_birth']) : false;
			$age_format = ( $age !== false ) ? ' (' . $age . ' ' . wordEnd($age, 'год', 'года', 'лет') . ')' : '';
			$age_format = nbsper($age_format);
			
			$spo_open = ( $SPOData['spo_start_date_unix'] != 0 ) ?
				date(DMY, $SPOData['spo_start_date_unix']) :
				text_muted(nbsper('не указано'));
			
			$mkb_begin = ( strlen($SPOData['spo_mkb_directed']) ) ?
				$SPOData['spo_mkb_directed'] :
				text_muted(nbsper('не указано'));
			
			$doctor_name = '';
			if ( $SPOData['spo_start_doctor_id'] > 0 )
			{
				$DoctorData = $DoctorsListId['id' . $SPOData['spo_start_doctor_id']];
				$doctor_name = nbsper(docNameShort($DoctorData));
			} else $doctor_name = text_muted(nbsper('не указан'));
			
			$acco_begin = ( $SPOData['spo_unix_accounting_set'] != 0 ) ? nbsper(date(DMY, $SPOData['spo_unix_accounting_set'])) : text_muted(nbsper('не указана'));
			
			if ( strlen($SPOData['spo_mkb_finished']) > 0 )
			{
				$mkb_acco = $SPOData['spo_mkb_finished'];
				$is_cancer = DiagnosisCancer($SPOData['spo_mkb_finished']);
				$is_dispancer = CheckMKBDispancer($SPOData['spo_mkb_finished'], $MKBDispLinear);
				if ( $is_cancer ) $mkb_acco = badge($mkb_acco, BT_THEME_DANGER, false, 1);
                elseif ( $is_dispancer ) $mkb_acco = badge($mkb_acco, BT_THEME_INFO, false, 1);
			} else $mkb_acco = text_muted(nbsper('не указан'));
			
			$lpu = '';
			if ( $SPOData['spo_lpu_id'] > 0 )
			{
				$lpu = $DispLPUId['id' . $SPOData['spo_lpu_id']]['lpu_showname'];
			} else $lpu = 'не указано';
			$lpu = text_muted('<br>Прикрепление: ' . $lpu);
			
			?>
            <tr>
                <td data-cell="npp" class="text-center" valign="top"><?=$npp;?>.</td>
                <td data-cell="patient">
					<?=editPersonalDataLink( mb_ucwords($PatientData['patid_name']), $PatientData[$PK[CAOP_PATIENTS]] );?>, <?=$PatientData['patid_birth'];?> г.р., <?=$age_format;?>
					<?=$lpu;?></td>
                <td data-cell="spo_open" class="text-center"><?=$spo_open;?></td>
                <td data-cell="spo_doctor" class="text-center"><?=$doctor_name;?></td>
                <td data-cell="spo_mkb_begin" class="text-center"><?=$mkb_begin;?></td>
                <td data-cell="spo_acco_begin" class="text-center"><?=$acco_begin;?></td>
                <td data-cell="spo_mkb_acco" class="text-center"><?=$mkb_acco;?></td>
                <td data-cell="spo_count" class="text-center"><?=$spoItem['spo_count'];?></td>
<!--                <td data-cell="last_visit" class="text-center">--><?//=$last_visit;?><!----><?//=$doctor_last_visit;?><!--</td>-->
<!--                <td data-cell="spo_end" class="text-center">--><?//=$spo_end;?><!--</td>-->
                <td data-cell="spo_button" class="text-center">
                    <button class="btn btn-primary btn-sm" onclick="javascript:showSPO(0, <?=$PatientData[$PK[CAOP_PATIENTS]];?>)">
						<?=BT_ICON_DISPANSER;?>
                    </button>
                </td>
            </tr>
			<?php
			$npp++;
		}
		?>
        </tbody>
    </table>
	<?php
	
	
	
} else bt_notice('В списке нет диспансерных пациентов', BT_THEME_WARNING);



?>
<script defer language="JavaScript" type="text/javascript" src="/engine/js/admin/admin_dispancer.js?<?=rand(0,1000000);?>"></script>
