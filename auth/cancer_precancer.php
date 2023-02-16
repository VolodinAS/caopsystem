<?php
$min_date = birthToUnix('01.01.2023');

//AND research_cancer!='0'
$main_request_research = "
SELECT * FROM ".CAOP_RESEARCH."
WHERE research_unix>='{$min_date}' AND research_cancer!='0'
";

//AND citology_cancer!='0'
$main_request_citology = "
SELECT * FROM ".CAOP_CITOLOGY."
WHERE citology_dir_date_unix>='{$min_date}' AND citology_cancer!='0'
";

$Researches_result = mqc($main_request_research);
$Researches = mr2a($Researches_result);

$Citologies_result = mqc($main_request_citology);
$Citologies = mr2a($Citologies_result);

$Total = array_merge($Researches, $Citologies);


$ResearchReportTypes = array(
    'УЗИ' => array(
    	'is_available' => true,
    	'is_cancer' => false,
        'total_surveys' => 0,
	    'cancers' => '---',
	    'precancerous' => 0,
	    'collector' => array(
	        'ident_field' => 'research_type',
		    'ident_value' => 3,
		    'ident_cancer' => 'research_cancer',
	    ),
    ),
    'Пункции под УЗИ' => array(
	    'is_available' => true,
	    'is_cancer' => true,
	    'total_surveys' => 0,
	    'cancers' => 0,
	    'precancerous' => 0,
	    'collector' => array(
		    'ident_field' => 'citology_analise_type',
		    'ident_value' => 3,
		    'ident_cancer' => 'citology_cancer',
	    ),
    ),
    'Рентген' => array(
	    'is_available' => true,
	    'is_cancer' => false,
	    'total_surveys' => 0,
	    'cancers' => '---',
	    'precancerous' => 0,
	    'collector' => array(
		    'ident_field' => 'research_type',
		    'ident_value' => 6,
		    'ident_cancer' => 'research_cancer',
	    ),
    ),
    'Маммограф' => array(
	    'is_available' => true,
	    'is_cancer' => false,
	    'total_surveys' => 0,
	    'cancers' => '---',
	    'precancerous' => 0,
	    'collector' => array(
		    'ident_field' => 'research_type',
		    'ident_value' => 8,
		    'ident_cancer' => 'research_cancer',
	    ),
    ),
    'Флюорографы' => array(
	    'is_available' => false,
	    'is_cancer' => false,
	    'total_surveys' => '---',
	    'cancers' => '---',
	    'precancerous' => '---'
    ),
    'ФГС' => array(
	    'is_available' => true,
	    'is_cancer' => true,
	    'total_surveys' => 0,
	    'cancers' => 0,
	    'precancerous' => 0,
	    'biopsy' => 0,
	    'collector' => array(
		    'ident_field' => 'research_type',
		    'ident_value' => 2,
		    'ident_cancer' => 'research_cancer',
	    ),
    ),
    'ФКС' => array(
	    'is_available' => true,
	    'is_cancer' => true,
	    'total_surveys' => 0,
	    'cancers' => 0,
	    'precancerous' => 0,
	    'biopsy' => 0,
	    'collector' => array(
		    'ident_field' => 'research_type',
		    'ident_value' => 1,
		    'ident_cancer' => 'research_cancer',
	    ),
    ),
    'ФБС' => array(
	    'is_available' => false,
	    'is_cancer' => false,
	    'total_surveys' => '---',
	    'cancers' => '---',
	    'precancerous' => '---'
    ),
    'RRS' => array(
	    'is_available' => false,
	    'is_cancer' => false,
	    'total_surveys' => '---',
	    'cancers' => '---',
	    'precancerous' => '---'
    ),
    'Биопсии при эндоск. исследованиях' => array(
	    'is_available' => false,
	    'is_cancer' => false,
	    'total_surveys' => '---',
	    'cancers' => '---',
	    'precancerous' => '---'
    ),
    'Гистероскопия' => array(
	    'is_available' => false,
	    'is_cancer' => false,
	    'total_surveys' => '---',
	    'cancers' => '---',
	    'precancerous' => '---'
    ),
    'Цистоскопия' => array(
	    'is_available' => false,
	    'is_cancer' => false,
	    'total_surveys' => '---',
	    'cancers' => '---',
	    'precancerous' => '---'
    ),
);

