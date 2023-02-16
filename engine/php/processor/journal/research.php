<?php
$response['stage'] = $action;

$patient_id = $_POST['patient'];

if ( $patient_id > 0 )
{

	$PatientJournal = getarr('caop_journal', "journal_id='{$patient_id}'");
	if ( count($PatientJournal) == 1 )
	{
		$Patient = $PatientJournal[0];

        $PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$Patient['journal_patid']}'");

        if ( count($PatientPersonalData) == 1 ) {

            $PatientPersonalData = $PatientPersonalData[0];

            $newResearch = array(
                'research_patid' => $PatientPersonalData['patid_id'],
                'research_patient_id'   =>  $Patient['journal_id'],
                'research_unix' =>  time(),
                'research_ds'   =>  $Patient['journal_ds'],
                'research_ds_text'  =>  $Patient['journal_ds_text'],
                'research_doctor_id'  =>  $Patient['journal_doctor']
            );

            $Append = appendData('caop_research', $newResearch);

            if ( $Append[ID] > 0 )
            {
                $response['result'] = true;
                $response['msg'] = 'Пациент успешно добавлен в список на обследование';
            } else
            {
                $response['msg'] = $Append;
            }

        } else
        {
            $response['msg'] = 'Пациента в базе нет';
        }

		/*$newResearch = array(
			'research_patient_id'   =>  $Patient['journal_id'],
			'research_patient_name' =>  $Patient['journal_patient_name'],
			'research_patient_birth'    =>  $Patient['journal_patient_birth'],
			'research_phone'    =>  $Patient['journal_patient_phone'],
			'research_doctor_id'    =>  $USER_PROFILE['doctor_id'],
			'research_ds'    =>  $Patient['journal_ds'],
		    'research_unix' =>  time()
		);*/



	} else
	{
		$response['msg'] = 'Пациента с таким ID не существует';
	}

} else
{
	$response['msg'] = 'Не указан ID пациента';
}