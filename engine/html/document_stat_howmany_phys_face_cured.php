<style>
	@media print{
		@page {size: landscape}
	}
</style>

<?php
$SessionParam = $PrintParams[0];
if ( count($request_params) > 0 )
{
//	debug($request_params);
	$RequestData = explode("/", $request_params);
//	debug($RequestData);
	if ( count($RequestData) === 3 )
	{
		$date_from_unix = strtotime($RequestData[1]);
		$date_to_unix = strtotime($RequestData[2]);
	} else
    {
	    $date_from_unix = strtotime($_SESSION[$SessionParam]['date_from']);
	    $date_to_unix = strtotime($_SESSION[$SessionParam]['date_to']);
    }
	$date_from = date(DMY, $date_from_unix);
	$date_to = date(DMY, $date_to_unix);
} else
{
	$date_from_unix = strtotime($_SESSION[$SessionParam]['date_from']);
	$date_to_unix = strtotime($_SESSION[$SessionParam]['date_to']);
	$date_from = date(DMY, $date_from_unix);
	$date_to = date(DMY, $date_to_unix);
}





$date_from_unix_begin = $date_from_unix;
$date_to_unix_end = $date_to_unix + (86400-1);

$date_from_sql = date(DMYHIS, $date_from_unix_begin);
$date_to_sql = date(DMYHIS, $date_to_unix_end);

//debug($date_from_sql);
//debug($date_to_sql);

// , COUNT({$CAOP_DS_VISITS}.visreg_id) as visits
//@ill_days_seconds := @ill_days_seconds + IF({$CAOP_DS_VISITS}.visreg_dispose_unix != '',
//                                                    ({$CAOP_DS_VISITS}.visreg_dispose_unix - {$CAOP_DS_VISITS}.visreg_visit_unix),
//                                                    86400) as ill_days_sec,
// {$CAOP_DS_DIRLIST}.dirlist_isMain='1'
$query_PatientDirlist = "
                        SELECT
                            *,
                            COUNT({$CAOP_DS_VISITS}.visreg_id) as visits,
                            SUM({$CAOP_DS_VISITS}.visreg_dose) as sum_dose
                        FROM {$CAOP_DS_PATIENTS}
                            LEFT JOIN {$CAOP_DS_DIRLIST}
                                ON {$CAOP_DS_PATIENTS}.patient_id={$CAOP_DS_DIRLIST}.dirlist_dspatid
                            LEFT JOIN {$CAOP_DS_VISITS}
                            	ON {$CAOP_DS_PATIENTS}.patient_id={$CAOP_DS_VISITS}.visreg_dspatid
                        WHERE
                            1
                            AND {$CAOP_DS_VISITS}.visreg_visit_unix >= '{$date_from_unix_begin}'
                            AND {$CAOP_DS_VISITS}.visreg_visit_unix <= '{$date_to_unix_end}'
                        GROUP BY {$CAOP_DS_PATIENTS}.patient_id
                        ORDER BY {$CAOP_DS_PATIENTS}.patient_fio ASC";

//$query_PatientDirlist = "
//SELECT * FROM ".CAOP_DS_VISITS."
//LEFT JOIN ".CAOP_DS_PATIENTS." ON ".CAOP_DS_PATIENTS.".patient_id = ".CAOP_DS_VISITS.".visreg_dspatid
//WHERE visreg_visit_unix >= '{$date_from_unix_begin}' AND visreg_visit_unix <= '{$date_to_unix_end}'
//GROUP BY ".CAOP_DS_PATIENTS.".patient_id
//";



$result_PatientDirlist = mqc($query_PatientDirlist);
$DSPatients = mr2a($result_PatientDirlist);

//debug($query_PatientDirlist);

//debug($DSPatients);
//exit();

