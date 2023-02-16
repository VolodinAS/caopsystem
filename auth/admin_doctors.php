<?php

$DoctorsFields = array(
	'doctor_id' => array(
		'title' => 'ID',
		'type' => 'input',
		'enabled' => false
	),
	'doctor_enabled' => array(
		'title' => 'Пользователь активирован?',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_isdoc' => array(
		'title' => 'Это врач?',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_ismajornurse' => array(
		'title' => 'Это старшая медсестра?',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_isnurse' => array(
		'title' => 'Это медсестра?',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_ishead' => array(
		'title' => 'Это заведущий?',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_isreg' => array(
		'title' => 'Это регистратор?',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_lpu_id' => array(
		'title' => 'Место работы',
		'type' => 'selector',
		'enabled' => true,
		'foreign' => array(
			'data_arr' => 'LpuId',
			'field_id' => 'lpu_id',
			'field_title' => 'lpu_blank_name'
		),
	),
	'doctor_f' => array(
		'title' => 'Фамилия',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_i' => array(
		'title' => 'Имя',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_o' => array(
		'title' => 'Отчество',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_birth' => array(
		'title' => 'Дата рождения',
		'type' => 'input',
		'enabled' => true,
		'class' => 'russianBirth'
	),
	'doctor_birth_unix' => array(
		'title' => 'Дата рождения (unix)',
		'type' => 'input',
		'enabled' => false,
		'updateBy' => array(
			'update' => 'date2unix',
			'field' => 'doctor_birth'
		)
	),
	'doctor_code' => array(
		'title' => 'Код',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_isBirth' => array(
		'title' => 'Участвует в списке ДР',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_duty' => array(
		'title' => 'Должность',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_cabinet' => array(
		'title' => 'Кабинет',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_nurse' => array(
		'title' => 'Основная медсестра',
		'type' => 'selector',
		'enabled' => true,
		'foreign' => array(
			'data_arr' => 'DoctorsNurseId',
			'field_id' => 'doctor_id',
			'field_title' => 'doctor_f'
		),
		'updateBy' => array(
			'update' => 'getNurseHTML',
			'field' => 'doctor_nurse'
		)
	),
	'doctor_ds' => array(
		'title' => 'Врач дневного стационара',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_miac_login' => array(
		'title' => 'ЕМИАС логин',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_miac_pass' => array(
		'title' => 'ЕМИАС пароль',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_access' => array(
		'title' => 'Доступы',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_phone' => array(
		'title' => 'Телефон',
		'type' => 'input',
		'enabled' => true
	),
	'doctor_isUzi' => array(
		'title' => 'Это врач УЗИ?',
		'type' => 'switcher',
		'enabled' => true
	),
	'doctor_test' => array(
		'title' => 'Это тест?',
		'type' => 'switcher',
		'enabled' => true
	),
);

?>
<button class="btn btn-sm btn-primary btn-addDoctor">Добавить врача</button>
<?php

$Doctors = getarr(CAOP_DOCTOR, "doctor_test='0'", "ORDER BY doctor_f, doctor_i, doctor_o ASC");

