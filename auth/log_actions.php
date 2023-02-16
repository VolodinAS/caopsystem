<?php
$Logs_query = "
SELECT * FROM ".CAOP_LOG."
	LEFT JOIN ".CAOP_CAT_SYSTEM." ON ".CAOP_CAT_SYSTEM.".{$PK[CAOP_CAT_SYSTEM]}=".CAOP_LOG.".log_cat_id
	LEFT JOIN ".CAOP_LOG_TYPE." ON ".CAOP_LOG_TYPE.".{$PK[CAOP_LOG_TYPE]}=".CAOP_LOG.".log_action_id
	LEFT JOIN ".CAOP_DOCTOR." ON ".CAOP_DOCTOR.".{$PK[CAOP_DOCTOR]}=".CAOP_LOG.".log_action_doctor_id
WHERE 1
ORDER BY log_date_unix DESC
";

$Logs_result = mqc($Logs_query);
$Logs = mr2a($Logs_result);

foreach ($Logs as $log)
{
//	$addon_field = '';
//	$addon_value = '';
	$docname = mb_ucwords($log['doctor_f'] . ' ' . $log['doctor_i'] . ' ' . $log['doctor_o']);
	
	$addon1_field = $log['log_target_info_1'];
	$addon1_value = $log['log_target_info_2'];
	
	$addon2_field = $log['log_target_info_3'];
	$addon2_value = $log['log_target_info_4'];
	
	switch ($log['log_action_id'])
	{
        case LOG_TYPE_CREATE_PATIENT:
            $PatientData = getPatientDataById($addon1_value);
            if ( $PatientData ['result'] === true )
            {
	            $PatientData = $PatientData['data']['personal'];
	            $addon1_value = editPersonalDataLink($PatientData['patid_name'], $PatientData['patid_id']);
            }
//            debug($PatientData);
        break;
	}
	
	?>
	<div id="log_<?=$log['log_id'];?>">
		<div>
			<span class="font-weight-bolder">Дата:</span> <?=date(DMYHIS, $log['log_date_unix']);?>
		</div>
		<div>
			<span class="font-weight-bolder">IP:</span> <?=$log['log_ip'];?>
		</div>
		<div>
			<span class="font-weight-bolder">CAT-ключ:</span> <?=$log['cat_desc'];?>
		</div>
		<div>
			<span class="font-weight-bolder">Действие:</span> <?=$log['type_title'];?>
		</div>
		<div>
			<span class="font-weight-bolder">Авторизация:</span>  <?=$docname;?>
		</div>
		<div>
			<span class="font-weight-bolder"><?=$addon1_field;?>:</span>  <?=$addon1_value;?>
		</div>
		<div>
			<span class="font-weight-bolder"><?=$addon2_field;?>:</span>  <?=$addon2_value;?>
		</div>
	</div>
    <?php
    spoiler_begin('MySQL-item', 'mysql-log-' . $log['log_id']);
    debug($log);
    spoiler_end();
    ?>
	<div class="dropdown-divider"></div>
	<?php
}

//debug($Logs);

//debug($_SERVER);