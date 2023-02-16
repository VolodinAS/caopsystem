<style>
	/*@media print{*/
	/*	@page {size: landscape}*/
	/*}*/
</style>

<?php

// debug($_SESSION);

$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");
$DispLPUId = getDoctorsById($DispLPU, 'lpu_id');

$SessionParam = $PrintParams[0];

$date_from_unix = strtotime($_SESSION[$SessionParam]['date_from']);
$date_to_unix = strtotime($_SESSION[$SessionParam]['date_to']);
$doctor_id = $_SESSION[$SessionParam]['doctor_id'];
$showRepeats = $_SESSION[$SessionParam]['showRepeats'];
$date_from = date("d.m.Y", $date_from_unix);
$date_to = date("d.m.Y", $date_to_unix);


$date_from_unix_begin = $date_from_unix;
$date_to_unix_end = $date_to_unix + (86400-1);

// , COUNT({$CAOP_DS_VISITS}.visreg_id) as visits
//@ill_days_seconds := @ill_days_seconds + IF({$CAOP_DS_VISITS}.visreg_dispose_unix != '',
//                                                    ({$CAOP_DS_VISITS}.visreg_dispose_unix - {$CAOP_DS_VISITS}.visreg_visit_unix),
//                                                    86400) as ill_days_sec,

//$query_PatientDirlist = "";
//$result_PatientDirlist = mqc($query_PatientDirlist);
//$DSPatients = mr2a($result_PatientDirlist);

//debug($query_PatientDirlist);

