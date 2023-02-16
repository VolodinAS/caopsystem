<?php
$response['stage'] = $action;
$go_journal = false;
$Today_Array = getarr('caop_days', "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");
//debug($Today_Array);
//debug($USER_PROFILE);
$response['debug']['$Today_Array'] = $Today_Array;
if ( count($Today_Array) == 1 )
{
    $go_next = false;
    $go_update = false;
	$Today_Array = $Today_Array[0];

	$pat_name = mb_strtolower($_POST['name'], UTF8);
	$pat_name = str_replace('.', '', $pat_name);
    $pat_name = nodoublespaces($pat_name);

	$birth = $_POST['birth'];
	$cardid = $_POST['cardid'];
	$timer = $_POST['timer'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$ins_number = $_POST['insurance_number'];
	$ins_company = $_POST['insurance_company'];

	$PatientNameQuery = str_replace(" ", "%%", $pat_name);
	$PatientNameQuery = "%{$PatientNameQuery}%";

//    $querySearch = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_birth='{$birth}' AND patid_name LIKE '{$PatientName['querySearchPercent']}'";
    $querySearch = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_birth='{$birth}' AND patid_name LIKE '{$PatientNameQuery}'";
//    $response['debug']['$querySearch'] = $querySearch;
    $resultSearch = mqc($querySearch);
    $amountSearch = mnr($resultSearch);

    $NewPatid = array(
        'patid_ident'   =>  $cardid,
        'patid_name'    =>  NAME_MORMALIZER($pat_name),
        'patid_birth'   =>  $birth,
        'patid_phone'   =>  $phone,
        'patid_address'   =>  $address,
        'patid_birth_unix'   =>  birthToUnix($birth),
        'patid_insurance_number'   =>  $ins_number,
        'patid_insurance_company'   =>  $ins_company,
    );

    if ( $amountSearch == 1 )
    {
        $PatidData = mfa($resultSearch);
        $NEW_ID = $PatidData['patid_id'];
        $go_next = true;
        $go_update = true;
    } else
    {
        if ( $amountSearch == 0 )
        {



            $AppendPatid = appendData(CAOP_PATIENTS, $NewPatid);
            if ( $AppendPatid[ID] > 0 )
            {
                $NEW_ID = $AppendPatid[ID];
                $go_next = true;

            } else
            {
                $response['msg'] = 'Ошибка при добавлении нового пациента в БД';
            }

        } else
        {
            $response['msg'] = 'Слишком много пациентов на одну карту';
        }
    }

    if ( $go_next )
    {

        $UpdateValues = array();
        foreach ($PatidData as $patid_field => $patid_value)
        {
            if ( strlen( $patid_value ) == 0 )
            {
                if ( notnull( $NewPatid[$patid_field] ) )
                {
                    if ( strlen($NewPatid[$patid_field] > 0) )
                    {
                        $UpdateValues[$patid_field] = $NewPatid[$patid_field];
                    }
                }
            }
        }
        if ( count($UpdateValues) > 0 )
        {
            $response['debug']['$UpdateValues'] = $UpdateValues;
            $UpdatePatid = updateData(CAOP_PATIENTS, $UpdateValues, array(), "patid_id='{$PatidData['patid_id']}'");
        }

	    $DayData = getarr(CAOP_DAYS, "day_id='{$Today_Array['day_id']}'");
	    $DayData = $DayData[0];
    	
        $newJournal = array(
		 'journal_day'   =>  $Today_Array['day_id'],
		 'journal_doctor'    =>  $USER_PROFILE['doctor_id'],
		 'journal_patid'    =>  $NEW_ID,
//		 'journal_patient_name'    =>  $pat_name,
//		 'journal_patient_ident'    =>  $cardid,
		 'journal_time'    =>  $timer,
         'journal_unix'  =>  $DayData['day_unix']
//		 'journal_patient_phone'    =>  $phone,
//		 'journal_patient_address'    =>  $address,
//		 'journal_patient_birth'    =>  $birth
	    );
        $Append2 = appendData(CAOP_JOURNAL, $newJournal);
        if ( $Append2[ID] > 0 )
        {
            $response['result'] = true;
        } else
        {
            $response['msg'] = $Append2;
        }
    }

} else
{
	$response['msg'] = 'Вы еще не создали день приёма';
}