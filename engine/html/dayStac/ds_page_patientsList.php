<?php
//$DSPatients = getarr(CAOP_DS_PATIENTS, "1", "ORDER BY patient_fio ASC");

$query_PatientDirlist = "SELECT * FROM {$CAOP_DS_PATIENTS}
                            LEFT JOIN {$CAOP_DS_DIRLIST}
                                ON {$CAOP_DS_PATIENTS}.patient_id={$CAOP_DS_DIRLIST}.dirlist_dspatid
                            WHERE {$CAOP_DS_DIRLIST}.dirlist_isMain='1'
                            ORDER BY {$CAOP_DS_PATIENTS}.patient_fio";
$result_PatientDirlist = mqc($query_PatientDirlist);
$DSPatients = mr2a($result_PatientDirlist);

if ( count($DSPatients) > 0 )
{
	$npp = 1;
	
?>
    <table class="table table-sm dspatients fixtable">
        <thead>
            <tr>
                <th width="1%" scope="col" class="text-center" data-title="npp">№</th>
                <th scope="col" class="text-center" data-title="patient_fio">
                    Ф.И.О.
                    <input type="text" class="form-control-sm form-control searchByTableField"
                           data-classname="patient-name" data-tablename="dspatients" disabled>
                </th>
                <th width="1%" scope="col" class="text-center" data-title="patient_birth">Дата рождения</th>
                <th width="1%" scope="col" class="text-center" data-title="dirlist_diag_mkb" <?=super_bootstrap_tooltip('По основному направлению');?>>МКБ</th>
                <th scope="col" class="text-center" data-title="dirlist_diag_text" <?=super_bootstrap_tooltip('По основному направлению');?>>Диагноз</th>
                <th scope="col" class="text-center" data-title="dirlist_doc_date" <?=super_bootstrap_tooltip('По основному направлению');?>>Дата направления</th>
                <th scope="col" class="text-center" data-title="dirlist_visit_date" <?=super_bootstrap_tooltip('По основному направлению');?>>Дата явки</th>
                <th scope="col" class="text-center" data-title="dirlist_done_date" <?=super_bootstrap_tooltip('По основному направлению');?>>Дата окончания</th>
                <th width="1%" scope="col" class="text-center" data-title="actions">Действия</th>
            </tr>
        </thead>
        <tbody>
        
        
<?php
	
	foreach ($DSPatients as $Patient)
	{
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
            <td data-cell="npp" class="font-weight-bolder text-center"><?=$npp;?>)</td>
            <td data-cell="patient_fio" class="patient-name">
                <?=mb_ucwords($Patient['patient_fio']);?>
                <?php
//                debug($Patient);
                ?>
            </td>
            <td data-cell="patient_birth" class="text-center"><?=$Patient['patient_birth'];?></td>
            <td data-cell="dirlist_diag_mkb" class="text-center font-weight-bolder"><?=$Patient['dirlist_diag_mkb'];?></td>
            <td data-cell="dirlist_diag_text"><?=$Patient['dirlist_diag_text'];?></td>
            <td data-cell="dirlist_doc_date" class="text-center"><?=$Patient['dirlist_doc_date'];?></td>
            <td data-cell="dirlist_visit_date" class="text-center"><?=$Patient['dirlist_visit_date'];?></td>
            <td data-cell="dirlist_done_date" class="text-center"><?=$dirlist_done_date?></td>
            <td data-cell="actions" class="text-center">
                <div class="dropdown div-jinline dropleft" data-title="Действия">
                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                            id="buttonPatient<?= $Patient['patient_id']; ?>" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <?=BT_ICON_ACTIONS;?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="buttonPatient<?= $Patient['patient_id']; ?>">
                        <a class="dropdown-item" href="/dayStac/newPatient/<?=$Patient['patient_id'];?>">Карта пациента</a>
                    </div>
                </div>
            </td>
            
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