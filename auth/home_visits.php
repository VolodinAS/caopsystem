<?php

//debugn($USER_PROFILE, '$USER_PROFILE');
if ( $USER_PROFILE['doctor_duty'] == "регистратор" || $USER_PROFILE['doctor_id'] == 1 )
{
	$options = array(
		'fields' => array(
			'home_patient_id' => array(
				'title' => 'ID пациента',
				'placeholder' => 'Введите ID пациента в системе, ЕСЛИ ЗНАЕТЕ - это НЕ НОМЕР карты!',
				'default' => '0'
			),
			'home_patient_full_name' => array(
				'title' => 'Полное имя пациента',
				'placeholder' => 'иванов иван иванович',
				'class' => 'required-field',
				'required' => true
			),
			'home_patient_birth' => array(
				'title' => 'Дата рождения',
				'placeholder' => 'Дата рождения в формате дд.мм.гггг',
				'class' => 'russianBirth required-field',
				'required' => true
			),
			'home_patient_phone' => array(
				'title' => 'Телефон для связи',
				'class' => 'required-field',
				'required' => true
			),
			'home_patient_address' => array(
				'title' => 'Адрес',
				'class' => 'required-field',
				'required' => true
			),
			'home_patient_diagnosis_mkb' => array(
				'title' => 'Диагноз МКБ',
				'placeholder' => 'Если знаете!',
				'class' => 'mkbDiagnosis'
			),
			'home_patient_diagnosis_text' => array(
				'title' => 'Текст диагноза',
				'placeholder' => 'Если знаете!'
			),
			'home_doctor_id' => array(
				'title' => 'Врач, кому назначен вызов',
				'related_array' => $DoctorsListId,
				'related_value' => 'doctor_id',
				'related_title' => 'callback.func_doctor_name',
				'class' => 'required-field',
				'required' => true
			),
			'home_doctor_dir' => array(
				'title' => 'Ф.И.О. врача, который вызывает на дом',
				'placeholder' => 'Если знаете!',
				'class' => 'required-field',
				'required' => true
			),
			'home_doctor_apk' => array(
				'title' => 'АПК, ЛПУ',
				'placeholder' => 'Учреждение, которое вызывает на дом',
				'default' => 'АПК 7',
				'class' => 'required-field',
				'required' => true
			),
		)
	);
	
	$options_encode = mysqleditor_modal_coder($options);
//$options_decode = mysqleditor_modal_coder($options_encode, 0);
//debugn($options, '$options');
//debugn($options_encode, '$options_encode');
//debugn($options_decode, '$options_decode');
	?>
    <button
            class="btn btn-primary mysqleditor-modal-form"
            id="myemf_button_add"
            type="button"
            data-action="add"
            data-table="<?=CAOP_HOME_VISIT;?>"
            data-fieldid="<?=$PK[CAOP_HOME_VISIT];?>"
            data-title="Добавить вызов на дом"
            data-options="<?=$options_encode;?>"
            data-callbacks="addFirstVisitAction"
    >
        Добавить вызов на дом
    </button>
    <div class="dropdown-divider"></div>
	
	<?php
	
	$HomeVisitTypes = getarr(CAOP_HOME_VISIT_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
//debugn($HomeVisitTypes, '$HomeVisitTypes');
	
	$HomeVisits = getHomeVisits(null, null, null, true);
	$HomeVisits2 = getDataByField($HomeVisits, 'action_type_id', 'type_', 'actions');
	unset($HomeVisits);
//debugn($HomeVisits2, '$HomeVisits2');
	foreach ($HomeVisitTypes as $homeVisitType)
	{
		$SortData = $HomeVisits2['type_' . $homeVisitType['type_id']];
//    debugn($SortData, '$SortData');
		foreach ($SortData as $sortDatum)
		{
			$HomeVisits[] = $sortDatum;
		}
	}

//debugn($HomeVisits2, '$HomeVisits2');
	bt_notice(wrapper('Вызовы на дом: ') . count($HomeVisits));
	?>
    <div class="dropdown-divider"></div>
	<?php
	
	if ( count($HomeVisits) > 0 )
	{
		$npp = 1;
		?>
        <table class="table">
            <thead>
            <tr>
                <!--                <th scope="col" class="text-center" data-title="process">P</th>-->
                <th scope="col" class="text-center" data-title="npp" width="1%">№</th>
                <th scope="col" class="text-center" data-title="id" width="1%">#</th>
                <th scope="col" class="text-center" data-title="fio">Ф.И.О. пациента</th>
                <th scope="col" class="text-center" data-title="birth" width="1%"><?=nbsper('Дата рождения');?></th>
                <th scope="col" class="text-center" data-title="phone">Телефон</th>
                <th scope="col" class="text-center" data-title="address">Адрес</th>
                <th scope="col" class="text-center" data-title="diagnosis">Диагноз</th>
                <th scope="col" class="text-center" data-title="doctor_caop" width="1%"><?=str_nbsp(nbsper('Врач ЦАОП'), 5);?></th>
                <th scope="col" class="text-center" data-title="doctor_lpu" width="1%"><?=str_nbsp(nbsper('Вызвавший врач'));?></th>
                <th scope="col" class="text-center" data-title="lpu_title" width="1%"><?=str_nbsp(nbsper('ЛПУ'), 5);?></th>
                <th scope="col" class="text-center" data-title="status"><?=str_nbsp(nbsper('Статус'));?></th>
                <th scope="col" class="text-center" data-title="action" width="1%">Действия</th>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ($HomeVisits as $homeVisit)
			{
				$mkb = ( strlen($homeVisit['home_patient_diagnosis_mkb']) > 0 ) ? '['.$homeVisit['home_patient_diagnosis_mkb'].'] ' : '';
				$dg = $mkb . $homeVisit['home_patient_diagnosis_text'];
				$doctor_name = docNameShort($homeVisit);
				$last_action = $homeVisit['actions'];
//            $last_action = $actions[0];

//            debugn($last_action, '$last_action');
				
				$ACTION = 'неизвестно';
				$bgcolor = '';
				if ( count($last_action) > 0 )
				{
					$doctor_name_action = docNameShort($last_action);
					$ACTION = $last_action['type_title'] . ' ('.$doctor_name_action.') ' . date(DMYHIS, $last_action['action_unix']);
					
					if ($last_action['action_type_id'] == 4) $bgcolor = ' bg-secondary';
					if ($last_action['action_type_id'] == 3) $bgcolor = ' bg-success';
					if ($last_action['action_type_id'] == 5) $bgcolor = ' bg-warning';
					if ($last_action['action_type_id'] == 6) $bgcolor = ' bg-danger';
					if ($last_action['action_type_id'] == 7) $bgcolor = ' bg-primary';
				}
				?>
                <tr class="<?=$bgcolor;?>">
                    <!--               <td data-cell="process">P</td>-->
                    <td data-cell="npp"><?=$npp;?></td>
                    <td data-cell="id"><?=$homeVisit[$PK[CAOP_HOME_VISIT]];?></td>
                    <td data-cell="fio"><?=$homeVisit['home_patient_full_name'];?></td>
                    <td data-cell="birth" class="text-center"><?=$homeVisit['home_patient_birth'];?></td>
                    <td data-cell="phone"><?=$homeVisit['home_patient_phone'];?></td>
                    <td data-cell="address"><?=$homeVisit['home_patient_address'];?></td>
                    <td data-cell="diagnosis"><?=$dg;?></td>
                    <td data-cell="doctor_caop" class="text-center"><?=$doctor_name;?></td>
                    <td data-cell="doctor_lpu" class="text-center"><?=$homeVisit['home_doctor_dir'];?></td>
                    <td data-cell="lpu_title" class="text-center"><?=$homeVisit['home_doctor_apk'];?></td>
                    <td data-cell="status" class="text-center">
						<?=$ACTION;?>
                    </td>
                    <td data-cell="action">
                        <div class="dropdown dropleft">
                            <button
                                    class="btn btn-sm btn-secondary dropdown-toggle"
                                    type="button"
                                    id="ddm_<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false">
								<?=BT_ICON_REGIMEN_TEMPLATE;?>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="ddm_<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>">

                                <button
                                        onclick="homeVisitProcess(<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>)"
                                        class="dropdown-item font-weight-bold"
                                        id="btn_process_<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>"
                                        type="button"
                                >Обработка вызова</button>

                                <div class="dropdown-divider"></div>

                                <button
                                        class="dropdown-item mysqleditor-modal-form"
                                        id="myemf_button_<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>"
                                        type="button"
                                        data-action="edit"
                                        data-table="<?=CAOP_HOME_VISIT;?>"
                                        data-fieldid="<?=$PK[CAOP_HOME_VISIT];?>"
                                        data-id="<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>"
                                        data-title="Редактирование вызова"
                                        data-options="<?=$options_encode;?>"
                                >
                                    Редактировать
                                </button>
                                <div class="dropdown-divider"></div>
                                <button
                                        class="btn btn-danger btn-sm col font-weight-bolder"
                                        onclick="if (confirm('Вы действительно хотите удалить данную запись?')){MySQLEditorAction(this, true);}"
                                        data-action="remove"
                                        data-table="<?=CAOP_HOME_VISIT;?>"
                                        data-assoc="0"
                                        data-fieldid="<?=$PK[CAOP_HOME_VISIT];?>"
                                        data-id="<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>"
                                        data-callbackfunc="removeHomeVisitActions"
                                        data-callbackparams="<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>"
                                        data-callbackcond="success"
                                >Удалить запись</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <!--            <tr>-->
                <!--                <td colspan="100%">-->
                <!--	                --><?php //debugn($last_action, '$last_action');?>
                <!--                </td>-->
                <!--            </tr>-->
				<?php
				$npp++;
			}
			?>
            </tbody>
        </table>
		<?php
	}
} else
{
    bt_notice('У Вас нет доступа к данному разделу', BT_THEME_WARNING);
}


require_once ("engine/html/modals/home_visit/home_visit.php");

?>

<script defer type="text/javascript" src="/engine/js/home_visits.js?<?=rand(0, 9999);?>"></script>
