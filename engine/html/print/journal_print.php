<?php
$PrintParams = explode('/', $request_params);
if ( count($PrintParams) == 2 )
{
	switch ($PrintParams[0])
	{
		case 'journalday':
		case 'stattalones':
		case 'dispanser':
		case 'dirs':
			$go_next = true;
			break;
		default:
			$go_print_error = true;
			$go_print_msg = 'Невозможно распечатать, так как неопознан тип документа';
			break;
	}

	if ( $go_next )
	{
		$day_id = $PrintParams[1];
		
		$ReportData_query = "
						SELECT * FROM ".CAOP_JOURNAL." journal
						LEFT JOIN ".CAOP_DAYS." days ON days.".$PK[CAOP_DAYS]."=journal.journal_day
						LEFT JOIN ".CAOP_SPO." spo ON spo.".$PK[CAOP_SPO]."=journal.journal_spo_id
						LEFT JOIN ".CAOP_PATIENTS." patients ON patients.".$PK[CAOP_PATIENTS]."=journal.journal_patid
						WHERE journal_day='{$day_id}'
						ORDER BY patients.patid_name ASC
					";
		$ReportData_result = mqc($ReportData_query);
		$Journal = mr2a($ReportData_result);
		
// 		debug($Journal);
		
//		$Journal = getarr('caop_journal', "journal_day='{$day_id}'");
		if ( count($Journal) > 0 )
		{
			$DoctorData = $DoctorsListId['id' . $Journal[0]['day_doctor']];
			$DayData = extractValueByKey($Journal[0], 'day_');

			if ($PrintParams[0] == "dispanser")
			{
				// TODO ОБРАБОТАТЬ ДИСПАНСЕРНЫХ
				$JournalDisp = [];
				
				foreach ($Journal as $reportDatum)
				{
					
					if ( $reportDatum['journal_disp_isReported'] > 0 )
					{
						$JournalDisp[] = $reportDatum;
					} else
					{
						if ( $reportDatum['spo_is_dispancer'] == 1 )
						{
							// случай является диспансерным = злокачественным
//							debug($reportDatum);
//							$JournalDisp[] = $reportDatum;
							
							$is_cancer = DiagnosisCancer($reportDatum['spo_mkb_finished']);
							
//							debug($is_cancer);
							
							$is_dispancer = CheckMKBDispancer($reportDatum['spo_mkb_finished'], $MKBDispLinear);
							
							if ( $is_cancer )
							{
								// установленный рак
								if ( $reportDatum['day_unix'] == $reportDatum['spo_unix_accounting_set'] )
								{
									// ВПЕРВЫЕ, так как дата установки = дате печати, в список НЕ включать
								} else
								{
									$JournalDisp[] = $reportDatum;
								}
							} else
							{
								if ( $is_dispancer )
								{
									// установленный диспансер
									if ( $reportDatum['day_unix'] == $reportDatum['spo_unix_accounting_set'] )
									{
										// ВПЕРВЫЕ, так как дата установки = дате печати, в список ВКЛЮЧИТЬ
										$JournalDisp[] = $reportDatum;
									}
									
								}
							}
							
							
							
						}
					}
				}
			}
			
			require_once ( "engine/html/title_begin_print.php" );
			require_once ( "engine/html/print_".$PrintParams[0].".php" );
//			print_r($PrintParams);
			require_once ( "engine/html/title_end_print.php" );
			
			exit();



		} else
		{
			$go_print_error = true;
			$go_print_msg = 'Невозможно распечатать, так как указанный день отсутствует в списке';
		}
	}
}