foreach ($Total as $survey_item)
{
	foreach ($ResearchReportTypes as $reportTitle => $reportData)
	{
	    // если статистика включена
        if ( $reportData['is_available'] )
        {
            // если есть коллектор
            if ( count($reportData['collector']) > 0 )
            {
                // получаем название искомого идентификатора таблицы
                $ident_field = $reportData['collector']['ident_field'];
                
                if ( array_key_exists($ident_field, $survey_item) )
                {
                    // если идентификатор ЕСТЬ в данных
                    $survey_ident_value = $survey_item[$ident_field];
                    $collector_ident_value = $reportData['collector']['ident_value'];
                    if ( $survey_ident_value == $collector_ident_value )
                    {
                        $cancer_ident = $reportData['collector']['ident_cancer'];
                        $cancer_result = $survey_item[$cancer_ident];
                        if ( $cancer_result == 1 )
                        {
                            // РАК
                            $ResearchReportTypes[$reportTitle]['cancers'] += 1;
                        } elseif ( $cancer_result == 2)
                        {
                            // предрак
	                        $ResearchReportTypes[$reportTitle]['precancerous'] += 1;
                        }
                        
                        if ( array_key_exists('research_morph_text', $survey_item) )
                        {
                            if ( strlen($survey_item['research_morph_text']) > 0 )
                            {
	                            $ResearchReportTypes[$reportTitle]['biopsy'] += 1;
                            }
                        }
	
	                    $ResearchReportTypes[$reportTitle]['total_surveys'] += 1;
                    }
                }
                
            }
        }
	}
}

?>

<table class="tbc tbc-border">
    <thead>
        <tr>
            <th scope="col" class="text-center" data-title="type">Вид исследования</th>
            <th scope="col" class="text-center" data-title="doctor_state">Врачей по штату</th>
            <th scope="col" class="text-center" data-title="doctor_fact">Врачей фактически</th>
            <th scope="col" class="text-center" data-title="doctor_duty">Занятые штатные должности</th>
            <th scope="col" class="text-center" data-title="devices">Число аппаратов</th>
            <th scope="col" class="text-center" data-title="device_production">Год выпуска</th>
            <th scope="col" class="text-center" data-title="survey_amount">Число исследований/<br>Число биопсий</th>
            <th scope="col" class="text-center" data-title="cancers">Выявлено рака</th>
            <th scope="col" class="text-center" data-title="precancerous">Выявлено предрака</th>
            <th scope="col" class="text-center" data-title="queue">Очередность</th>
        </tr>
    </thead>
    <tbody>
    <?php
    
    $ResearchReportTypes['Биопсии при эндоск. исследованиях']['biopsy'] = $ResearchReportTypes['ФКС']['biopsy'] + $ResearchReportTypes['ФГС']['biopsy'];
    $ResearchReportTypes['Биопсии при эндоск. исследованиях']['cancers'] = $ResearchReportTypes['ФКС']['cancers'] + $ResearchReportTypes['ФГС']['cancers'];
    $ResearchReportTypes['Биопсии при эндоск. исследованиях']['precancerous'] = $ResearchReportTypes['ФКС']['precancerous'] + $ResearchReportTypes['ФГС']['precancerous'];
    
    foreach ($ResearchReportTypes as $reportTitle=>$reportData)
    {
	    $biopsy_format = '';
        if ( strlen($reportData['biopsy']) > 0 )
        {
	        $biopsy_format = '/' . $reportData['biopsy'];
        }
        if ( $reportTitle == 'Биопсии при эндоск. исследованиях' )
        {
	        $reportData['total_surveys'] = '';
            $biopsy_format = $reportData['biopsy'];
        }
    ?>
		<tr>
			<td data-cell="type">
				<?=$reportTitle;?>
			</td>
			<td data-cell="doctor_state" class="full-center">
			
			</td>
			<td data-cell="doctor_fact" class="full-center">
			
			</td>
			<td data-cell="doctor_duty" class="full-center">
			
			</td>
			<td data-cell="devices" class="full-center">
			
			</td>
			<td data-cell="device_production" class="full-center">
			
			</td>
			<td data-cell="survey_amount" class="full-center">
				<?=$reportData['total_surveys'];?><?=$biopsy_format;?>
			</td>
			<td data-cell="cancers" class="full-center">
				<?=$reportData['cancers'];?>
			</td>
			<td data-cell="precancerous" class="full-center">
				<?=$reportData['precancerous'];?>
			</td>
			<td data-cell="queue" class="full-center"></td>
		</tr>
    <?php
    }
    ?>
    </tbody>
</table>

<?php
//debug($Total);
?>