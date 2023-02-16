<?php
$response['stage'] = $action;

$journal_id = $_POST['patvis'];
$patient_id = $_POST['patient_id'];
$restype = $_POST['restype'];
$dateres = $_POST['dateres'];
$areares = $_POST['areares'];
$result = $_POST['result'];
$cancer = $_POST['cancer'];
$is_add = ( $_POST['is_add'] == "true" ) ? true : false;

$response['debug']['is_add'] = $is_add;

if ( $journal_id > 0 )
{

	$PatientJournal = getarr('caop_journal', "journal_id='{$journal_id}'");
	if ( count($PatientJournal) == 1 )
	{
		
		$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
		$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');
		
		$Patient = $PatientJournal[0];
		
		if ( $restype )
		{
			$ResearchData = $ResearchTypesId[ 'id' . $restype ];
			
			$response['debug']['$ResearchData'] = $ResearchData;
			$go_next = false;
          	if ( !$is_add )
            {
              	if ( $cancer )
                {
                    if ( $cancer == 1 )
                    {
                        // рак
                        if ( $ResearchData['type_morph'] == 0 )
                        {
                            // НЕэндоскопический
                            $response['msg'] = 'Вы выбрали метод, который НЕ МОЖЕТ установить РАК.'.n(2).'Выберите ЭНДОСКОПИЧЕСКИЙ метод или уберите отметку о том, что выявлен рак!';
                        } else $go_next = true;
                    } else $go_next = true;

                    if ( $go_next )
                    {
                        $newResearch = array(
                            'research_patid' => $patient_id,
                            'research_patient_id'   =>  $Patient['journal_id'],
                            'research_unix' =>  time(),
                            'research_ds'   =>  $Patient['journal_ds'],
                            'research_ds_text'  =>  $Patient['journal_ds_text'],
                            'research_doctor_id'  =>  $Patient['journal_doctor'],
                            'research_type'  =>  $restype,
                            'patidcard_patient_done'  =>  $dateres,
                            'research_area'	=>	$areares,
                            'research_result' => $result,
                            'research_cancer' => $cancer
                        );

                        if ( strlen($result) > 0 )
                        {
                            $newResearch['research_status'] = 4;
                        }

                        $Append = appendData('caop_research', $newResearch);

                        if ( $Append[ID] > 0 )
                        {
                            $response['result'] = true;
                            $response['msg'] = 'Пациент успешно добавлен в список на обследование';
                          	$go_next = false;
                        } else
                        {
                            $response['msg'] = $Append;
                        }

                    }

                } else $response['msg'] = 'Вы не выбрали, выявлено что-то в результате или нет';
            } else $go_next = true;
          
          	if ( $go_next )
            {
                $newResearch = array(
                  'research_patid' => $patient_id,
                  'research_patient_id'   =>  $Patient['journal_id'],
                  'research_unix' =>  time(),
                  'research_ds'   =>  $Patient['journal_ds'],
                  'research_ds_text'  =>  $Patient['journal_ds_text'],
                  'research_doctor_id'  =>  $Patient['journal_doctor'],
                  'research_type'  =>  $restype,
                  'patidcard_patient_done'  =>  $dateres,
                  'research_area'	=>	$areares,
                  'research_result' => $result,
                  'research_cancer' => $cancer
                );

                if ( strlen($result) > 0 )
                {
                  $newResearch['research_status'] = 4;
                }

                $Append = appendData('caop_research', $newResearch);

                if ( $Append[ID] > 0 )
                {
                  $response['result'] = true;
                  $response['msg'] = 'Пациент успешно добавлен в список на обследование';
                } else
                {
                  $response['msg'] = $Append;
                }

            }
			
		} else $response['msg'] = 'Вы не выбрали обследование';
		
		
		
		/*

		*/

	} else
	{
		$response['msg'] = 'Пациента с таким ID не существует';
	}

} else
{
	$response['msg'] = 'Не указан ID пациента';
}