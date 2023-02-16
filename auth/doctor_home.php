<?php

$HomeVisits = getHomeVisits(null, $USER_PROFILE['doctor_id']);
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
			$actions = $homeVisit['actions'];
			$last_action = $actions[0];
			
			$ACTION = 'неизвестно';
			if ( count($last_action) > 0 )
			{
				$doctor_name_action = docNameShort($last_action);
				$ACTION = $last_action['type_title'] . ' ('.$doctor_name_action.') ' . date(DMYHIS, $last_action['action_unix']);
			}
			?>
			<tr>
				<!--               <td data-cell="process">P</td>-->
				<td data-cell="npp"><?=$npp;?></td>
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
								onclick="homeVisitProcess(<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>, 1)"
								class="dropdown-item font-weight-bold"
								id="btn_process_<?=$homeVisit[$PK[CAOP_HOME_VISIT]];?>"
								type="button"
							>Обработка вызова</button>
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
require_once ("engine/html/modals/home_visit/home_visit.php");

?>

<script defer type="text/javascript" src="/engine/js/home_visits.js?<?=rand(0, 9999);?>"></script>
