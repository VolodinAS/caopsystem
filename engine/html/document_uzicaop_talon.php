<?php
// debug($_SESSION);

$HTTP = $_SESSION[$PrintParams[0]];
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$DoctorsListUzi = getarr(CAOP_DOCTOR, "doctor_isUzi='1'");
$DoctorsListUziId = getDoctorsById($DoctorsListUzi);

if ( strlen($patuzi_id) > 0 )
{
	$PatUziData = getarr(CAOP_SCHEDULE_UZI_PATIENTS, "patient_time_id='{$patuzi_id}' AND patient_date_id='{$date_id}'");
	if ( count($PatUziData) == 1 )
	{
		$PatUziData = $PatUziData[0];
		
		$PatientData = getarr(CAOP_PATIENTS, "patid_id='{$PatUziData['patient_pat_id']}'");
		
		if ( count($PatientData) == 1 )
		{
			$PatientData = $PatientData[0];

// 		debug($PatientData);
			
			if ($PatUziData['patient_prescription_doctor_id'] > 0)
			{
				$DoctorData = $DoctorsListId['id' . $PatUziData['patient_prescription_doctor_id']];
//			debug($DoctorData);
				$DOCTOR_NAME = docFtoFIO($DoctorsListId, 1, $PatUziData['patient_prescription_doctor_id']);
//			debug($DoctorsArr);
			} else
			{
				$DOCTOR_NAME = 'не указано';
			}

//		debug($PatUziData);
//		debug($DoctorsListUziId);
			
			$DoctorUZI = $DoctorsListUziId['id' . $PatUziData['patient_doctor_id']];
			$DOCTOR_UZI_NAME = docFtoFIO($DoctorsListUziId, 1, $PatUziData['patient_doctor_id']);

// 		debug($DoctorUZI);
			
			$age = ageByBirth($PatientData['patid_birth']);
			$age_end = wordEnd($age, 'год', 'года', 'лет');
			
			$AreaData = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "area_id='{$PatUziData['patient_area_id']}'");
			if ( count($AreaData) == 1 )
			{
				$AreaData = $AreaData[0];
				
				//  debug($AreaData);
				$description = "";
				$description = ( strlen($PatUziData['patient_area_description']) > 0 ) ? ' ('.$PatUziData['patient_area_description'].')' : '';
				
				$DateData = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='{$PatUziData['patient_date_id']}'");
				if ( count($DateData) == 1 )
				{
					$DateData = $DateData[0];
					
					// debug($DateData);
					
					$TimeData = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_id='{$PatUziData['patient_time_id']}'");
					if (count($TimeData) == 1)
					{
						$TimeData = $TimeData[0];
						
						$DS = '';
						if ($PatUziData['patient_journal_id'] > 0)
						{
							$Journal = getarr(CAOP_JOURNAL, "journal_id='{$PatUziData['patient_journal_id']}'");
							if ( count($Journal) > 0 )
							{
								$Journal = $Journal[0];
								
								if ( strlen($Journal['journal_ds']) > 0 && strlen($Journal['journal_ds_text']) > 0 )
								{
									$DS = '
                                    <tr>
                                        <td>
                                            <div class="size-16pt"><b>Диагноз:</b> ['.$Journal['journal_ds'].'] '.$Journal['journal_ds_text'].'</div>
                                        </td>
                                    </tr>
                                ';
								}
							}
						}
						
						$gondon = '';
						if ( $PatUziData['patient_area_id'] == 4 || $PatUziData['patient_area_id'] == 5 )
                        {
                            $gondon = 'Купите в аптеке ПРЕЗЕРВАТИВ ДЛЯ УЗИ!';
                        }
						
						?>
                        <table cellpadding="2" width="100%">
                            <tr>
                                <td align="center">
                                    <div class="gbuz boldy"><?= $LPU_DOCTOR['lpu_blank_name']; ?></div>
                                    <div class="address boldy"><?= $LPU_DOCTOR['lpu_lpu_address']; ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <div class="size-16pt"><b>Талон на УЗИ ЦАОП</b></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <div class="size-16pt"><b>к специалисту</b></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">

                                    <div class="size-16pt">
                                        <b><?=$DoctorUZI['doctor_duty'];?>: <?=$DOCTOR_UZI_NAME;?></b>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <div class="size-20pt"><b><?=$DateData['dates_date']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$TimeData['time_hour']?>:<?=$TimeData['time_min']?></b></div>
                                        </td>
                                        <td>
                                            <div class="size-20pt">
                                                <b>КАБИНЕТ №</b> 121
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </tr>
                            <tr>
                                <td>
                                    <br>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="size-16pt"><b>Пациент:</b> <?=mb_ucwords($PatientData['patid_name']);?>, <?=$PatientData['patid_birth'];?> г.р. (<?=$age;?> <?=$age_end;?>)</div>
                                </td>
                            </tr>
							<?=$DS;?>
                            <tr>
                                <td>
                                    <div class="size-16pt"><b>Исследование:</b> <?=$AreaData['area_title'];?><?=$description;?></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <div class="size-16pt"><b>Направивший врач:</b> <?=$DOCTOR_NAME;?></div>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <div><b>Дата выдачи талона:</b> <?=date(DMYHI, time()+3600);?></div>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <table width="100%">
                            <tr>
                                <td align="center"><b>ВНИМАНИЕ!<br>С собой взять простыню (постелить) и полотенце (вытереться после процедуры)! <?=$gondon;?></b></td>
                            </tr>
                        </table>
                        <hr>
						
						<?php
						
					} else
					{
						echo 'ТАКОГО ВРЕМЕНИ НЕ СУЩЕСТВУЕТ';
					}
					
					
					
				} else
				{
					echo 'ТАКОЙ ДАТЫ ЗАПИСИ НЕ СУЩЕСТВУЕТ';
				}
				
				
				
			} else
			{
				echo 'ТАКОЙ ОБЛАСТИ ИССЛЕДОВАНИЯ НЕ СУЩЕСТВУЕТ';
			}
			
		} else
		{
			echo 'ТАКОГО ТАЛОНА НЕ СУЩЕСТВУЕТ';
		}

// 	debug($PatUziData);
	}
} else
{
    if ( count($uzi_ids) > 0 )
    {
        $PatientData = array();
        
        $DATE_TIMES = '';
        $go_next = false;
        $prev_doctor_id = 0;
	    $gondon = '';
	    foreach ($uzi_ids as $uzi_id)
	    {
		    $PatUziData = getarr(CAOP_SCHEDULE_UZI_PATIENTS, $PK[CAOP_SCHEDULE_UZI_PATIENTS] . "='{$uzi_id}'");
		    if ( count($PatUziData) > 0 )
		    {
			    $PatUziData = $PatUziData[0];
//			    debug($PatUziData);
			    if ($prev_doctor_id == 0)
                {
                    $prev_doctor_id = $PatUziData['patient_doctor_id'];
                    $go_next = true;
                } else
                {
                    if ( $PatUziData['patient_doctor_id'] == $prev_doctor_id )
                    {
                        $go_next = true;
                    } else
                    {
                        $MSG = 'ВЫ НЕ МОЖЕТЕ НА ОДНОЙ БУМАГЕ РАСПЕЧАТАТЬ НЕСКОЛЬКО ТАЛОНОВ К РАЗНЫМ ВРАЧАМ УЗИ!';
                        break;
                    }
                }
			    
			    if ( $go_next )
			    {
				    if ( count($PatientData) == 0 )
                    {
	                    $PatientData = getarr(CAOP_PATIENTS, "patid_id='{$PatUziData['patient_pat_id']}'");
	                    $PatientData = $PatientData[0];
                    }
				        
				    
				
				
				    if ($PatUziData['patient_prescription_doctor_id'] > 0)
				    {
					    $DoctorData = $DoctorsListId['id' . $PatUziData['patient_prescription_doctor_id']];
					    $DOCTOR_NAME = docFtoFIO($DoctorsListId, 1, $PatUziData['patient_prescription_doctor_id']);
				    } else
				    {
					    $DOCTOR_NAME = 'не указано';
				    }
				
				    $DoctorUZI = $DoctorsListUziId['id' . $PatUziData['patient_doctor_id']];
				    $DOCTOR_UZI_NAME = docFtoFIO($DoctorsListUziId, 1, $PatUziData['patient_doctor_id']);
				
				    $age = ageByBirth($PatientData['patid_birth']);
				    $age_end = wordEnd($age, 'год', 'года', 'лет');
				
				    $AreaData = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "area_id='{$PatUziData['patient_area_id']}'");
				    if ( count($AreaData) == 1 )
				    {
					    $AreaData = $AreaData[0];
					
//					    debug($AreaData);
					
					    $description = "";
					    $description = (strlen($PatUziData['patient_area_description']) > 0) ? ' (' . $PatUziData['patient_area_description'] . ')' : '';
					
					    $DateData = getarr(CAOP_SCHEDULE_UZI_DATES, "dates_id='{$PatUziData['patient_date_id']}'");
					    if (count($DateData) == 1)
					    {
						    $DateData = $DateData[0];
						
//						    debug($DateData);
						
						    $TimeData = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_id='{$PatUziData['patient_time_id']}'");
						    if (count($TimeData) == 1)
						    {
							    $TimeData = $TimeData[0];
							
//							    debug($TimeData);
							
							    $DS = '';
							    if ($PatUziData['patient_journal_id'] > 0)
							    {
								    $Journal = getarr(CAOP_JOURNAL, "journal_id='{$PatUziData['patient_journal_id']}'");
								    if (count($Journal) > 0)
								    {
									
									    $Journal = $Journal[0];
									
									    if (strlen($Journal['journal_ds']) > 0 && strlen($Journal['journal_ds_text']) > 0)
									    {
										    $DS = '
                                                    <tr>
                                                        <td>
                                                            <div class="size-16pt"><b>Диагноз:</b> [' . $Journal['journal_ds'] . '] ' . $Journal['journal_ds_text'] . '</div>
                                                        </td>
                                                    </tr>
                                                ';
									    }
								    }
							    }
							    
							    if ( $PatUziData['patient_area_id'] == 4 || $PatUziData['patient_area_id'] == 5 )
							    {
								    $gondon = 'Купите в аптеке ПРЕЗЕРВАТИВ ДЛЯ УЗИ!';
							    }
							
							    $go_next = true;
							    $DATE_TIMES .= '
                                    <tr>
                                        <td>
                                            <div class="size-20pt"><b>'.$DateData['dates_date'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$TimeData['time_hour'].':'.$TimeData['time_min'].'</b></div>
                                        </td>
                                        <td class="size-20pt">
                                            '.$AreaData['area_title'].$description.'
                                        </td>
                                        <td>
                                            <div class="size-20pt">
                                                <b>КАБИНЕТ №</b> 121
                                            </div>
                                        </td>
                                    </tr>
                                    ';
						    } else
						    {
							    $MSG = 'ТАКОГО ВРЕМЕНИ НЕ СУЩЕСТВУЕТ';
						    }
					    } else
					    {
						    $MSG = 'ТАКОЙ ДАТЫ ЗАПИСИ НЕ СУЩЕСТВУЕТ';
					    }
				    } else
				    {
					    $MSG = 'ТАКОЙ ОБЛАСТИ ИССЛЕДОВАНИЯ НЕ СУЩЕСТВУЕТ';
				    }
			    }
			    
//			    echo '<hr>';
//			    echo '<hr>';
		    }
	    }
	    
        if ( $go_next )
        {
//            debug($PatientData);
        ?>
            <table cellpadding="2" width="100%">
                <tr>
                    <td align="center">
                        <div class="gbuz boldy"><?= $LPU_DOCTOR['lpu_blank_name']; ?></div>
                        <div class="address boldy"><?= $LPU_DOCTOR['lpu_lpu_address']; ?></div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div class="size-16pt"><b>Талон на УЗИ ЦАОП</b></div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <div class="size-16pt"><b>к специалисту</b></div>
                    </td>
                </tr>
                <tr>
                    <td align="center">
    
                        <div class="size-16pt">
                            <b><?=$DoctorUZI['doctor_duty'];?>: <?=$DOCTOR_UZI_NAME;?></b>
                        </div>
    
                    </td>
                </tr>
                <tr>
                    <table width="100%">
                        <?=$DATE_TIMES;?>
                    </table>
                </tr>
                <tr>
                    <td>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="size-16pt"><b>Пациент:</b> <?=mb_ucwords($PatientData['patid_name']);?>, <?=$PatientData['patid_birth'];?> г.р. (<?=$age;?> <?=$age_end;?>)</div>
                    </td>
                </tr>
                <?=$DS;?>
                <tr>
                    <td align="right">
                        <div class="size-16pt"><b>Направивший врач:</b> <?=$DOCTOR_NAME;?></div>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <div><b>Дата выдачи талона:</b> <?=date(DMYHI, time()+3600);?></div>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="100%">
                <tr>
                    <td align="center"><b>ВНИМАНИЕ!<br>С собой взять простыню (постелить) и полотенце (вытереться после процедуры)! <?=$gondon;?></b></td>
                </tr>
            </table>
            <hr>
        <?php
        } else bt_notice($MSG,BT_THEME_DANGER);
    }
}

//echo $MSG;