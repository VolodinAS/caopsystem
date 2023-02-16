<?php
$response['stage'] = $action;

$Today_Array = getarr('caop_days', "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");

if ( count($Today_Array) == 1 )
{
    $Today_Array = $Today_Array[0];
    $response['debug']['$Today_Array'] = $Today_Array;

    $mias_data_ssilka = explode('Ссылка', $_POST['miasText']);
//	$response['response'][] = $mias_data_ssilka; // 1
//	$mias_data_no_n = rtrim($mias_data_ssilka[1]);
    $mias_data_n = explode("\n", $mias_data_ssilka[1]);
    $Patients = array();
    for ($i=0; $i<count($mias_data_n); $i++)
    {
        $str = $mias_data_n[$i];

        if ( strpos($str, 'История') !== FALSE )
        {

            $time = trim($mias_data_n[$i-1]);
            $namedate = $mias_data_n[$i+1];
            $namedate_data = explode("\t", $namedate); // 1
            $response['debug']['namedate_data'] = $namedate_data;

            $birth = trim($namedate_data[1]);
            $name_data = explode(" ", $namedate_data[0]); // 0, 1

            $pat_f = trim($name_data[0]);
            $pat_io_data = explode(".", $name_data[1]);
            $pat_name = $pat_f . ' ' . $pat_io_data[0] . ' ' . $pat_io_data[1];
//            $response['debug']['wqweqwe']['$pat_name'] = $pat_name;
            $patarr = array(
                '$time'=>trim($time),
                'dn'=>$namedate,
                '$birth'=>$birth,
                '$name_data'=>$name_data,
                '$pat_name'=>$pat_name,
                '$namedate_data'=>$namedate_data
            );

            list($hours, $mins, $secs) = explode(':', $time); //преобразовываем в секунды
            $_seconds = ($hours * 3600 ) + ($mins * 60 ) + $secs;

            $response['debug'][date("d.m.Y H:i:s")] = $namedate_data;

            $response['DEBUG'] = 'HERE4';

            if ( ifound($namedate_data[3], "казать") || ifound($namedate_data[3], "едакти") )
            {
//                $Patient = array(
//                    'journal_patient_name' => $pat_name,
//                    'journal_patient_birth' => $birth,
//                    'journal_day' => $Today_Array['day_id'],
//                    'journal_time' => $time,
//                    'journal_order' => $_seconds,
//                    'journal_doctor' => $USER_PROFILE['doctor_id'],
//                );

                $pat_name = mb_strtolower($pat_name, UTF8);
                $pat_name = str_replace('.', '', $pat_name);
                $pat_name = nodoublespaces($pat_name);

                $PatientName = name_for_query($pat_name);

                $patid_ident = trim($namedate_data[2]);
                $response['DEBUG_patid_ident'] = $patid_ident;

                $querySearch = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_birth='{$birth}' AND patid_name LIKE '{$PatientName['querySearchPercent']}'";
                $resultSearch = mqc($querySearch);
                $amountSearch = mnr($resultSearch);
                if ( $amountSearch == 1 )
                {
                    $PatidData = mfa($resultSearch);
                    $NEW_ID = $PatidData['patid_id'];
                    $go_next = true;
                } else
                {
                    if ( $amountSearch == 0 )
                    {


                        $NewPatid = array(
                            'patid_name'    =>  NAME_MORMALIZER($PatientName['patientClearName']),
                            'patid_birth'   =>  $birth,
                            'patid_birth_unix'   =>  birthToUnix($birth),
                            'patid_ident'   =>  $patid_ident
                        );

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

                $response['DEBUG'] = 'HERE2';

                if ( $go_next )
                {
	                $DayData = getarr(CAOP_DAYS, "day_id='{$Today_Array['day_id']}'");
	                $DayData = $DayData[0];
                	
                    $newJournal = array(
                        'journal_day'   =>  $Today_Array['day_id'],
                        'journal_doctor'    =>  $USER_PROFILE['doctor_id'],
                        'journal_patid'    =>  $NEW_ID,
//		 'journal_patient_name'    =>  $pat_name,
//		 'journal_patient_ident'    =>  $cardid,
                        'journal_time'    =>  $time,
                        'journal_order'    =>  $_seconds,
	                    'journal_unix'  =>  $DayData['day_unix']
//		 'journal_patient_phone'    =>  $phone,
//		 'journal_patient_address'    =>  $address,
//		 'journal_patient_birth'    =>  $birth
                    );
                    $Append2 = appendData(CAOP_JOURNAL, $newJournal);
                    if ( $Append2[ID] > 0 )
                    {
                        $response['DEBUG'] = 'HERE1';
                        $response['result'] = true;
                    } else
                    {
                        $response['msg'] = $Append2;
                    }
                }

                /*

                $ifNoExistsPatient = array(
                    'index' => 'journal_id',
                    'query' => "journal_day='{$Today_Array['day_id']}' AND journal_patient_name='{$pat_name}' AND journal_doctor='{$USER_PROFILE['doctor_id']}'"
                );
                $AppendDataPatient = appendData(CAOP_JOURNAL, $Patient, $ifNoExistsPatient);*/

            }

//		    $response['pataras'][] = $patarr;
        }
    }
//	$response['response'][] = $mias_data_n;
//    $response['$Patients'] = $Patients;
//    $response['result'] = true;
} else
{
    $response['msg'] = 'Дня приёма не существует';
}




//	$response['response']['$Today'] = $Today;
//	$response['response']['$Today[day_id]'] = $Today['day_id'];
//	$response['response']['$FieldsF'] = $FieldsF;

//	$mias_data = explode("\n", );