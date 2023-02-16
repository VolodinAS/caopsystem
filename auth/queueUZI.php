<?php
$Research_UZI = getarr('caop_research', "research_type='3'");
//debug($AllResearch);

$Research_UZI = array_orderby($Research_UZI, 'research_status', SORT_ASC, 'research_unix', SORT_ASC);
// 'patient_order', SORT_ASC
//, 'research_status', SORT_ASC
//

if ( count($Research_UZI) > 0 )
{
	
	$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
	$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");
	$ResearchStatuses = getarr(CAOP_RESEARCH_STATUS, "1", "ORDER BY status_id ASC");
	$ResearchCitos = getarr(CAOP_RESEARCH_CITO, "1", "ORDER BY cito_id ASC");

	foreach ($Research_UZI as $Patient) {
//		debug($Patient);
		include ( "engine/html/research_patientlist.php" );
	}

} else
{
	bt_notice('Нет записанных в очередь пациентов',BT_THEME_PRIMARY);
}

?>


<script defer src="/engine/js/research.js?<?=rand(0,999999);?>" type="text/javascript"></script>
