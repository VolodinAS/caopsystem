<?php
$PrintParams = explode('/', $request_params);
//debug(count($PrintParams));
//exit();
if ( count($PrintParams) == 1 )
{
    $go_next = true;
	
	$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
	$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');

    if ( $go_next )
    {
        $citology_id = $PrintParams[0];

        $CitologyData = getarr('caop_citology', "citology_id='{$citology_id}'");

//        debug($CitologyData);

        if ( count($CitologyData) == 1 )
        {
            $CitologyData = $CitologyData[0];

	        $JSON_markers = stripcslashes($CitologyData['citology_analise_markers']);
	        $MarkersData = json_decode($JSON_markers);

            $Doctor = $DoctorsListId[ 'id' . $CitologyData['citology_doctor_id'] ];
            $doc_name = $Doctor['doctor_f'] . ' ' . $Doctor['doctor_i'] . ' ' . $Doctor['doctor_o'];
            $doc_name_sh = shorty($doc_name);
            $Patient_analize_data = getarr(CAOP_CITOLOGY_TYPE, "type_id={$CitologyData['citology_analise_type']}")[0]['type_title'];


            $PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$CitologyData['citology_patid']}'");

            if ( count($PatientPersonalData) == 1 )
            {
                $PatientPersonalData = $PatientPersonalData[0];

                $Insurance = $CompanyListId[ 'id'.$PatientPersonalData['patid_insurance_company'] ]['insurance_title'];
//                debug($PatientPersonalData);

                require_once ( "engine/html/title_begin_print.php" );
                require_once ( "engine/html/citology.php" );
//			debug($CitologyData);
//			debug($Doctor);
                require_once ( "engine/html/title_end_print.php" );
                exit();
            } else
            {
                bt_notice('ПАЦИЕНТА НЕТ В БАЗЕ ДАННЫХ',BT_THEME_DANGER);
            }


        } else
        {
            $go_print_error = true;
            $go_print_msg = 'Невозможно распечатать, так как не найдена запись в журнале';
        }


    }
}