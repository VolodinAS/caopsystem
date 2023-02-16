<?php

$ShowArchiveCitology = doctor_param('get', $USER_PROFILE['doctor_id'], 'researchShowArchive');
$research_query_archive_addon = '';
if ( $ShowArchiveCitology['stat'] == RES_SUCCESS )
{
	$ShowArchiveCitology = $ShowArchiveCitology['data']['settings_param_value'];
	if ( $ShowArchiveCitology == 0 )
	{
		bt_notice('Внимание! У Вас установлена фильтрация данных. Архивные (законченные) результаты скрыты! <a href="/profileSettings">'.wrapper('Изменить...').'</a>', BT_THEME_WARNING);
		$research_query_archive_addon = " AND research_result=''";
	}
} else
{
	$ShowArchiveCitology = 1;
}

$RequestData = explode("/", $request_params);
$request_params = $RequestData[0];
$is_query = true;
if ( ifound($RequestData[0], "patient") )
{
	$user_id = str_replace("patient", "", $RequestData[0]);
	$CheckUser = getarr(CAOP_PATIENTS, "patid_id='{$user_id}'");
	if ( count($CheckUser) == 1 )
	{
		$is_query = false;
//		$AllResearch = getarr(CAOP_RESEARCH, "research_patid='{$user_id}'");
        $AllResearch_query = "SELECT * FROM ".CAOP_RESEARCH." cr LEFT JOIN ".CAOP_PATIENTS." cp ON cp.patid_id = cr.research_patid WHERE research_patid='{$user_id}'";
	}
}

if ( $is_query )
{
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
				
//				$AllResearch = getarr(CAOP_RESEARCH, "research_unix>='{$lastPeriod}'");
				$AllResearch_query = "SELECT * FROM ".CAOP_RESEARCH." cr LEFT JOIN ".CAOP_PATIENTS." cp ON cp.patid_id = cr.research_patid WHERE 1 AND research_unix>='{$lastPeriod}'";
			}

//		debug($PeriodData);
		} else
		{
//			$AllResearch = getarr(CAOP_RESEARCH, "1");
			$AllResearch_query = "SELECT * FROM ".CAOP_RESEARCH." cr LEFT JOIN ".CAOP_PATIENTS." cp ON cp.patid_id = cr.research_patid WHERE 1";
		}
	} else
	{
//		$AllResearch = getarr(CAOP_RESEARCH, "1");
		$AllResearch_query = "SELECT * FROM ".CAOP_RESEARCH." cr LEFT JOIN ".CAOP_PATIENTS." cp ON cp.patid_id = cr.research_patid WHERE 1";
	}
	
	$AllResearch_query .= $research_query_archive_addon;
}



//debug($AllResearch_query);

$AllResearch_result = mqc($AllResearch_query);
$AllResearch = mr2a($AllResearch_result);

//$AllResearch = array_orderby($AllResearch, 'research_cito', SORT_DESC, 'research_status', SORT_ASC, 'research_unix', SORT_ASC);
$AllResearch = array_orderby($AllResearch, 'research_status', SORT_ASC, 'research_cito', SORT_DESC, 'research_unix', SORT_ASC);

//debug($AllResearch);

$RequestData = explode("/", $request_params);
$request_params = $RequestData[0];
if ( ifound($RequestData[0], "light") )
{
	$light_id = str_replace("light", "", $RequestData[0]);
//	$SearchCitology = getarr(CAOP_)
}

require_once ( "engine/html/research_type_sorter.php" );
?>

<?php
if ( count($AllResearch) > 0 )
{
	?>
    <table class="table tablesorter table-sm patient_research" id="patient_research">
        <thead>
        <tr>
			<?php
			require_once ( "engine/html/researchTableHeader.php" );
			?>
        </tr>
        </thead>
        <tbody>
		<?php
		$npp = count($AllResearch);
		
		$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
		$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");
		$ResearchStatuses = getarr(CAOP_RESEARCH_STATUS, "1", "ORDER BY status_id ASC");
		$ResearchCitos = getarr(CAOP_RESEARCH_CITO, "1", "ORDER BY cito_id ASC");
		
		foreach ($AllResearch as $Patient) {
			
			//		debug($Patient);
			
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
?>

<?php
require_once ( "engine/html/modals/researchPatientCard.php" );
?>


<script defer src="/engine/js/research.js?<?=rand(0,999999);?>" type="text/javascript"></script>
