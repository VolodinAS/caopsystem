<?php
$NotQueueResearch = getarr('caop_research', "research_type='0'", "ORDER BY research_unix DESC");
//debug($NotQueueResearch);

$NotQueueResearch = array_orderby($NotQueueResearch, 'research_cito', SORT_DESC, 'research_status', SORT_ASC, 'research_unix', SORT_ASC);

if ( count($NotQueueResearch) > 0 )
{
	?>
    <table class="table tablesorter table-sm" id="patient_research">
        <thead>
        <tr>
            <?php
            require_once ( "engine/html/researchTableHeader.php" );
            ?>
        </tr>
        </thead>
        <tbody>
		<?php
		$npp = count($NotQueueResearch);
		
		$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
		$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");
		$ResearchStatuses = getarr(CAOP_RESEARCH_STATUS, "1", "ORDER BY status_id ASC");
		$ResearchCitos = getarr(CAOP_RESEARCH_CITO, "1", "ORDER BY cito_id ASC");
		
		foreach ($NotQueueResearch as $Patient) {
			$CITO = '';
			if ( $Patient['research_cito'] == 2 ) $CITO = 'table-danger';

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
	bt_notice('Нет пациентов, которым требуется указать тип обследования',BT_THEME_PRIMARY);
}

require_once ( "engine/html/modals/researchPatientCard.php" );

?>
<script defer src="/engine/js/research.js?<?=rand(0,999999);?>" type="text/javascript"></script>
