<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['htmlData'] = '';

if ( strlen( $table ) > 0 )
{

	if ( strlen($field) > 0 )
	{

		if ( strlen($value) > 0 )
		{

			$query_SEARCH = "SELECT * FROM {$table} WHERE {$field} LIKE '{$value}'";
			$result_SEARCH = mqc($query_SEARCH);
			$amount_SEARCH = mnr($result_SEARCH);

			$response['htmlData'] .= bt_notice('Запрос: ' . wrapper($query_SEARCH), BT_THEME_INFO, 1);
			$response['htmlData'] .= bt_notice('Найдено записей: ' . wrapper($amount_SEARCH), BT_THEME_WARNING, 1);

			if ( $amount_SEARCH > 0 )
			{
				$response['result'] = true;
				$JournalData = mr2a($result_SEARCH);

				$WasPatientList = [];

				//include ( "engine/php/patient_search.php" );

				foreach ( $JournalData as $journalItem )
				{
					$PatientData = [];
					if ( strlen($patient_id_field) > 0 )
					{
						$patient_id = $journalItem[$patient_id_field];
						if ( count($WasPatientList['pid_' . $patient_id]) > 0 )
						{
							$PatientData = $WasPatientList['pid_' . $patient_id];
						} else
						{
							$PatientData = getarr(CAOP_PATIENTS, "patid_id='{$patient_id}'");
							$WasPatientList['pid_' . $patient_id] = $PatientData;
						}

					}

					$response['htmlData'] .= '<div class="row">';
					$response['htmlData'] .= '<div class="col-6">';
					$response['htmlData'] .= debug_ret($journalItem);
					$response['htmlData'] .= '</div>';
					$response['htmlData'] .= '[PATIENT_DATA]';
					$response['htmlData'] .= '</div>';

					if ( count($PatientData) > 0 )
					{
						$response['htmlData'] = str_replace( '[PATIENT_DATA]', '<div class="col-6">' . debug_ret($PatientData) . '</div>', $response['htmlData'] );
					} else
					{
						$response['htmlData'] = str_replace( '[PATIENT_DATA]', '', $response['htmlData'] );
					}
				}

			} else
			{
				$response['result'] = true;
				$response['htmlData'] .= bt_notice('Записей по указанным критериям не найдено',BT_THEME_SECONDARY, 1);
			}

		} else $response['msg'] = 'Не указан критерий поиска поля';

	} else $response['msg'] = 'Не указано название столбца';

} else $response['msg'] = 'Не указано название таблицы';