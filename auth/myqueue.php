<?php

$ShowArchiveResearch = doctor_param('get', $USER_PROFILE['doctor_id'], 'researchShowArchive');
$research_query_archive_addon = '';
if ( $ShowArchiveResearch['stat'] == RES_SUCCESS )
{
	$ShowArchiveResearch = $ShowArchiveResearch['data']['settings_param_value'];
	if ( $ShowArchiveResearch == 0 )
    {
	    bt_notice('Внимание! У Вас установлена фильтрация данных. Архивные (законченные) результаты скрыты! <a href="/profileSettings">'.wrapper('Изменить...').'</a>', BT_THEME_WARNING);
        $research_query_archive_addon = " AND research_status!=4";
    }
} else
{
	$ShowArchiveResearch = 1;
}


$ResearchPeriod = doctor_param('get', $USER_PROFILE['doctor_id'], 'researchShowMePeriod');
if ( $ResearchPeriod['stat'] == RES_SUCCESS )
{
	$ShowMePeriodResearch = $ResearchPeriod['data']['settings_param_value'];
	if ( $ShowMePeriodResearch != "all" )
	{
		//citology_dir_date_unix
		$PeriodData = PeriodToSec($ShowMePeriodResearch);
		
		if ( $PeriodData !== false )
		{
			bt_notice('Внимание! У Вас установлена фильтрация данных. Отображены результаты за последние '.wrapper($PeriodData['title']) . '. <a href="/profileSettings">'.wrapper('Изменить...').'</a>', BT_THEME_WARNING);
			$lastPeriod = time() - $PeriodData['interval'];
			
			$Research_doctor = getarr('caop_research', "research_unix>='{$lastPeriod}' AND research_doctor_id='{$USER_PROFILE['doctor_id']}'" . $research_query_archive_addon);
		}

//		debug($PeriodData);
	} else
	{
		$Research_doctor = getarr('caop_research', "research_doctor_id='{$USER_PROFILE['doctor_id']}'" . $research_query_archive_addon);
	}
} else
{
	$Research_doctor = getarr('caop_research', "research_doctor_id='{$USER_PROFILE['doctor_id']}'" . $research_query_archive_addon);
}



//debug($AllResearch);

//$Research_doctor = array_orderby($Research_doctor, 'research_cito', SORT_DESC, 'research_status', SORT_ASC, 'research_unix', SORT_ASC);
//$Research_doctor = array_orderby($Research_doctor, 'research_status', SORT_ASC, 'research_cito', SORT_DESC, 'research_unix', SORT_ASC);
$Research_doctor = array_orderby($Research_doctor, 'research_status', SORT_ASC, 'research_cito', SORT_DESC, 'research_unix', SORT_DESC);
// 'patient_order', SORT_ASC
//, 'research_status', SORT_ASC
//

if ( count($Research_doctor) > 0 )
{
	?>
    <table class="table table-sm" id="patient_research">
        <thead>
        <tr>
	        <?php
	        require_once ( "engine/html/researchTableHeader.php" );
	        ?>
        </tr>
        </thead>
        <tbody>
		<?php
		$npp = count($Research_doctor);
		
		$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
		$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");
		$ResearchStatuses = getarr(CAOP_RESEARCH_STATUS, "1", "ORDER BY status_id ASC");
		$ResearchCitos = getarr(CAOP_RESEARCH_CITO, "1", "ORDER BY cito_id ASC");
		
		foreach ($Research_doctor as $Patient) {
//		debug($Patient);
//			include ( "engine/html/research_patientlist.php" );

            $PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$Patient['research_patid']}'");
            if ( count($PatientPersonalData) == 1 ) {
                $PatientPersonalData = $PatientPersonalData[0];
                include("engine/html/research_patientlist.php");
            }

			$npp--;
		}
		?>
        </tbody>
    </table>
	<?php
} else
{
	bt_notice('Нет записанных в очередь пациентов',BT_THEME_PRIMARY);
}

require_once ( "engine/html/modals/researchPatientCard.php" );

?>


<script defer src="/engine/js/research.js?<?=rand(0,999999);?>" type="text/javascript"></script>