if (count($Doctors) > 0)
{
	?>
    <table class="table tbc table-sm"
           width="100%">
        <tbody>
		<?php
		$skeleton_path = "/engine/images/icons/doctor.png";
		foreach ($Doctors as $doctor)
		{
			$doc_name = docNameShort($doctor, "famimot");
			
			$file_path = "/engine/images/avatars/doctor_{$doctor['doctor_id']}.png";
			
			$avatar_path = (file_exists(ROOT . $file_path)) ? $file_path : $skeleton_path;
//			$avatar_path = "/engine/images/avatars/doctor_{$doctor['doctor_id']}.png";
			?>
            <tr data-doctor="<?= $doctor['doctor_id']; ?>">
                <td data-cell="avatar"
                    width="1%">
                    <img src="<?= $avatar_path; ?>"
                         alt="<?= $doc_name; ?>"
                         width="100"
                         class="avatar br-10px">
                    <button class="btn btn-sm btn-secondary btn-removeDoctor col">Удалить</button>
                    <button class="btn btn-sm btn-primary btn-quickAccess col" data-quick="<?=$doctor['doctor_quick'];?>">Быстрый доступ</button>
                </td>
                <td data-cell="info">
					<?php
					foreach ($DoctorsFields as $field => $fieldData)
					{
						$enabled = ($fieldData['enabled']) ? '' : ' disabled';
						?>
                        <div class="row m-2">
                            <div class="col-4">
                                <b><?= $fieldData['title']; ?></b>
                            </div>
                            <div class="col">
								<?php
								switch ($fieldData['type'])
								{
									case "input":
										if ($fieldData['enabled'])
										{
											?>
                                            <input type="text"
                                                   class="form-control mysqleditor <?= $fieldData['class']; ?>"
                                                   data-action="edit"
                                                   data-table="<?= CAOP_DOCTOR; ?>"
                                                   data-assoc="0"
                                                   data-fieldid="doctor_id"
                                                   data-id="<?= $doctor['doctor_id']; ?>"
                                                   data-field="<?= $field; ?>"
                                                   data-unixfield="doctor_unix_updated"
                                                   value="<?= $doctor[$field]; ?>">
											<?php
										} else
										{
											?>
                                            <input type="text"
                                                   class="form-control"
                                                   value="<?= $doctor[$field]; ?>"
                                                   disabled>
											<?php
										}
										
										break;
									case "switcher":
										$param_checked = ((int)$doctor[$field] == 1) ? ' checked' : '';
										
										$switcher_checkbox_options = array(
										    'mye' => array(
                                                'table' => CAOP_DOCTOR,
                                                'field_id' => 'doctor_id',
                                                'id' => $doctor['doctor_id'],
                                                'field' => $field,
                                                'unixfield' => 'doctor_unix_updated'
										    ),
                                            'addon' => $param_checked
										);
										$switcher_checkbox = checkbox_switcher($switcher_checkbox_options);
										echo $switcher_checkbox;
										?>
										<?php
										break;
									case "selector":
										$go_next = false;
									    if ( $field == 'doctor_nurse' )
                                        {
                                            if ( $doctor['doctor_isnurse'] > 0 || $doctor['doctor_ismajornurse'] > 0 )
                                            {
                                                bt_notice('Невозможно выбрать медсестру для медсестры');
                                            } else $go_next = true;
                                        } else $go_next = true;
									    if ( $go_next )
                                        {
	                                        $ForSelect = $fieldData['foreign']['data_arr'];
	                                        $ArrForSelect = $$ForSelect;
	                                        $a2sDefault = array(
		                                        'key' => 0,
		                                        'value' => 'Выберите...'
	                                        );
	                                        $a2sSelected = array(
		                                        'value' => $doctor[$field]
	                                        );
	                                        $a2sSelector = array2select($ArrForSelect, $fieldData['foreign']['field_id'], $fieldData['foreign']['field_title'], null,
		                                        'class="form-control mysqleditor" data-action="edit"
                                                data-table="' . CAOP_DOCTOR . '"
                                                data-assoc="0"
                                                data-fieldid="doctor_id"
                                                data-id="' . $doctor['doctor_id'] . '"
                                                data-field="' . $field . '"
                                                data-unixfield="doctor_unix_updated"', $a2sDefault, $a2sSelected);
	                                        echo $a2sSelector['result'];
                                        }
										break;
								}
								?>
                            </div>
                        </div>
                        <!--                        <div class="dropdown-divider"></div>-->
						<?php
					}
					?>
                </td>
            </tr>
			<?php
		}
		?>
        </tbody>
    </table>
	<?php
} else
{
	bt_notice('В системе пока нет врачей');
}

//foreach ($Doctors as $doctor)
//{
//
//}
?>
<script defer type="text/javascript" src="/engine/js/admin/admin_doctors.js?<?=rand(0, 9999);?>"></script>
