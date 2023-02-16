<button class="btn btn-danger btn-sm" onclick="window.location.href = '/admin_double?go'">Поиск дублей</button>
<div class="dropdown-divider"></div>
<?php
set_time_limit(0);

if ( !isset($_GET['go']) )
{
	
	$DoubleData = getarr(CAOP_DOUBLE, "1", "ORDER BY double_found_unix DESC");
	if (count($DoubleData) > 0)
	{
		foreach ($DoubleData as $doubleDatum)
		{
		    $PatientSource = getPatientDataById($doubleDatum['double_patid_source'])['data']['personal'];
		    $PatientDouble = getPatientDataById($doubleDatum['double_patid_double'])['data']['personal'];
		    
//		    debug($PatientSource);
		    
			spoiler_begin('Пациент: ' . mb_ucwords($PatientSource['patid_name']) . ', ' . $PatientSource['patid_birth'], 'duplicates_' . $doubleDatum['double_id'], '');
			{
			    ?>
			    <b>Источник: </b> <?=editPersonalDataLink($PatientSource['patid_name'], $PatientSource['patid_id']);?>
                <br>
                <b>Дубликат: </b> <?=editPersonalDataLink($PatientDouble['patid_name'], $PatientDouble['patid_id']);?>
                <br>
                <a target="_blank" href="/admin_mergePatients/<?=$PatientSource['patid_id'];?>/<?=$PatientDouble['patid_id'];?>">Отправить в слияние</a> |
                <a href="javascript:doubleComplete(<?=$doubleDatum['double_id'];?>)"><b>Дубль слит</b></a>
			    <?php
			}
			spoiler_end();
		}
	} else
	{
		bt_notice('Дублей не найдено. Запустите <a href="/admin_double?go"><b>поиск</b></a>', BT_THEME_WARNING);
	}
 
} else
{
    $fields = 'patid_id, patid_name, patid_birth';
	
	$AllPatients = getarr(CAOP_PATIENTS, "1", "ORDER BY patid_name ASC", false, $fields);
	
	$Doubles = [];
	
	foreach ($AllPatients as $PatientData)
	{
	    echo "|";
		$PatientName = name_for_query($PatientData['patid_name']);
		
		$Duplicates = getarr(CAOP_PATIENTS, "patid_name LIKE '{$PatientName['querySearchPercent']}' AND patid_birth='{$PatientData['patid_birth']}' AND patid_id!='{$PatientData['patid_id']}'", null, false, $fields);
//		$Duplicates = getarr(CAOP_PATIENTS, "patid_name LIKE '{$PatientData['patid_name']}' AND patid_birth='{$PatientData['patid_birth']}' AND patid_id!='{$PatientData['patid_id']}'", null, false, $fields);
		
		if ( count($Duplicates) > 0 )
		{
			spoiler_begin('[#'.$PatientData['patid_id'].'] ' . mb_ucwords($PatientData['patid_name']) . ', ' . $PatientData['patid_birth'] . '. <b>Дубликаты:</b> ' . $amountSearch . ' [NameDataArray: '.strlen($PatientName['NameDataArray'][1]).']', 'patient_' . $PatientData['patid_id']);
			foreach ($Duplicates as $DuplicateData)
			{
			    
			    $param_double = array(
			        'double_patid_source' => $PatientData['patid_id'],
                    'double_patid_double' => $DuplicateData['patid_id'],
                    'double_found_unix' => time()
			    );
			    
			    $Doubles[] = $param_double;
			    
				spoiler_begin('Дубликаты', 'duplicates_' . $DuplicateData['patid_id'], '');
				{
					echo editPersonalDataLink(shorty($DuplicateData['patid_name']), $DuplicateData['patid_id'], super_bootstrap_tooltip(mb_ucwords($DuplicateData['patid_name'])));
					echo '<br>';
					echo ' <a target="_blank" href="/admin_mergePatients/'.$PatientData['patid_id'].'/'.$DuplicateData['patid_id'].'">Отправить в слияние</a>';
				}
				spoiler_end();
			}
			spoiler_end();
		}
	}
 
	foreach ($Doubles as $double)
	{
	    
	    $if_not_exists = array(
	        'index' => 'double_id',
            'query' => "double_patid_source='{$double['double_patid_source']}' AND double_patid_double='{$double['double_patid_double']}'"
	    );
	    
	    $AddDoubleINE = appendData(CAOP_DOUBLE, $double, $if_not_exists);
	    
	}
	
	meta('/admin_double/1', 10);
}
?>
<script defer type="text/javascript" src="/engine/js/admin/admin_double.js?<?=rand(0, 99999);?>"></script>