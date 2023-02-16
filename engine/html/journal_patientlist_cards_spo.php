<?php

//debug($DirStacListId);

require_once("engine/html/journal_menu.php");

$queryPatientsToday = "SELECT IF( cj.journal_recom = '', 0, 1 ) AS status, cj . * FROM {$CAOP_JOURNAL} cj INNER JOIN {$CAOP_PATIENTS} cp ON cj.journal_patid=cp.patid_id WHERE cj.journal_day='{$Today_Array['day_id']}' AND cj.journal_doctor='{$CHOSEN_DOCTOR_ID}' ORDER BY status, cj.journal_time, cp.patid_name ASC";

$resultPatientsToday = mqc($queryPatientsToday);
$amountPatientsToday = mnr($resultPatientsToday);
$PatientsToday = mr2a($resultPatientsToday);

$JSON = json_encode($PatientsToday);
$JSON_HASH = md5($JSON);

$CaseStatusesList = getarr(CAOP_CASESTATUS, "casestatus_enabled='1'", "ORDER BY casestatus_order ASC");
$CaseStatusesListId = getDoctorsById($CaseStatusesList, 'casestatus_id');

$DirStacList = getarr(CAOP_DIRSTAC, "dirstac_enabled='1'", "ORDER BY dirstac_order ASC");
$DirStacListId = getDoctorsById($DirStacList, 'dirstac_id');

$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");
$DispLPUId = getDoctorsById($DispLPU, 'lpu_id');

?>
    <script>
        var JSON_HASH = '<?=$JSON_HASH;?>';
        var TODAY_DAYID = '<?=$Today_Array['day_id'];?>';
    </script>
<?php

