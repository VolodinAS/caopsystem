<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( strlen($patient_fio) > 0 )
{
	if ( strlen($patient_birth) > 0 )
	{
		if ( strlen($patient_ident) > 0 )
		{
			if ( strlen($patient_address) > 0 )
			{
				
				if ( strlen($patient_id)>0 )
				{
					$response['msg'] = 'editor';
					$HTTP['patient_birth_unix'] = birthToUnix($HTTP['patient_birth']);
					unset($HTTP['"patient_id"']);
					
					if ( strlen( $HTTP['patient_weight'] ) > 0 )
					{
						$HTTP['patient_weight'] = ClearIt($HTTP['patient_weight'], '.');
						$HTTP['patient_weight'] = ClearIt($HTTP['patient_weight'], ',');
						$HTTP['patient_weight'] = str_replace(',', '.', $HTTP['patient_weight']);
					}

					$UpdatePatient = updateData(CAOP_DS_PATIENTS, $HTTP, $HTTP, "patient_id='{$patient_id}'");
					if ( $UpdatePatient['stat'] == RES_SUCCESS )
					{
						$response['patient_id'] = $UpdatePatient['patient_id'];
						$response['result'] = true;
					}
					
				} else
				{
					$GetPatientByNameAndBirth = getarr(CAOP_DS_PATIENTS, "patient_fio LIKE '%{$patient_fio}%' AND patient_birth LIKE '%{$patient_birth}%'");
					if ( count($GetPatientByNameAndBirth) == 0 )
					{
						$HTTP['patient_birth_unix'] = birthToUnix($HTTP['patient_birth']);
						$InsertPatient = appendData(CAOP_DS_PATIENTS, $HTTP);
						if ( $InsertPatient[ID] > 0 )
						{
							$response['patient_id'] = $InsertPatient[ID];
							$response['result'] = true;
						}
						
					} else
					{
						$response['msg'] = 'Пациент с такими Ф.И.О. и датой рождения уже есть в базе! Выполяняю переход';
						$response['patient_id'] = $GetPatientByNameAndBirth[0]['patient_id'];
					}
				}
				
				
			} else $response['msg'] = 'Не заполнен номер карты';
		} else $response['msg'] = 'Не заполнен номер карты';
	} else $response['msg'] = 'Не заполнена дата рождения';
} else $response['msg'] = 'Не заполнены Ф.И.О. пациента';