if ( count($DSPatients) > 0 )
{
	$npp = 1;
	$ILL_DAYS_SECONDS = 0;
	
	
	?>
    <hr>
    <table width="100%" border="0">
        <tbody>
        <tr>
            <td align="center"><h1>Сколько было физических лиц пролечено</h1></td>
        </tr>
        </tbody>
    </table>
    <hr>
	<h2>Период: <?=$date_from;?> - <?=$date_to;?></h2>
	<h3>Количество пациентов за период: <?=count($DSPatients);?></h3>
	<table class="tbc size-10pt" border="1" cellpadding="5">
		<thead>
		<tr>
			<th width="1%" scope="col" class="text-center" data-title="npp">№</th>
			<th scope="col" class="text-center" data-title="patient_fio">
				Ф.И.О.
			</th>
			<th width="1%" scope="col" class="text-center" data-title="patient_birth">Дата рождения</th>
			<th width="1%" scope="col" class="text-center" data-title="dirlist_diag_mkb">МКБ</th>
			<th scope="col" class="text-center" data-title="dirlist_diag_text">Диагноз</th>
			<th width="1%" scope="col" class="text-center" data-title="dirlist_visit_date">Дата первой явки</th>
			<th width="1%" scope="col" class="text-center" data-title="visits">Визитов</th>
			<th width="1%" scope="col" class="text-center" data-title="cure_mg">Кол-во введенного препарата, мг</th>
			<th width="1%" scope="col" class="text-center" data-title="ill_days">Всего койкодней</th>
		</tr>
		</thead>
		<tbody>
		
		
		<?php
		$VISITS_SUMMARY = 0;
		$DOSE_MG_SUMMARY = 0;
		$KOIKODAYS_SUMMARY = 0;
		foreach ($DSPatients as $Patient)
		{
			$VisitsData = getarr(CAOP_DS_VISITS, "visreg_dspatid='{$Patient['patient_id']}' AND visreg_dirlist_id='{$Patient['dirlist_id']}'");
			$VISITS_SUMMARY += count($VisitsData);
			$visits_data_debug = '';
			if ( count($VisitsData) > 0 )
			{
			    $CURE_MG = 0;
				$ILL_DAYS_SECONDS = 0;
			 
				foreach ($VisitsData as $visitsDatum)
				{
					$visits_data_debug .= debug_ret($visitsDatum);
					$CURE_MG += $visitsDatum['visreg_dose'];
                    if ( strlen($visitsDatum['visreg_dispose_date']) > 0 )
                    {
                        $ILL_DAYS_SECONDS += intval($visitsDatum['visreg_dispose_unix']) - intval($visitsDatum['visreg_visit_unix']) + TIME_DAY;
                    } else
                    {
	                    $ILL_DAYS_SECONDS += TIME_DAY;
                    }
			    }
			}
			$DOSE_MG_SUMMARY += $CURE_MG;
//		debug($Patient);
//		spoiler_begin( $n . ') [#' . $Patient['patient_id'] . ']. ' . mb_ucwords($Patient['patient_fio']).', ' . $Patient['patient_birth'] . ' г.р.', 'patient_'.$Patient['patient_id'] );
//		?>
			<!--		<a href="/dayStac/newPatient/--><?//=$Patient['patient_id'];?><!--">Редактировать информацию</a>-->
			<!--		--><?php
//		spoiler_end();
			$dirlist_done_date = $Patient['dirlist_done_date'];
			if ( strlen($dirlist_done_date) == 0 )
			{
				$dirlist_done_date = 'лечится';
			}
			?>
			<tr>
				<td align="center" data-cell="npp" class="font-weight-bolder text-center"><?=$npp;?>)</td>
				<td data-cell="patient_fio" class="patient-name">
					<?=mb_ucwords($Patient['patient_fio']);?>
					<?php
//					                debug($Patient);
//					                debug($visits_data_debug);
//					                debug($VisitsData);
//					                debug( date("d.m.Y H:i:s", $date_from_unix_begin) . ' <= ' . date("d.m.Y H:i:s", $Patient['visreg_visit_unix']) . ' <= ' . date("d.m.Y H:i:s", $date_to_unix_end) );
					?>
				</td>
				<td align="center" data-cell="patient_birth" class="text-center"><?=$Patient['patient_birth'];?></td>
				<td align="center" data-cell="dirlist_diag_mkb" class="text-center font-weight-bolder"><?=$Patient['dirlist_diag_mkb'];?></td>
				<td data-cell="dirlist_diag_text"><?=$Patient['dirlist_diag_text'];?></td>
				<td align="center" data-cell="dirlist_visit_date" class="text-center"><?=$Patient['dirlist_visit_date'];?></td>
				<td align="center" data-cell="visits" class="text-center"><?=count($VisitsData);?></td>
				<td align="center" data-cell="cure_mg" class="text-center"><?=$CURE_MG;?></td>
				<td align="center" data-cell="ill_days" class="text-center"><?=round($ILL_DAYS_SECONDS / TIME_DAY);?></td>
			
			</tr>
			<?php
			$KOIKODAYS_SUMMARY += round($ILL_DAYS_SECONDS / TIME_DAY);
			$npp++;
		}
		
		?>
        <tr>
            <td colspan="6" align="right"><b>ИТОГО:</b></td>
            <td align="center"><?=$VISITS_SUMMARY;?></td>
            <td align="center"><?=$DOSE_MG_SUMMARY;?></td>
            <td align="center"><?=$KOIKODAYS_SUMMARY;?></td>
        </tr>
		</tbody>
	</table>
	<?php
} else
{
	bt_notice('Пациентов в списке нет');
}