if (count($PatientsToday) > 0)
{
	?>

    <table class="table tablesorter table-sm"
           id="patient_journal">
        <thead>
        <tr>
            <th scope="col"
                width="1%"
                align="center">
				<?= str_nbsp('#', 5); ?>
            </th>

            <th scope="col"
                width="1%"
                align="center"
                class="sorter-false"><i class="bi bi-pen-fill"></i></th>
            <th scope="col"
                width="1%"
                align="center"
                class="sorter-false"><i class="bi bi-card-heading"></i></th>

            <th scope="col"
                class="sorter-false"
                width="1%"
                align="center"><i class="bi bi-card-heading"></i></th>
            <th scope="col"
                width="1%"
                align="center"><img src="/engine/images/icons/cancer.png"
                                    alt=""></th>
            <th scope="col"
                width="1%"
                align="center">
                <span <?= super_bootstrap_tooltip('Пациент является диспансерным'); ?>><?= BT_ICON_DISPANSER; ?></span>
            </th>
            <th scope="col"
                width="1%"
                align="center">Время
            </th>

            <th scope="col"
                align="center"
                class="align-center sorter-false" <?= super_bootstrap_tooltip('ОБРАТИТЕ ВНИМАНИЕ!'); ?>><?= BT_ICON_CASE_WARNING; ?></th>

            <th scope="col">Ф.И.О.</th>
            <th scope="col"
                width="1%"
                align="center"
                style="align-center">Дата&nbsp;рождения
            </th>
            <th scope="col"
                align="center"
                class="align-center">Диагноз
            </th>

            <th scope="col"
                class="sorter-false"
                width="1%"
                align="center">
                <div <?= super_bootstrap_tooltip('Данного пациента необходимо перенесети на другую дату!'); ?>><?= BT_ICON_REGIMEN_DONE; ?></div>
            </th>

            <th scope="col"
                class="sorter-false"
                width="1%"
                align="center">
				<?= str_nbsp('Действия', 4) ?>
            </th>
        </tr>
        </thead>
        <tbody>
		<?php
		$npp = count($PatientsToday);
		foreach ($PatientsToday as $Patient)
		{
			
			
			$icons_case = '';
			
			$icon_case_personal_ok = true;
			$icon_case_personal_data = array();
			$icon_case_personal = '
		    <span ' . super_bootstrap_tooltip("У ПАЦИЕНТА НЕ ЗАПОЛНЕНЫ НЕКОТОРЫЕ ДАННЫЕ ПРОФИЛЯ!\r\n[PERSONAL_ERROR]") . '>' . BT_ICON_CASE_PROFILE . '</span>
		    ';
			
			$icon_case_journal_ok = true;
			$icon_case_journal_data = array();
			$icon_case_journal = '
		    <span ' . super_bootstrap_tooltip("У ПАЦИЕНТА НЕ ЗАПОЛНЕНЫ НЕКОТОРЫЕ ДАННЫЕ ПРИЁМА!
		    \r\n[JOURNAL_ERROR]") . '>' . BT_ICON_CASE_JOURNAL . '</span>
		    ';
			
			$icon_case_ok = true;
			$icon_case = '
		    <span ' . super_bootstrap_tooltip('Всё отлично!') . '>' . BT_ICON_CASE_ALLGOOD . '</span>
		    ';
			
			$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$Patient['journal_patid']}'");

//            debug($PatientPersonalData);
			
			$light_bg = ($light_id == $Patient['journal_id']) ? 'table-primary' : '';
			
			if (count($PatientPersonalData) == 1)
			{
				$PatientPersonalData = $PatientPersonalData[0];
				
				include("engine/html/include/inc_check_personal.php");
				
				include("engine/html/include/inc_check_journal.php");
				
				if ($icon_case_personal_ok === true && $icon_case_journal_ok === true)
				{
				
				} else $icon_case_ok = false;
				
				if ($icon_case_ok === true)
				{
					$icons_case = $icon_case;
				}
				
				$icons_case = str_replace('[PERSONAL_ERROR]', implode(', ', $icon_case_personal_data), $icons_case);
				$icons_case = str_replace('[JOURNAL_ERROR]', implode(', ', $icon_case_journal_data), $icons_case);

//				debug( $PatientNameData );

//				debug($Today_Array);
				$DeleteButton = '';
				
				$Cancer = getarr(CAOP_CANCER, "cancer_patid='{$PatientPersonalData['patid_id']}'", "ORDER BY cancer_order_number ASC");
				
				$cancer_image = '';
				
				if (count($Cancer) > 0)
				{
					$Cancer_text = '';
					foreach ($Cancer as $dsunit)
					{
						$Cancer_text .= '[' . $dsunit['cancer_ds'] . '] ' . $dsunit['cancer_ds_text'] . "\n";
					}
					$cancer_image = '<img ' . super_bootstrap_tooltip($Cancer_text) . ' src="/engine/images/icons/cancer.png" /><div style="display: none">1</div>';
				}
				
			} else
			{
				bt_notice('Пациент отсутствует в базе данных', BT_THEME_DANGER);
				$PatientPersonalData['patid_name'] = 'безымянный';
			}
			
			if ($Today_Array['day_signature_state'] == 0)
			{
				$DeleteButton = '<a class="dropdown-item" href="javascript:journalRemovePatient(' . $Patient['journal_id'] . ')">Удалить из списка</a><div class="dropdown-divider"></div>';
			}
			
			$patient_status = '<b>НЕ ВЫБРАН</b>';
			if ($PatientPersonalData['patid_casestatus'] > 0)
			{
//                debug($CaseStatusesListId);
				$patient_status = $CaseStatusesListId['id' . $PatientPersonalData['patid_casestatus']]['casestatus_title'];
				
			}
//			debug($JournalInfirstId);

//			debug($PatStat);
			
			if ($light_id > 0)
			{
			
			} else
			{
				$light_bg = ((strlen($Patient['journal_ds_recom']) > 0)) ? 'table-info' : '';
			}
			
			$DispPatient = '';
			if ($Patient['journal_disp_isDisp'] == "2")
			{
				if ($Patient['journal_disp_lpu'] == "0" || strlen($Patient['journal_disp_mkb']) == 0)
				{
					$DispPatient = '<span ' . super_bootstrap_tooltip('Не указаны диспансерные данные!') . '>' . BT_ICON_CASE_WARNING . '</span>';
				} else
				{
					$LPU = $DispLPUId['id' . $Patient['journal_disp_lpu']]['lpu_showname'];
					$DispPatient = '<span ' . super_bootstrap_tooltip($Patient['journal_disp_mkb'] . '; ' . $LPU) . '>' . BT_ICON_DISPANSER . '</span>';
				}
			} else
			{
				if ($Patient['journal_disp_isDisp'] == "0")
				{
					$DispPatient = '<span ' . super_bootstrap_tooltip('Не указаны диспансерные данные!') . '>' . BT_ICON_CASE_WARNING . '</span>';
				}
			}
			
			?>
            <tr class="<?= $light_bg; ?>">
                <td data-title="#"
                    align="center">
                    <a name="patient<?= $Patient['journal_id']; ?>"></a>
                    <input class="form-check-input move-labeler"
                           type="checkbox"
                           name="labeler<?= $Patient['journal_id']; ?>"
                           id="labeler<?= $Patient['journal_id']; ?>"
                           data-patid="<?= $Patient['journal_id']; ?>"
                           value="1">
                    <label class="form-check-label box-label"
                           for="labeler<?= $Patient['journal_id']; ?>"><span></span><b><?= $npp; ?>)</b></label>
                </td>
                <td data-title="Открыть"
                    align="center">

                    <button <?= super_bootstrap_tooltip('Редактировать дневник'); ?> type="button"
                                                                                     class="btn btn-primary btn-sm openCardSPO"
                                                                                     data-patient="<?= $Patient['journal_id']; ?>">
						<?= BT_ICON_PEN_FILL; ?>
                    </button>
					
					<?php
					if ($USER_PROFILE['doctor_id'] == 1)
					{
						?>
                        <!--
                        <button <?= super_bootstrap_tooltip('ОТКРЫТЬ СПО'); ?> type="button" class="btn btn-danger btn-sm openCardSPO" data-patient="<?= $Patient['journal_id']; ?>">
		                    <?= BT_ICON_CASE_WARNING; ?>
                        </button>
                        -->
						<?php
					}
					?>

                </td>
                <td data-title="Открыть"
                    align="center">
                    <button <?= super_bootstrap_tooltip('Показать визиты'); ?> type="button"
                                                                               class="btn btn-success btn-sm"
                                                                               onclick="allVisits('<?= $PatientPersonalData['patid_id']; ?>', '1')">
						<?= BT_ICON_DIAG_TEXT; ?>
                    </button>
                </td>
                <td data-title="Карта"
                    align="center">
					<?= $PatientPersonalData['patid_ident']; ?>
                </td>
                <td data-title="CR">
					<?= $cancer_image; ?>
                </td>
                <td data-title="DISP">
					<?= $DispPatient; ?>
                </td>
                <td data-title="Время">
					<?= $Patient['journal_time']; ?>
                </td>

                <td>
					<?= $icons_case; ?>
                </td>

                <td data-title="Ф.И.О.">
					<?= editPersonalDataLink(shorty($PatientPersonalData['patid_name']), $PatientPersonalData['patid_id'], super_bootstrap_tooltip(mb_ucwords($PatientPersonalData['patid_name']))); ?>
                    <div class="text-muted"
                         style="font-size: 8pt"><?= $patient_status; ?></div>
                </td>
                <td data-title="Дата рождения"
                    align="center">
                    <div data-type="fioForSort"
                         style="display: none"><?= strtotime($PatientPersonalData['patid_birth']); ?></div>
					<?= $PatientPersonalData['patid_birth']; ?>
                </td>
                <td data-title="Диагноз">
					<?php
					if (strlen($Patient['journal_ds_text']) > 0)
					{
						?>
                        [<?= $Patient['journal_ds']; ?>] <?= $Patient['journal_ds_text']; ?>
						<?php
					}
					?>
					<?php
					if (strlen($Patient['journal_ds_recom']) > 0)
					{
						?>
                        <div class="text-muted size-10pt">&nbsp;&nbsp;&nbsp;<i class="bi bi-chat-left-dots-fill"></i>
                            <b>Рекомендовано:</b> <?= $Patient['journal_ds_recom']; ?></div>
						<?php
					}
					?>
					<?php
					if ($Patient['journal_dirstac'] > 0)
					{
						?>
                        <div class="text-muted size-10pt">&nbsp;&nbsp;&nbsp;<i class="bi bi-building"></i>
                            <b>Направлен:</b> <?= $DirStacListId['id' . $Patient['journal_dirstac']]['dirstac_title']; ?>
                        </div>
						<?php
					}
					?>
                </td>
                <td align="center">
					<?php
					$btn = ($Patient['journal_need_move'] == 0) ? html_img_gen("/engine/images/icons/journal_not_need_move.png", 'height="24"', 1) : html_img_gen("/engine/images/icons/journal_need_move.png", 'height="24"', 1);
					$btnvalue = ($Patient['journal_need_move'] == 0) ? 1 : 0;
					?>
                    <!--                    --><?//=BT_ICON_REGIMEN_DONE;
					?>
                    <span onclick="MySQLEditorAction(this, true)"
                          class="cursor-pointer"
                          id="needmove_<?= $Patient['journal_id']; ?>"
                          data-action="edit"
                          data-table="<?= CAOP_JOURNAL; ?>"
                          data-assoc="0"
                          data-fieldid="journal_id"
                          data-id="<?= $Patient['journal_id']; ?>"
                          data-field="journal_need_move"
                          data-buttonvalue="<?= $btnvalue; ?>"
                          data-return="#needmove_<?= $Patient['journal_id']; ?>"
                          data-returntype="html"
                          data-returnfunc="journal_current_move_button"><?= $btn; ?></span>
                </td>

                <td align="center">
                    <div class="dropdown div-jinline dropleft"
                         data-title="Действия">
                        <button class="btn btn-secondary dropdown-toggle btn-sm"
                                type="button"
                                id="buttonPatient<?= $Patient['journal_id']; ?>"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
							<?= BT_ICON_ACTIONS; ?>
                        </button>
                        <div class="dropdown-menu"
                             aria-labelledby="buttonPatient<?= $Patient['journal_id']; ?>">
							<?= $DeleteButton; ?>
                            <a class="dropdown-item"
                               href="javascript:uziCAOP(<?= $Patient['journal_id']; ?>)"><b>Назначить УЗИ ЦАОП</b></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               href="javascript:journalResearch2(<?= $Patient['journal_id']; ?>)">Записать
                                                                                                  на обследование</a>
                            <a class="dropdown-item"
                               href="javascript:journalCitology2(<?= $Patient['journal_id']; ?>)">Записать
                                                                                                  на цитологию</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               href="javascript:journalZNO(<?= $Patient['journal_id']; ?>)">
                                Установить ЗНО
                            </a>
                            <a class="dropdown-item"
                               target="_blank"
                               href="/noticeF1A/<?= $PatientPersonalData['patid_id']; ?>/add/<?= $Patient['journal_id']; ?>">
                                Установить 1А клиническую группу
                            </a>
                            <a class="dropdown-item"
                               href="/routeSheet/<?= $PatientPersonalData['patid_id']; ?>">Маршуртные
                                                                                           листы</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               href="/dailys/<?= $PatientPersonalData['patid_id']; ?>"><b>Дневник</b>, если ЕМИАС не работает</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               href="javascript:createDispancer(<?= $Patient['journal_id']; ?>)"><b>ДИСПАНСЕРИЗАЦИЯ</b></a>
                        </div>
                    </div>
                    <div class="dropdown div-jinline dropleft"
                         data-title="Документы">
                        <button class="btn btn-success dropdown-toggle btn-sm"
                                type="button"
                                id="buttonPatientDoc<?= $Patient['journal_id']; ?>"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="16"
                                 height="16"
                                 fill="currentColor"
                                 class="bi bi-book-half"
                                 viewBox="0 0 16 16">
                                <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu"
                             aria-labelledby="buttonPatientDoc<?= $Patient['journal_id']; ?>">
                            <a class="dropdown-item button_document_print"
                               href="#"
                               data-document="reference_simple"
                               data-patient="<?= $Patient['journal_id']; ?>">Справка после приёма</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item button_document_print"
                               href="#"
                               data-document="fgds"
                               data-patient="<?= $Patient['journal_id']; ?>">ТАЛОН на ФГДС</a>
                            <a class="dropdown-item button_document_print"
                               href="#"
                               data-document="fks"
                               data-patient="<?= $Patient['journal_id']; ?>">ТАЛОН на ФКС</a>
                            <a class="dropdown-item button_document_print"
                               href="#"
                               data-document="ekg"
                               data-patient="<?= $Patient['journal_id']; ?>">ТАЛОН на ЭКГ</a>
                            <a class="dropdown-item button_document_print"
                               href="#"
                               data-document="rentgen"
                               data-patient="<?= $Patient['journal_id']; ?>">Направление на R-графию</a>
                            <a class="dropdown-item button_document_print"
                               href="#"
                               data-document="vich"
                               data-patient="<?= $Patient['journal_id']; ?>">ТАЛОН на ВИЧ/RW</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               target="_blank"
                               href="/noticeDecline/<?= $PatientPersonalData['patid_id']; ?>/add/<?= $Patient['journal_id']; ?>">
                                Бланк Отказа от вмешательства
                            </a>
                            <a class="dropdown-item"
                               target="_blank"
                               href="/documentPrint/agree_research/<?= $Patient['journal_id']; ?>">
                                ИСП - Обследования
                            </a>
                        </div>
                    </div>

                </td>
            </tr>
			<?php
			$npp--;
		}
		?>
        </tbody>
    </table>
	<?php
	
} else
{
	bt_notice('Список пациентов пока пуст.');
}


//debug($DoctorsNurseId);