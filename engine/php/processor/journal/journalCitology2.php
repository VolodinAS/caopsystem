<?php
$response['stage'] = $action;

$journal_id = $_POST['patvis'];
$patid = $_POST['patient_id'];
$citotype = $_POST['citotype'];
$cancer = $_POST['cancer'];
$result = $_POST['result'];
$result_date = $_POST['result_date'];
$result_ident = $_POST['result_ident'];
$is_add = ( $_POST['is_add'] == "true" ) ? true : false;

$PatientData = getarr(CAOP_JOURNAL, "journal_id='{$journal_id}'");
//$response['debug']['$PatientData'] = $PatientData;
if ( count($PatientData) == 1 )
{
	$PatientData = $PatientData[0];

	if ( $citotype > 0 )
	{
      	if ( !$is_add )
        {
          	if ( $cancer > 0 )
            {
                $biomark = $_POST['biomark'];
                $biomark_arr = explode("\n", $biomark);

                $response['debug']['$biomark_arr'] = $biomark_arr;
                if ( count($biomark_arr) > 0 )
                {
                    $json_string = json_encode($biomark_arr, JSON_UNESCAPED_UNICODE);
                }

                $CitologyDirect = array(
                    'citology_journal_id'   =>  $PatientData['journal_id'],
                    'citology_doctor_id'    =>  $PatientData['journal_doctor'],
                    'citology_ds_mkb'   =>  $PatientData['journal_ds'],
                    'citology_ds_text'  =>  $PatientData['journal_ds_text'],
                    'citology_dir_date_unix'    =>  time(),
                    'citology_action_date'    =>  date("d.m.Y", time()),
                    'citology_dir_doctor_id'    =>  $PatientData['journal_doctor'],
                    'citology_patid'    =>  $patid,
                    'citology_analise_type' => $citotype,
                    'citology_analise_markers'    =>  $json_string,
                    'citology_cancer'    =>  $cancer,
                    'citology_result_text'    =>  $result,
                    'citology_result_id'    =>  $result_ident,
                    'patidcard_patient_done'    =>  $result_date,
                );

                $response['debug']['$CitologyDirect'] = $CitologyDirect;

                $NewCitology = appendData(CAOP_CITOLOGY, $CitologyDirect);
                if ( $NewCitology[ID] > 0 )
                {
                    $response['result'] = true;
                  	$go_next = false;
                } else
                {
                    $response['msg'] = $NewCitology;
                }
            } else $response['msg'] = 'Вы не выбрали, выявлено ли что-то в результате или нет';
        } else $go_next = true;
      
      	if ( $go_next )
        {
          	$biomark = $_POST['biomark'];
            $biomark_arr = explode("\n", $biomark);

            $response['debug']['$biomark_arr'] = $biomark_arr;
            if ( count($biomark_arr) > 0 )
            {
              $json_string = json_encode($biomark_arr, JSON_UNESCAPED_UNICODE);
            }

            $CitologyDirect = array(
              'citology_journal_id'   =>  $PatientData['journal_id'],
              'citology_doctor_id'    =>  $PatientData['journal_doctor'],
              'citology_ds_mkb'   =>  $PatientData['journal_ds'],
              'citology_ds_text'  =>  $PatientData['journal_ds_text'],
              'citology_dir_date_unix'    =>  time(),
              'citology_action_date'    =>  date("d.m.Y", time()),
              'citology_dir_doctor_id'    =>  $PatientData['journal_doctor'],
              'citology_patid'    =>  $patid,
              'citology_analise_type' => $citotype,
              'citology_analise_markers'    =>  $json_string,
              'citology_cancer'    =>  $cancer,
              'citology_result_text'    =>  $result,
              'citology_result_id'    =>  $result_ident,
              'patidcard_patient_done'    =>  $result_date,
            );

            $response['debug']['$CitologyDirect'] = $CitologyDirect;

            $NewCitology = appendData(CAOP_CITOLOGY, $CitologyDirect);
            if ( $NewCitology[ID] > 0 )
            {
              $response['result'] = true;
            } else
            {
              $response['msg'] = $NewCitology;
            }
        }
      
		
	} else $response['msg'] = 'Не выбран тип цитологии';

	

} else
{
	$response['msg'] = 'Такого пациента не существует';
}