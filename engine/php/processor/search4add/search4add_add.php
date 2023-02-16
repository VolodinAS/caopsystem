<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( strlen( $journal_time ) > 0 )
{

	if ( strlen( $patient_name ) > 0 )
	{
	
		if ( strlen( $patient_birth ) > 0 )
		{
		
			if ( strlen( $patient_ident ) )
			{
				
				$CheckIdent = getarr(CAOP_PATIENTS, "patid_ident='{$patient_ident}'");
				if ( count($CheckIdent) > 0 )
				{
					$CheckIdent = $CheckIdent[0];
					$response['msg'] = 'Пациент с таким номером карты УЖЕ СУЩЕСТВУЕТ:'.n(2).'['.$CheckIdent['patid_ident'].'] '.$CheckIdent['patid_name'] . ', ' . $CheckIdent['patid_birth'] . ' г.р.';
					$response['debug']['$CheckIdent'] = $CheckIdent;
					$response['ident'] = $CheckIdent['patid_ident'];
				} else
				{
					$patient_name = mb_strtolower($patient_name, UTF8);
					$patient_name = str_replace('.', '', $patient_name);
					$patient_name = nodoublespaces($patient_name);
					$patient_name = NAME_MORMALIZER($patient_name);
					
					$paramValues = array(
						'patid_name' => $patient_name,
						'patid_birth'    =>  $patient_birth,
						'patid_birth_unix'    =>  birthToUnix($patient_birth),
						'patid_ident'    =>  $patient_ident
					);
					$NewPatient = appendData(CAOP_PATIENTS, $paramValues);
					if ( $NewPatient[ID] > 0 )
					{
						$PATIENT_ID = $NewPatient[ID];
						$Today_Array = getarr(CAOP_DAYS, "day_unix='{$CURRENT_DAY['unix']}' AND day_doctor='{$USER_PROFILE['doctor_id']}'");
						if ( count($Today_Array) == 1 )
						{
							$Today_Array = $Today_Array[0];
							$paramValues_journal = array(
								'journal_day'   =>  $Today_Array['day_id'],
								'journal_doctor'    =>  $USER_PROFILE['doctor_id'],
								'journal_patid'    =>  $PATIENT_ID,
								'journal_time'    =>  $journal_time,
								'journal_unix'  =>  $CURRENT_DAY['unix'],
								'journal_visit_type'  =>  1
							);
							$NewJournalVisit = appendData(CAOP_JOURNAL, $paramValues_journal);
							if ( $NewJournalVisit[ID] > 0 )
							{
								
								LoggerGlobal(
									LOG_TYPE_CREATE_PATIENT,
									$_SERVER['REMOTE_ADDR'],
									$CAT_DATA['cat_id'],
									$USER_PROFILE['doctor_id'],
									'через дневник',
									$PATIENT_ID
								);
								
								$response['result'] = true;
								$response['msg'] = "Пациент успешно добавлен!\n\nВизит добавлен в приём!";
							} else
							{
								$response['msg'] = 'Проблема с добавлением визита';
								$response['debug']['$paramValues_journal'] = $paramValues_journal;
								$response['debug']['$NewJournalVisit'] = $NewJournalVisit;
							}
							
						} else
						{
							$response['msg'] = 'Вы еще не создали день для добавления визита';
							$response['debug']['$Today_Array'] = $Today_Array;
						}
						
					} else
					{
						$response['msg'] = 'Проблема с добавлением пациента в базу';
						$response['debug']['$paramValues'] = $paramValues;
						$response['debug']['$NewPatient'] = $NewPatient;
					}
				}
				
			} else $response['msg'] = 'Вы не ввели НОМЕР КАРТЫ пациента';
		
		} else $response['msg'] = 'Вы не ввели ДАТУ РОЖДЕНИЯ пациента';
	
	} else $response['msg'] = 'Вы не ввели ФИО пациента';

} else $response['msg'] = 'Вы не ввели ВРЕМЯ ПРИЁМА';