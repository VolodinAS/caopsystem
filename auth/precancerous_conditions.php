<?php
const DOC_HEAD_ID = '16';

//debug($USER_PROFILE);
//exit();
$min_date = birthToUnix('01.01.2023');

$show_all_citology = " AND citology_cancer='0'";
$show_all_research = " AND research_cancer='0'";
if ( isset($_GET['showall']) )
{
	$show_all_citology = "";
	$show_all_research = "";
}

$main_request_research = "
SELECT * FROM ".CAOP_RESEARCH."
LEFT JOIN ".CAOP_PATIENTS." ON ".CAOP_PATIENTS.".patid_id=".CAOP_RESEARCH.".research_patid
WHERE research_unix>='{$min_date}' {$show_all_research}
";

$main_request_citology = "
SELECT * FROM ".CAOP_CITOLOGY."
LEFT JOIN ".CAOP_PATIENTS." ON ".CAOP_PATIENTS.".patid_id=".CAOP_CITOLOGY.".citology_patid
WHERE citology_dir_date_unix>='{$min_date}' {$show_all_citology}
";

if ( $USER_PROFILE['doctor_id'] == DOC_HEAD_ID )
{
	$is_doc_head = true;
	// структура для заведующего
} else
{
	$is_doc_head = false;
	// структура для врача
    /*if ( $USER_PROFILE['doctor_id'] == 1 )
    {
    
    } else
    {
	    $main_request_research .= " AND research_doctor_id='{$USER_PROFILE['doctor_id']}'";
	    $main_request_citology .= " AND citology_doctor_id='{$USER_PROFILE['doctor_id']}'";
    }*/
	
	$main_request_research .= " AND research_doctor_id='{$USER_PROFILE['doctor_id']}'";
	$main_request_citology .= " AND citology_doctor_id='{$USER_PROFILE['doctor_id']}'";
	
}

$Researches_result = mqc($main_request_research);
$Researches = mr2a($Researches_result);

$Citologies_result = mqc($main_request_citology);
$Citologies = mr2a($Citologies_result);

$Total = array_merge($Researches, $Citologies);

//debug($Total);

$SurveyResultTypes2 = getarr(CAOP_CITOLOGY_CANCER_TYPE, 1, "ORDER BY type_order ASC");

$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');

$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');

//debug($is_doc_head);

if ($is_doc_head)
{
    if ( count($Total) > 0 )
    {
//	    debug($Total);
        
        bt_notice(wrapper('Всего исследований: ') . count($Total), BT_THEME_WARNING);
        
        $StructuredTotal = group_array_by_field(
            $Total,
            array(
                'research_doctor_id',
                'citology_doctor_id'
            ),
            'doctor_'
        );
	
	    echo tab_menu_begin('doctor_list');
	    $is_first = true;
	    foreach ($StructuredTotal as $doctor_prefix => $Data)
	    {
		    $doctor_data = explode('_', $doctor_prefix);
		    $doctor_id = $doctor_data[1];
		    $DoctorData = $DoctorsListId['id' . $doctor_id];
		    $ab = $sb = false;
		    if ( $is_first )
            {
	            $ab = $sb = true;
	            $is_first = false;
            }
		    echo tab_menu_item($doctor_prefix, docNameShort($DoctorData), '', '', $sb, $ab);
	    }
	    echo tab_menu_end();
	
	    echo tab_content_begin('doctor_list');
	    $is_first = true;
	    foreach ($StructuredTotal as $doctor_prefix => $Total)
	    {
		    $ab = $sb = $fb = false;
		    if ( $is_first )
		    {
			    $ab = $sb = $fb = true;
			    $is_first = false;
		    }
		    echo tab_pane_begin($doctor_prefix, $fb, $sb, $ab);
		    include("engine/html/include/precancerous_conditions/pc_iterator.php");
		    echo tab_end();
	    }
	
	    echo tab_end();
    }
} else
{
    include("engine/html/include/precancerous_conditions/pc_iterator.php");
}

//debug($Total);
?>
<script defer type="text/javascript" src="/engine/js/precancerous_conditions.js?<?=rand(0, 9999);?>"></script>
