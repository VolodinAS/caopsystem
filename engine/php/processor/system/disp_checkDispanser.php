<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['msg'] = 'Выполняется проверка заполненных данных...';

//$GetJournal = getarr(CAOP_JOURNAL, "journal_id='{$journal_id}'");

$GetJournal_query = "
SELECT * FROM ".CAOP_JOURNAL." journal
LEFT JOIN ".CAOP_DAYS." days ON days.".$PK[CAOP_DAYS]."=journal.journal_day
WHERE ".$PK[CAOP_JOURNAL]."='{$journal_id}'
";
$GetJournal_result = mqc($GetJournal_query);
$GetJournal = mr2a($GetJournal_result);

if ( count($GetJournal) > 0 )
{
    $Journal = $GetJournal[0];
    
    if ( $Journal['journal_visit_type'] == 1 )
    {
	    $response['debug']['$Journal'] = $Journal;
	    $response['debug']['$MKBDispLinear'] = $MKBDispLinear;
	    $response['debug']['checks'][] = '1. Приём существует';
	    // 1 - проверяем дневник
	    if ( strlen($Journal['journal_ds']) > 0 )
	    {
		    $response['debug']['checks'][] = '2. Диагноз указан';
		    if ( strlen($Journal['journal_ds_text']) > 0 )
		    {
			    $response['debug']['checks'][] = '3. Текст диагноза указан';
			    if ( strlen($Journal['journal_recom']) > 0 )
			    {
				    $response['debug']['checks'][] = '4. Исход указан';
				    if ( strlen($Journal['journal_ds_recom']) > 0 )
				    {
					    $response['debug']['checks'][] = '5. Рекомендации указаны';
					    if ( $Journal['journal_infirst'] > 0 )
					    {
						    $response['debug']['checks'][] = '6. Случай указан';
						    if ( strlen($Journal['journal_cardplace']) > 0 )
						    {
							    $response['debug']['checks'][] = '7. Место указано';
							    // 2 - проверяем прикрепление
							    if ( $Journal['journal_disp_lpu'] > 0 )
							    {
								    $response['debug']['checks'][] = '8. ЛПУ указано';
								    // 3 - проверяем направление в другое ЛПУ (-1 - это вообще ничего)
								    $go_next = false;
								    if ( $Journal['journal_dirstac'] != -1 )
								    {
									
									    if ( $Journal['journal_dirstac'] == 0 )
									    {
										    $go_next = true;
									    } else
									    {
										    if ( $Journal['journal_dirstac'] == 6 )
										    {
											    if ( strlen($Journal['journal_dirstac_desc']) > 0 )
											    {
												    $go_next = true;
											    } else
											    {
												    $response['msg'] = 'ОШИБКА:'.n(2).'Вы указали, что направили в ДРУГОЕ учреждение, но не указали, в какое!';
												    $response['focus'] = 'journal_dirstac_desc';
												    $response['tab'] = 'dir-lpu';
											    }
										    } else $go_next = true;
									    }
								    } else
								    {
									    $response['msg'] = 'ОШИБКА:'.n(2).'Не указано, направили ли Вы куда-то пациента после приёма или нет!';
									    $response['focus'] = 'dirstac_none';
									    $response['tab'] = 'dir-lpu';
								    }
								
								    if ( $go_next )
								    {
									    $response['debug']['checks'][] = '9. Направление указано';
									    // 4 - проверяем соответствие диагноза и выставленного случая
									    $is_cancer = DiagnosisCancer($Journal['journal_ds']);
									
									    $go_next = false;
									    if ( $Journal['journal_infirst'] == 1 || $Journal['journal_infirst'] == 2 )
									    {
										    if ( $is_cancer )
										    {
											    $response['msg'] = 'ОШИБКА:'.n(2).'Выбранный тип случая не соответствует злокачественному диагнозу!'.n(2).'Введите доброкачественный диагноз или выберите другой тип случая!';
											    $response['focus'] = 'journal_infirst';
											    $response['tab'] = 'daily';
										    } else $go_next = true;
									    } else
									    {
										    if ( !$is_cancer )
										    {
											    $response['msg'] = 'ОШИБКА:'.n(2).'Выбранный тип случая должен соответствовать злокачественному диагнозу!'.n(2).'Введите злокачественный диагноз или выберите другой тип случая!';
											    $response['focus'] = 'journal_infirst';
											    $response['tab'] = 'daily';
										    } else $go_next = true;
									    }
									
									    if ( $go_next )
									    {
										    $response['debug']['checks'][] = '10. Диагноз и случай соответствуют';
										    // 5 - проверяем обозначение СПО
										    if ( $Journal['journal_spo_id'] > 0 )
										    {
											    $CheckSPO = getarr(CAOP_SPO, $PK[CAOP_SPO] . "='{$Journal['journal_spo_id']}'");
											    if ( count($CheckSPO) == 1 )
											    {
												    $response['debug']['checks'][] = '11. СПО существует';
												    $CheckSPO = $CheckSPO[0];
												
												    $go_next = false;
												
												    // 6 - проверяем сроки СПО
												    if ( $CheckSPO['spo_is_dispancer'] )
												    {
													    $go_next = true;
												    } else
												    {
													    if ( $CheckSPO['spo_end_date_unix'] > 0 )
														    $unix_expired = $CheckSPO['spo_end_date_unix'];
													    else
														    $unix_expired = strtotime("+3 month", $CheckSPO['spo_start_date_unix']);
													
													    if ( $Journal['day_unix'] > $unix_expired )
													    {
														    $response['msg'] = 'ОШИБКА:'.n(2).'У СПО закончился 3хмесячный срок!'.n(2).'Создайте новый!';
														    $response['focus'] = 'addNewSPO';
														    $response['tab'] = 'SPO';
													    } else $go_next = true;
												    }
												
												    if ( $go_next )
												    {
													    $response['debug']['checks'][] = '12. Сроки СПО адекватные';
													
													    if ( $Journal['journal_spo_end_reason_type'] > 0 )
													    {
														    $response['debug']['checks'][] = '13. Причина СПО указана';
														    if ( strlen($CheckSPO['spo_mkb_directed']) > 0 )
														    {
															    $response['debug']['checks'][] = '14. Направительный диагноз указан';
															    if ( strlen($CheckSPO['spo_mkb_finished']) > 0 )
															    {
																    $response['debug']['checks'][] = '15. Закрывающий диагноз указан';
																    if ( strlen($CheckSPO['spo_start_date_unix']) > 0 )
																    {
																	    $response['debug']['checks'][] = '16. Дата открытия СПО указано';
																	    if ( $CheckSPO['spo_start_doctor_id'] > 0 )
																	    {
																		    $response['debug']['checks'][] = '17. Врач, открывший СПО, указан';
																		    // 7 - проверяем соответствие диагноза его диспансерности
																		    $go_next = false;
																		    $is_dispancer = CheckMKBDispancer($CheckSPO['spo_mkb_finished'], $MKBDispLinear)['result'];
																		    if ( $is_dispancer )
																		    {
																			    if ( $CheckSPO['spo_is_dispancer'] == 1 )
																			    {
																				    $go_next = true;
																			    } else
																			    {
																				
																				    //   /*
																				    $is_cancer = DiagnosisCancer($CheckSPO['spo_mkb_finished']);
																				    if ( $is_cancer )
																				    {
																					    $go_next = true;
																				    }  else
																				    {
																					    $response['msg'] = 'ОШИБКА:'.n(2).'У Вас указан ДИСПАНСЕРНЫЙ ДИАГНОЗ.'.n(2).'Отметьте, что случай является ДИСПАНСЕРНЫМ или УСТАНОВИТЕ ЗНО!';
																					    $response['focus'] = 'spo_is_dispancer';
																					    $response['tab'] = 'SPO';
																				    }
																				    //   */
																				
																				    /*
																					$response['msg'] = 'ОШИБКА:'.n(2).'У Вас указан ДИСПАНСЕРНЫЙ ДИАГНОЗ.'.n(2).'Отметьте, что случай является ДИСПАНСЕРНЫМ или УСТАНОВИТЕ ЗНО!';
																					$response['focus'] = 'spo_is_dispancer';
																					$response['tab'] = 'SPO';
																					*/
																				
																				
																			    }
																		    } else
																		    {
																			    if ( $CheckSPO['spo_is_dispancer'] == 0 )
																			    {
																				    $go_next = true;
																			    } else
																			    {
																				    //   /*
																				    $is_cancer = DiagnosisCancer($CheckSPO['spo_mkb_finished']);
																				    if ( $is_cancer )
																				    {
																					    $go_next = true;
																				    }  else
																				    {
																					    $response['msg'] = 'ОШИБКА:'.n(2).'Вы указали ОБЫЧНЫЙ диагноз, но отметили, что случай является ДИСПАНСЕРНЫМ!'.n(2).'Исправьте это!';
																					    $response['focus'] = 'spo_is_dispancer';
																					    $response['tab'] = 'SPO';
																				    }
																				    //   */
																				    /*
																					$response['msg'] = 'ОШИБКА:'.n(2).'Вы указали ОБЫЧНЫЙ диагноз, но отметили, что случай является ДИСПАНСЕРНЫМ!'.n(2).'Исправьте это!';
																					$response['focus'] = 'spo_is_dispancer';
																					$response['tab'] = 'SPO';
																					*/
																			    }
																		    }
																		
																		    if ( $go_next )
																		    {
																			    $response['debug']['checks'][] = '18. Диагноз соответствует диспансеризации';
																			    $go_next = false;
																			    if ( $Journal['journal_spo_end_reason_type'] == '2' )
																			    {
																				    // если случай закончен
																				    if ( $CheckSPO['spo_mkb_directed'] != $CheckSPO['spo_mkb_finished'] )
																				    {
																					    $go_next = true;
																				    } else
																				    {
																					    $response['msg'] = 'ОШИБКА:'.n(2).'Если случай ЗАКОНЧЕН, то НАПРАВИТЕЛЬНЫЙ диагноз НЕ МОЖЕТ БЫТЬ РАВЕН ДИАГНОЗУ ПОСЛЕ ВИЗИТА!'.n(2).'ЕСЛИ ПО ЗАВЕРШЕНИЮ ДИАГНОСТИКИ ДИАГНОЗ НЕ ПОМЕНЯЛСЯ, ЗНАЧИТ, ЛИБО ОКОНЧАНИЕ ПОСЕЩЕНИЯ ИЗМЕНИТЕ НА "ДИНАНИЧЕСКОЕ НАБЛЮДЕНИЕ" ИЛИ ПОСТАВЬТЕ ДОБРОКАЧЕСТВЕННЫЙ ДИАГНОЗ!';
																					    $response['focus'] = 'spo_mkb_finished';
																					    $response['tab'] = 'SPO';
																				    }
																			    } else $go_next = true;
																		    }
																		
																		    if ( $go_next )
																		    {
																			    $response['debug']['checks'][] = '19. Случай закончен правильно';
																			    $go_next = false;
																			    if ( $is_dispancer || $is_cancer )
																			    {
																				    if ( $CheckSPO['spo_unix_accounting_set'] > 0 )
																				    {
																					    $go_next = true;
																				    } else
																				    {
																					    $response['msg'] = 'ОШИБКА:'.n(2).'Вы указали диспансерный случай (или диагноз ЗНО), но не поставили дату установки ЗНО!'.n(2).'Установите сегодняшнюю дату постановки на диспансерный учет (или нажмите "Д-учет" рядом с основным диагнозом)';
																					    $response['focus'] = 'spo_unix_accounting_set';
																					    $response['tab'] = 'SPO';
																				    }
																			    } else $go_next = true;
																		    }
																		
																		    if ( $go_next )
																		    {
																			    $response['debug']['checks'][] = '20. Пациент поставлен на Д-учет';
																			    $go_next = false;
																			
																			    if ( $CheckSPO['spo_accounting_unset_reason'] > 0 )
																			    {
																				    $response['debug']['checks'][] = '21.1 - указана причина';
																				    if ( $CheckSPO['spo_unix_accounting_unset'] != 0 )
																				    {
																					    $response['debug']['checks'][] = '21.2 - указано время';
																					    $go_next = true;
																				    } else
																				    {
																					    $response['debug']['checks'][] = '21.3 - время не указано';
																					    $response['msg'] = 'ОШИБКА:'.n(2).'Вы указали причину снятия с Д-учета, но не указали дату!';
																					    $response['focus'] = 'spo_unix_accounting_unset';
																					    $response['tab'] = 'SPO';
																				    }
																			    } else
																			    {
																				    $response['debug']['checks'][] = '21.4 - причина не указана';
																				    if ( $CheckSPO['spo_unix_accounting_unset'] != 0 )
																				    {
																					    $response['debug']['checks'][] = '21.5 - указано время';
																					    $response['msg'] = 'ОШИБКА:'.n(2).'Вы указали дату снятия с Д-учета, но не указали причину!';
																					    $response['focus'] = 'spo_accounting_unset_reason';
																					    $response['tab'] = 'SPO';
																				    } else
																				    {
																					    $response['debug']['checks'][] = '21.6 - время не указано';
																					    $go_next = true;
																				    }
																			    }
																		    }
																		
																		    if ( $go_next )
																		    {
																			    // TODO ПРОДОЛЖАТЬ ТУТ
																			    $response['debug']['checks'][] = 'СПО оформлено верно';
																			    $response['result'] = true;
																		    }
																		
																	    } else
																	    {
																		    $response['msg'] = 'ОШИБКА:'.n(2).'Выберите врача, открывшего СПО!';
																		    $response['focus'] = 'spo_mkb_finished';
																		    $response['tab'] = 'SPO';
																	    }
																    } else
																    {
																	    $response['msg'] = 'ОШИБКА:'.n(2).'Необходимо заполнить дату открытия СПО!';
																	    $response['focus'] = 'spo_mkb_finished';
																	    $response['tab'] = 'SPO';
																    }
															    } else
															    {
																    $response['msg'] = 'ОШИБКА:'.n(2).'Необходимо заполнить диагноз после посещения!';
																    $response['focus'] = 'spo_mkb_finished';
																    $response['tab'] = 'SPO';
															    }
														    } else
														    {
															    $response['msg'] = 'ОШИБКА:'.n(2).'Необходимо заполнить направительный диагноз!';
															    $response['focus'] = 'spo_mkb_directed';
															    $response['tab'] = 'SPO';
														    }
														
													    } else
													    {
														    $response['msg'] = 'ОШИБКА:'.n(2).'Выберите, чем на данное посещение закончился случай!';
														    $response['focus'] = 'spo_end_reason_type';
														    $response['tab'] = 'SPO';
													    }
													
													
												    }
												
											    } else
											    {
												    $response['msg'] = 'ОШИБКА:'.n(2).'Указан несуществующий СПО!'.n(2).'Создайте новый!';
												    $response['focus'] = 'addNewSPO';
												    $response['tab'] = 'SPO';
											    }
										    } else
										    {
											    $response['msg'] = 'ОШИБКА:'.n(2).'Не выбрано СПО для данного случая!';
											    $response['focus'] = 'addNewSPO';
											    $response['tab'] = 'SPO';
										    }
									    }
									
								    }
								
							    } else
							    {
								    $response['msg'] = 'ОШИБКА:'.n(2).'Не указано ЛПУ прикрепления пациента!';
								    $response['focus'] = 'journal_disp_lpu';
								    $response['tab'] = 'lpu-pin';
							    }
							
							
						    } else
						    {
							    $response['msg'] = 'ОШИБКА:'.n(2).'Не указано место карты!';
							    $response['focus'] = 'journal_cardplace';
							    $response['tab'] = 'daily';
						    }
					    } else
					    {
						    $response['msg'] = 'ОШИБКА:'.n(2).'Не выбран тип случая (в желтой рамке)!';
						    $response['focus'] = 'journal_infirst';
						    $response['tab'] = 'daily';
					    }
				    } else
				    {
					    $response['msg'] = 'ОШИБКА:'.n(2).'Не указаны рекомендации (для справки)!';
					    $response['focus'] = 'journal_ds_recom';
					    $response['tab'] = 'daily';
				    }
			    } else
			    {
				    $response['msg'] = 'ОШИБКА:'.n(2).'Не указан исход случая!';
				    $response['focus'] = 'journal_recom';
				    $response['tab'] = 'daily';
			    }
		    } else
		    {
			    $response['msg'] = 'ОШИБКА:'.n(2).'Не указан текст основного диагноза!';
			    $response['focus'] = 'journal_ds_text';
			    $response['tab'] = 'daily';
		    }
	    } else
	    {
		    $response['msg'] = 'ОШИБКА:'.n(2).'Не указан основной диагноз!';
		    $response['focus'] = 'journal_ds';
		    $response['tab'] = 'daily';
	    }
    } else
    {
    	$response['result'] = true;
    }
    
//	$response['result'] = true;
//	$response['isDisp'] = $Journal['journal_disp_isDisp'];
//	$response['isSelf'] = $Journal['journal_disp_self'];
//	$response['dirStac'] = $Journal['journal_dirstac'];
//	$response['dirStacDesc'] = $Journal['journal_dirstac_desc'];
//	$response['dirInfirst'] = $Journal['journal_infirst'];
//	$response['SPO'] = $Journal['journal_spo_id'];
	$response['dirZNO'] = $Journal['journal_ds'][0];
} else $response['msg'] = 'Такой записи в журнале не найдено';