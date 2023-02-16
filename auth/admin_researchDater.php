<?php
$AllResearch = getarr(CAOP_RESEARCH, "1", "ORDER BY research_patid ASC");
foreach ($AllResearch as $research)
{
	if ( strlen($research['research_patient_talon'])>1 )
	{
		$Talon = explode(".", $research['research_patient_talon']);
		
		if ( count($Talon) == 3 )
		{
	
		} else
		{
			spoiler_begin('[#'.$research['research_id'].'] <b>research_patient_talon</b>', 'research_talon_' . $research['research_id']);
			{
				debug($research);
				echo '<a href="javascript:openResearchCard(' . $research['research_id'] . ')"><b>Редактировать</b></a>';
			}
			spoiler_end();
		}
	}
	
	if ( strlen($research['patidcard_patient_done'])>1 )
	{
		$Done = explode(".", $research['patidcard_patient_done']);
		
		if ( count($Done) == 3 )
		{
		
		} else
		{
			spoiler_begin('[#'.$research['research_id'].'] <b>patidcard_patient_done</b>', 'research_done_' . $research['research_id']);
			{
				debug($research);
				echo '<a href="javascript:openResearchCard(' . $research['research_id'] . ')"><b>Редактировать</b></a>';
			}
			spoiler_end();
		}
	}
	
	
}
include ( "engine/html/modals/editResearch.php" );
?>
<script defer type="text/javascript" src="/engine/js/journal.js"></script>

