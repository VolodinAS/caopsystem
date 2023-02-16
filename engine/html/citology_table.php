<?php
if (count($CitologyArray) > 0)
{
	$npp = 1;
//    debug($CitologyArray);
	
 
	
	?>
    <table class="table table-sm tbc" border="1" id="maincitology">
        <thead>
        <th scope="col" width="1%" class="full-center">#</th>
        <th scope="col" width="1%" class="full-center">Дата</th>
        <th scope="col" width="1%"
            class="full-center sorter-false" <?= super_bootstrap_tooltip('Редактировать информацию о цитологии'); ?>>
            <?=BT_ICON_PEN_FILL;?>
        </th>
        <th scope="col" class="full-center">
            Ф.И.О.
            <input type="text" class="form-control-sm form-control" id="search">
        </th>
        <th scope="col" width="1%" class="full-center">Дата рождения</th>
        <th scope="col" width="1%" class="full-center"><?= str_nbsp('Врач', 10); ?></th>
        <th scope="col" width="1%" class="full-center sorter-false">МКБ</th>
        <th scope="col" class="full-center sorter-false">Диагноз</th>
        <!--		<th scope="col"  width="1%" class="full-center sorter-false">--><? //=str_nbsp('Анализ', 12);
		?><!--</th>-->
        <!--		<th scope="col"  width="1%" class="full-center">Дата&nbsp;проведения</th>-->
        <!--		<th scope="col"  width="1%" class="full-center">Дата&nbsp;результата</th>-->
        <!--		<th scope="col"  width="1%" class="full-center sorter-false">Номер&nbsp;результата</th>-->
        <!--		<th scope="col"  class="full-center sorter-false">Результат</th>-->
        <th scope="col" class="full-center sorter-false">Действия</th>
        </thead>
        <tbody>
		<? foreach ($CitologyArray as $PatientCitology): ?>
			<?php
//        $PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientCitology['citology_patid']}'");
//        if ( count($PatientPersonalData) == 1 ) {
//            $PatientPersonalData = $PatientPersonalData[0];
//        }
//        debug($light_id);
			$light_bg = (strlen($PatientCitology['citology_result_text']) > 0) ? 'table-dark' : '';
			$light_bg = ($light_id == $PatientCitology['citology_id']) ? 'table-primary' : $light_bg;
			
			$CitologyResult = $PatientCitology['citology_result_text'];
			$CitologyResult = ( strlen($PatientCitology['citology_result_text']) > 2 ) ? '<br>'.wrapper('Заключение:') . '<br>' . $CitologyResult : '';
			?>
            <tr class="<?= $light_bg; ?>" id="stringPatient<?= $PatientCitology['citology_id'] ?>">
                <td align="center"><b><?= $npp; ?></b></td>
                <td align="center"><?= date("d.m.Y H:i", $PatientCitology['citology_dir_date_unix']); ?></td>
                <td align="center">
                    <button <?= super_bootstrap_tooltip('Редактировать цитологию'); ?> type="button"
                                                                                     class="btn btn-primary btn-sm"
                                                                                     onclick="javascript:openCitologyCard(<?= $PatientCitology['citology_id']; ?>)">
                        <?=BT_ICON_PEN_FILL;?>
                    </button>
                </td>
                <td>
					<?= editPersonalDataLink(shorty($PatientCitology['patid_name']), $PatientCitology['patid_id'], super_bootstrap_tooltip(mb_ucwords($PatientCitology['patid_name']))); ?>
                    <a name="citopat<?= $PatientCitology['citology_id']; ?>"></a>
                    <br>
	                [<?= $PatientCitology['patid_ident']; ?>]
                </td>
                <td align="center"><?= $PatientCitology['patid_birth']; ?></td>
                <td align="center">
					<?php
					$Doctor = $DoctorsListId['id' . $PatientCitology['citology_doctor_id']];
					$doc_name = $Doctor['doctor_f'] . ' ' . $Doctor['doctor_i'] . ' ' . $Doctor['doctor_o'];
					$doc_name_sh = shorty($doc_name);
					?>
					<?= $doc_name_sh; ?>
                </td>
                <td align="center"><?= $PatientCitology['citology_ds_mkb']; ?></td>
                <td>
                    <?php
//                    debug($PatientCitology['citology_result_text']);
                    ?>
					<?= $PatientCitology['citology_ds_text']; ?>
					<?php
					if (strlen($PatientCitology['citology_analise_markers']) > 5)
					{
						$JSON_markers = stripcslashes($PatientCitology['citology_analise_markers']);
						$MarkersData = json_decode($JSON_markers);
						for ($i = 0; $i < count($MarkersData); $i++)
						{
							$npp2 = $i + 1;
							echo '<div class="text-muted size-10pt">&nbsp;&nbsp;&nbsp;<b>' . $npp2 . '</b> - ' . $MarkersData[$i] . '</div>';
						}
					}
					echo $CitologyResult;
					?>
                </td>
                <!--				<td align="center">-->
                <!--					--><?php
				//					if ( $PatientCitology['citology_analise_type'] == 0 )
				//					{
				//						echo '<i class="bi bi-arrow-down-square-fill"></i>';
				//					}
				//					$zeroOption_analize = array(
				//						'key'   =>  0,
				//						'value'    =>  'ВЫБЕРИТЕ'
				//					);
				//					$defaultSelect_analize = array(
				//						'key'   =>   'type_id',
				//						'value'   =>  $PatientCitology['citology_analise_type']
				//					);
				//					$mysqleditor_params_analize = 'data-action="edit" data-table="'.CAOP_CITOLOGY.'" data-assoc="0" data-fieldid="citology_id" data-id="'.$PatientCitology['citology_id'].'" data-field="citology_analise_type"';
				//					$CitologySelectorAnalize = array2select($CitologyTypes, 'type_id', 'type_title', 'citology_analise_type', ' class="mysqleditor form-control form-control-sm" ' . $mysqleditor_params_analize, $zeroOption_analize, $defaultSelect_analize);
				//					if ( $CitologySelectorAnalize['stat'] == RES_SUCCESS ) echo $CitologySelectorAnalize['result'];
				//
				//					if ( $PatientCitology['citology_analise_type'] == 0 )
				//					{
				//						echo '<i class="bi bi-arrow-up-square-fill"></i>';
				//					}
				//					?>
                <!--				</td>-->
                <!--				<td align="center">-->
                <!--					--><?php
				//					$citology_action_date = mysqleditor_field_generator(
				//						$CAOP_CITOLOGY,
				//						'citology_id',
				//						$PatientCitology['citology_id'],
				//						'citology_action_date',
				//						0,
				//						'',
				//						'',
				//						'input',
				//						'text',
				//						'mysqleditor form-control form-control-sm russianBirth',
				//						'',
				//						'Дата проведения',
				//						$PatientCitology['citology_action_date'],
				//						'edit',
				//						'0');
				//					echo $citology_action_date['input'];
				//					?>
                <!--					<div data-type="fioForSort" style="display: none">-->
				<? //=$PatientCitology['citology_action_date'];?><!--</div>-->
                <!--				</td>-->
                <!--				<td align="center">-->
                <!--					--><?php
				//					$citology_result_date = mysqleditor_field_generator(
				//						$CAOP_CITOLOGY,
				//						'citology_id',
				//						$PatientCitology['citology_id'],
				//						'patidcard_patient_done',
				//						0,
				//						'',
				//						'',
				//						'input',
				//						'text',
				//						'mysqleditor form-control form-control-sm russianBirth',
				//						'',
				//						'Дата результата',
				//						$PatientCitology['patidcard_patient_done'],
				//						'edit',
				//						'0');
				//					echo $citology_result_date['input'];
				//					?>
                <!--					<div data-type="fioForSort" style="display: none">-->
				<? //=$PatientCitology['patidcard_patient_done'];?><!--</div>-->
                <!--				</td>-->
                <!--				<td align="center">-->
                <!--					--><?php
				//					$citology_result_number = mysqleditor_field_generator(
				//						$CAOP_CITOLOGY,
				//						'citology_id',
				//						$PatientCitology['citology_id'],
				//						'citology_result_id',
				//						0,
				//						'',
				//						'',
				//						'input',
				//						'text',
				//						'mysqleditor form-control form-control-sm',
				//						'',
				//						'Номер',
				//						$PatientCitology['citology_result_id'],
				//						'edit',
				//						'0');
				//					echo $citology_result_number['input'];
				//					?>
                <!--				</td>-->
                <!--				<td align="center">-->
                <!--					--><?php
				//					$citology_result_text_tooltip = super_bootstrap_tooltip($PatientCitology['citology_result_text']);
				//					$citology_result_text = mysqleditor_field_generator(
				//						$CAOP_CITOLOGY,
				//						'citology_id',
				//						$PatientCitology['citology_id'],
				//						'citology_result_text',
				//						0,
				//						null,
				//						$citology_result_text_tooltip,
				//						'input',
				//						'text',
				//						'mysqleditor form-control form-control-sm',
				//						'',
				//						'Результат',
				//						$PatientCitology['citology_result_text'],
				//						'edit',
				//						'0');
				//					echo $citology_result_text['input'];
				//
				//					?>
                <!--				</td>-->
                <td align="center">
                    <div class="dropdown div-inline dropleft" data-title="Действия">
                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="buttonPatient<?= $PatientCitology['citology_id']; ?>" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <?=BT_ICON_ACTIONS;?>
                        </button>
                        <div class="dropdown-menu"
                             aria-labelledby="buttonPatient<?= $PatientCitology['citology_id']; ?>">
                            <a class="dropdown-item"
                               href="javascript:citologyRemovePatient(<?= $PatientCitology['citology_id']; ?>)">Удалить
                                из списка</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               href="javascript:citologyMark(<?= $PatientCitology['citology_id']; ?>)">Маркировка
                                материала</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item"
                               href="javascript:citologyPrintDirection(<?= $PatientCitology['citology_id']; ?>)">Распечатать
                                направление</a>
                        </div>
						<?php
                        if (strlen($PatientCitology['citology_result_text']) > 2)
                        {
	                        echo '&nbsp;<img ' . super_bootstrap_tooltip('Скопировать заключение') . ' src="/engine/images/icons/copy.png" class="cursor-pointer clickForCopy" style="width: 32px" data-target="citologyCopy' . $PatientCitology['citology_id'] . '">';
	                        echo '<textarea class="class-for-copy" id="citologyCopy' . $PatientCitology['citology_id'] . '">Цитология №' . $PatientCitology['citology_result_id'] . ' от ' . $PatientCitology['patidcard_patient_done'] . ' - ' . $PatientCitology['citology_result_text'] . '</textarea>';
                        }
						?>
                    </div>
                </td>
            </tr>
			<? $npp++; ?>
		<? endforeach; ?>
        </tbody>
    </table>
	<?php
} else
{
	bt_notice('Нет пациентов, записанных на цитологию', BT_THEME_WARNING);
}
?>