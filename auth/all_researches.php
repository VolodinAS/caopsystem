<?php
//debugn($_GET, '$_GET');
$query = "";
if ( count($_GET) > 0 )
{
	
	foreach ($_GET as $key=>$value)
	{
		$field = str_replace('|', '.', $key);
		if ( strlen($value) > 0 )
		{
		    $value_data = explode(';', $value);
		    if ( count($value_data) > 0 )
		    {
			    $query .= " AND (";
			    
				$pre_query = "";
			    foreach ($value_data as $value_datum)
			    {
				    if ( strlen($pre_query) > 0 )
				    {
				        $pre_query .= " OR {$field}='{$value_datum}'";
				    } else
				    {
					    $pre_query .= " {$field}='{$value_datum}'";
				    }
		        }
			    
			    $query .= $pre_query;
			
			    $query .= ")";
		    }
		}
    }
}

//debugn($query, '$query');

$min_date = '01.01.2023';
$min_date_unix = birthToUnix($min_date);

$WHERE = " AND research_unix>='{$min_date_unix}'";

$fields = [
	CAOP_RESEARCH.".research_id",
	CAOP_RESEARCH.".research_patid",
	CAOP_RESEARCH.".research_type",
	CAOP_RESEARCH.".research_area",
	CAOP_RESEARCH.".research_unix",
	CAOP_RESEARCH.".patidcard_patient_done",
	CAOP_RESEARCH.".research_result",
	CAOP_RESEARCH.".research_morph_ident",
	CAOP_RESEARCH.".research_morph_date",
	CAOP_RESEARCH.".research_morph_text",
	CAOP_PATIENTS.".patid_ident",
	CAOP_PATIENTS.".patid_name",
	CAOP_PATIENTS.".patid_birth",
	CAOP_DOCTOR.".doctor_f",
	CAOP_DOCTOR.".doctor_i",
	CAOP_DOCTOR.".doctor_o",
	CAOP_RESEARCH_TYPES.".type_title as rt_type_title",
	CAOP_CITOLOGY_CANCER_TYPE.".type_title as cct_type_title",
];
//$fields = ['*'];

$Research_query = "
SELECT ".implode(",\n", $fields)." FROM ".CAOP_RESEARCH."
LEFT JOIN ".CAOP_PATIENTS." ON ".CAOP_PATIENTS.".{$PK[CAOP_PATIENTS]} = ".CAOP_RESEARCH.".research_patid
LEFT JOIN ".CAOP_DOCTOR." ON ".CAOP_DOCTOR.".{$PK[CAOP_DOCTOR]} = ".CAOP_RESEARCH.".research_doctor_id
LEFT JOIN ".CAOP_RESEARCH_TYPES." ON ".CAOP_RESEARCH_TYPES.".{$PK[CAOP_RESEARCH_TYPES]} = ".CAOP_RESEARCH.".research_type
LEFT JOIN ".CAOP_CITOLOGY_CANCER_TYPE." ON ".CAOP_CITOLOGY_CANCER_TYPE.".{$PK[CAOP_CITOLOGY_CANCER_TYPE]} = ".CAOP_RESEARCH.".research_cancer
WHERE 1
{$WHERE}
{$query}
ORDER BY research_unix DESC
#LIMIT 1
";
//debugn($Research_query, '$Research_query');
$Research_result = mqc($Research_query);
$Researches = mr2a($Research_result);

spoiler_begin('Researches[0]', 'Researches0');
debugn($Researches[0], '$Researches');
spoiler_end();
$npp = 1;
?>

<table class="table">
    <thead>
        <tr>
            <th scope="col" class="text-center">№</th>
            <th scope="col" class="text-center">Пациент</th>
            <th scope="col" class="text-center">Врач</th>
            <th scope="col" class="text-center">Обследование</th>
            <th scope="col" class="text-center">Результат</th>
            <th scope="col" class="text-center">Морфология</th>
            <th scope="col" class="text-center">Проведено</th>
            <th scope="col" class="text-center">Добавлено</th>
            <th scope="col" class="text-center" <?=super_bootstrap_tooltip('PCS - Precancerous status - Предраковое состояние');?>>PCS</th>
        </tr>
    </thead>
    <tbody>
    <?php
	if ( count($Researches) > 0 )
	{
		foreach ($Researches as $research)
		{
			$morph = 'нет';
			if ( strlen($research['research_morph_ident']) > 0 ) $morph .= '№' . $research['research_morph_ident'];
			if ( strlen($research['research_morph_date']) > 0 ) $morph .= ' от ' . $research['research_morph_date'];
			if ( strlen($research['research_morph_text']) > 0 ) $morph .= ' - ' . $research['research_morph_text'];
			
		?>
		<tr>
			<td><?=$npp;?></td>
			<td>
				<?=editPersonalDataLink(shorty(mb_ucwords($research['patid_name']), "famimot"), $research['patid_id']);?>, <?=$research['patid_birth'];?> г.р.
			</td>
			<td>
				<?=docNameShort($research);?>
			</td>
			<td>
				<?=$research['rt_type_title'];?> <?=$research['research_area'];?>
			</td>
			<td>
				<?=$research['research_result'];?>
			</td>
			<td>
				<?=$morph;?>
			</td>
			<td>
				<?=$research['patidcard_patient_done'];?>
			</td>
			<td>
				<?=date(DMY, $research['research_unix']);?>
			</td>
			<td>
				<?=$research['cct_type_title'];?>
			</td>
		</tr>
		<?php
			$npp++;
		}
	} else
	{
	?>
		<tr>
			<td colspan="100%"><? bt_notice('Обследований в списке нет', BT_THEME_WARNING); ?></td>
		</tr>
	<?php
	}
    ?>
    </tbody>
</table>

<?php