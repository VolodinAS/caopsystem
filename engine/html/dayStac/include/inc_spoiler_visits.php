<?php
if ($isPatient)
{
	
	
	$VisitsData = getarr(CAOP_DS_VISITS, "visreg_dspatid='{$PatientData['patient_id']}'", "ORDER BY visreg_visit_unix DESC");
	$ResearchData = getarr(CAOP_DS_RESEARCH, "research_patid='{$PatientData['patient_id']}'", "ORDER BY research_unix DESC");
	
	spoiler_begin('Посещения пациента (' . count($VisitsData) . ')', 'patient_visits');
	
	if (count($VisitsData) > 0)
	{


//		debug($PatientData);
//		debug($VisitsData);
		$DATA = '';
		$SUMM_DOSE = 0;
		$ILL_DAYS_SECONDS = 0;
		foreach ($VisitsData as $visregVisit)
		{
			$DoctorData = $DoctorsListId['id' . $visregVisit['visreg_doctor_id']];
			$DoctorName = docNameShort($DoctorData, "famimot");
			
			$DATA .= spoiler_begin_return('Посещение от ' . $visregVisit['visreg_visit_date'] . ' по схеме "' . $visregVisit['visreg_title'] . '", врач ' . wrapper($DoctorName), 'visreg_' . $visregVisit['visreg_id'], '');
			
			
			$SUMM_DOSE += intval($visregVisit['visreg_dose']);
			$KOIKODAY = 0;
			if (strlen($visregVisit['visreg_dispose_date']) > 0)
			{
				$KOIKODAY = intval($visregVisit['visreg_dispose_unix']) - intval($visregVisit['visreg_visit_unix']) + TIME_DAY;
				$ILL_DAYS_SECONDS += $KOIKODAY;
			} else
			{
				$KOIKODAY = TIME_DAY;
				$ILL_DAYS_SECONDS += $KOIKODAY;
			}
			
			//$response['debug']['$DoctorsListId'] = $DoctorsListId;
			$date_dispose = (strlen($visregVisit['visreg_dispose_date']) > 0) ? date("d.m.Y", $visregVisit['visreg_dispose_unix']) : 'еще лечится';
			
			$koikodays = (strlen($visregVisit['visreg_dispose_date']) > 0) ? ($KOIKODAY / TIME_DAY) : 'еще лечится';
			
			$visreg_dose_measure_type = $DOSE_MEASURE_TYPES_ID['id' . $visregVisit['visreg_dose_measure_type']]['type_title'];
			$visreg_dose_period_type = $DOSE_PERIOD_TYPES_ID['id' . $visregVisit['visreg_dose_period_type']]['type_title'];
			$visreg_freq_period_type = $DOSE_FREQ_PERIOD_TYPES_ID['id' . $visregVisit['visreg_freq_period_type']]['type_title'];
//			$DATA .= '<b>Название схемы:</b> '.$visregVisit['visreg_title'].'<br>';
			$DATA .= '<b>Название препарата:</b> ' . $visregVisit['visreg_drug'] . '<br>';
			$DATA .= '<b>Дозировка препарата:</b> ' . $visregVisit['visreg_dose'] . ' ' . $visreg_dose_measure_type . ' / ' . $visreg_dose_period_type . '<br>';
			$DATA .= '<b>Частота процедуры:</b> ' . $visregVisit['visreg_freq_amount'] . ' раз в ' . $visregVisit['visreg_freq_period_amount'] . ' ' . $visreg_freq_period_type . '<br>';
			$DATA .= '<b>Da. Signa.:</b> ' . $visregVisit['visreg_dasigna'] . '<br>';
			$DATA .= '<b>Дата госпитализации:</b> ' . date("d.m.Y", $visregVisit['visreg_visit_unix']) . '<br>';
			$DATA .= '<b>Дата выписки:</b> ' . $date_dispose . '<br>';
			$DATA .= '<b>Койкодней:</b> ' . $koikodays;

//			$DATA .= debug_ret($visregVisit);
			
			$DATA .= spoiler_end_return();
			
			
		}
		
		spoiler_begin('Статистика посещений', 'patient_visits_stat', '');
		?>
        <b>ВИЗИТОВ:</b> <?= count($VisitsData); ?><br>
        <b>Введено препарата:</b> <?= $SUMM_DOSE; ?><br>
        <b>Койкодней:</b> <?= ($ILL_DAYS_SECONDS / TIME_DAY); ?>
		<?php
		spoiler_end();
		
		echo $DATA;
		
		
	} else
	{
		bt_notice('У пациента нет еще визитов', BT_THEME_WARNING);
	}
	
	spoiler_end();
	
	spoiler_begin('Динамика результатов анализов', 'patient_research');
//	debug($ResearchData);
	$ANALISES_HTML = '';
	$AnaliseGroups = array();
	
	foreach ($ResearchData as $researchDatum)
	{
		$AnaliseGroups['id' . $researchDatum['research_resid']][] = $researchDatum;
	}
	
	foreach ($AnaliseGroups as $analiseID => $analiseGroup)
	{
		$ResearchData = $DS_RESEARCH_TYPES_ID[$analiseID];
		spoiler_begin('Динамика показателей анализа: ' . $ResearchData['type_title'], 'dynamic_' . $ResearchData['type_id'], '');
		
//		debug($ResearchData);
		if ($ResearchData['type_addon'] == 'graphic')
		{
		    $labels_arr = [];
		    $data_arr = [];
			foreach ($analiseGroup as $analiseItem)
			{
			    $labels_arr[] = "'{$analiseItem['research_date']}'";
				$data_arr[] = str_replace(',', '.', $analiseItem['research_value']);
    
			}
			
			$minmax_arr = explode(';', $ResearchData['type_addon_2']);
			$min_value = $minmax_arr[0];
			$max_value = $minmax_arr[1];
			
			$labels_str = implode(', ', $labels_arr);
			$data_str = implode(', ', $data_arr);
			
//			debug($labels_str);
//			debug($data_str);
			
			?>
            <div>
                <canvas id="graphic<?= $ResearchData['type_id']; ?>"></canvas>
            </div>
            <script>
                let labels_<?=$ResearchData['type_id'];?> = [<?=$labels_str;?>];

                let data_<?=$ResearchData['type_id'];?> = {
                    labels: labels_<?=$ResearchData['type_id'];?>,
                    datasets: [{
                        label: 'Динамика показателя <?=$ResearchData['type_title'];?>',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: [<?=$data_str;?>],
                        pointStyle: 'circle',
                        pointRadius: 10,
                        pointHoverRadius: 15
                    }]
                };

                let config_<?=$ResearchData['type_id'];?> = {
                    type: 'line',
                    data: data_<?=$ResearchData['type_id'];?>,
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                min: <?=$min_value;?>,
                                max: <?=$max_value;?>,
                            }
                        }
                    }
                };

                const myChart_<?=$ResearchData['type_id'];?> = new Chart(
                    document.getElementById('graphic<?= $ResearchData['type_id']; ?>'),
                    config_<?=$ResearchData['type_id'];?>
                );
            </script>
			<?php
		}
//		echo wrapper($ResearchData['type_title'] . ':') . '<br>';
		foreach ($analiseGroup as $analiseItem)
		{
			echo _nbsp(5) . '<button type="button" class="btn btn-sm btn-warning deleteResearch" data-researchid="' . $analiseItem['research_id'] . '">' . BT_ICON_CLOSE . '</button> от ' . $analiseItem['research_date'] . ' - ' . wrapper($analiseItem['research_value']) . '<br>';
		}
		spoiler_end();
		echo '<br>';
	}
	spoiler_end();
}
?>