//exit();
if ( $doctor_id > 0 )
{
    $query_JD = "SELECT * FROM {$CAOP_JOURNAL} INNER JOIN {$CAOP_PATIENTS} ON {$CAOP_JOURNAL}.journal_patid={$CAOP_PATIENTS}.patid_id WHERE {$CAOP_JOURNAL}.journal_unix>='{$date_from_unix_begin}' AND {$CAOP_JOURNAL}.journal_unix<='{$date_to_unix_end}' AND {$CAOP_JOURNAL}.journal_doctor='{$doctor_id}' GROUP BY {$CAOP_PATIENTS}.patid_id ORDER BY {$CAOP_JOURNAL}.journal_unix ASC";
	//$JornalDispancer = getarr(CAOP_JOURNAL, "journal_unix>='{$date_from_unix_begin}' AND journal_unix<='{$date_to_unix_end}' AND journal_doctor='{$doctor_id}'");
} else
{
    $query_JD = "SELECT * FROM {$CAOP_JOURNAL} INNER JOIN {$CAOP_PATIENTS} ON {$CAOP_JOURNAL}.journal_patid={$CAOP_PATIENTS}.patid_id WHERE {$CAOP_JOURNAL}.journal_unix>='{$date_from_unix_begin}' AND {$CAOP_JOURNAL}.journal_unix<='{$date_to_unix_end}' GROUP BY {$CAOP_PATIENTS}.patid_id ORDER BY {$CAOP_JOURNAL}.journal_unix ASC";
	//$JornalDispancer = getarr(CAOP_JOURNAL, "journal_unix>='{$date_from_unix_begin}' AND journal_unix<='{$date_to_unix_end}'");
}
// debug($query_JD);
$result_JD = mqc($query_JD);
$JornalDispancer = mr2a($result_JD);
//debug($JornalDispancer);
//exit();
$DSPatients = [];
if ( count($JornalDispancer) > 0 )
{
    foreach ($JornalDispancer as $journal_item)
    {
        if ((int)$journal_item['journal_disp_isDisp'] == 2)
        {
            $DSPatients[] = $journal_item;
        } else
        {
            if ((int)$journal_item['journal_disp_isDisp'] == 1)
            {
                if ( (int)$journal_item['journal_disp_isReported'] == 1 )
                {
                    $DSPatients[] = $journal_item;
                }
            }
        }
    }
} else
{
    bt_notice("Нет принятых пациентов за указанный период");
}
//$DSPatients = $JornalDispancer;
if ( count($DSPatients) > 0 )
{
    $DSPatients = array_orderby($DSPatients, 'patid_name', SORT_ASC);
	$DSPatientsClear = [];
//    debug($DSPatients);

    if ((int)$showRepeats == 1)
    {
        $DSPatientsClear = $DSPatients;
    } else
    {
        foreach ($DSPatients as $patientPrecheck)
    	{
            $Visits = getarr(CAOP_JOURNAL, "journal_patid='{$patientPrecheck['journal_patid']}' AND journal_unix<='{$date_to_unix_end}'", "ORDER BY journal_id ASC");
            if ( count($Visits) == 1 )
            {
    	        $DSPatientsClear[] = $patientPrecheck;
            }
        }
        
    }
	
    unset($DSPatients);
	$DSPatientsClear = array_orderby($DSPatientsClear, 'patid_name', SORT_ASC);
	$DSPatients = $DSPatientsClear;
	unset($DSPatientsClear);
    
	$npp = 1;
	?>
    <div class="header">
        <div class="gbuz boldy"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
        <div class="address boldy"><?=$LPU_DOCTOR['lpu_lpu_address'];?></div>
        <div class="listheader boldy">СПИСОК ПРИНЯТЫХ ДИСПАНСЕРНЫХ ПАЦИЕНТОВ</div>
    </div>
    <br>
	<div style="font-weight: bolder">
        Период: <?=$date_from;?> - <?=$date_to;?><br>
        Количество пациентов за период: <?=count($DSPatients);?>
    </div>
	<table class="tbc size-10pt" border="1" cellpadding="5">
		<thead>
		<tr>
			<th width="1%" scope="col" class="text-center" data-title="npp">№</th>
			<th scope="col" class="text-center" data-title="patient_fio">
				Ф.И.О.
			</th>
			<th width="1%" scope="col" class="text-center" data-title="patient_birth">Дата рождения</th>
			<th width="1%" scope="col" class="text-center" data-title="diag_mkb_dir">МКБ направления</th>
			<th width="1%" scope="col" class="text-center" data-title="diag_mkb_onco">МКБ онколога</th>
			<th width="1%" scope="col" class="text-center" data-title="disp_lpu">ЛПУ прикрепления</th>
			<th width="1%" scope="col" class="text-center" data-title="first_visit">Дата первого визита</th>
			<th width="1%" scope="col" class="text-center" data-title="doctor">Принявший врач</th>
			<th scope="col" class="text-center" data-title="diag_text">Диагноз</th>
		</tr>
		</thead>
		<tbody>
		
		
		<?php
		foreach ($DSPatients as $Patient)
		{
		    $doctor_name = docNameShort( $DoctorsListId['id' . $Patient['journal_doctor']], "famimot" );
//		    debug($Patient);
			
			$disp_lpu = 'Самообращение';
			if ( $Patient['journal_disp_lpu'] != 0 )
			{
				$disp_lpu = nbsper($DispLPUId [ 'id' . $Patient['journal_disp_lpu'] ]['lpu_shortname']);
			}
			?>
			<tr>
				<td align="center" data-cell="npp" class="font-weight-bolder text-center"><?=$npp;?>)</td>
				<td data-cell="patient_fio" class="patient-name">
					<?=mb_ucwords($Patient['patid_name']);?>
					<?php
					//					                debug($Patient);
					//					                debug($visits_data_debug);
					//					                debug($VisitsData);
					//					                debug( date("d.m.Y H:i:s", $date_from_unix_begin) . ' <= ' . date("d.m.Y H:i:s", $Patient['visreg_visit_unix']) . ' <= ' . date("d.m.Y H:i:s", $date_to_unix_end) );
					?>
				</td>
				<td align="center" data-cell="patient_birth" class="text-center"><?=$Patient['patid_birth'];?></td>
				<td align="center" data-cell="diag_mkb_dir" class="text-center font-weight-bolder"><?=$Patient['journal_disp_mkb'];?></td>
				<td align="center" data-cell="diag_mkb_onco" class="text-center font-weight-bolder"><?=$Patient['journal_ds'];?></td>
				<td align="center" data-cell="disp_lpu" class="text-center font-weight-bolder"><?=$disp_lpu;?></td>
				<td align="center" data-cell="first_visit" class="text-center font-weight-bolder"><?=date("d.m.Y", $Patient['journal_unix']);?></td>
				<td align="center" data-cell="doctor"><?=$doctor_name?></td>
				<td data-cell="diag_text"><?=$Patient['journal_ds_text'];?></td>
			
			</tr>
			<?php
			$npp++;
		}
		
		?>
		</tbody>
	</table>
	<?php
} else
{
	bt_notice('Пациентов в списке нет');
}
