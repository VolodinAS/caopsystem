<?php
$response['stage'] = $action;
$response['result'] = true;
$patient_id = $_POST['patient_id'];
$response['htmlData'] = '';

if ($patient_id > 0)
{
	
	$SPOReasonTypes = getarr(CAOP_SPO_REASON_TYPES, "reason_enabled='1'", "ORDER BY reason_type_order ASC");
	$SPOReasonTypesId = getDoctorsById($SPOReasonTypes, 'reason_type_id');
	
	$PatientData = getarr(CAOP_JOURNAL, "journal_id='{$patient_id}'");
	if (count($PatientData) == 1)
	{
		$PatientData = $PatientData[0];
		
		$PatientPersonalData = getarr(CAOP_PATIENTS, "patid_id='{$PatientData['journal_patid']}'");
		if (count($PatientPersonalData) == 1)
		{
			$PatientPersonalData = $PatientPersonalData[0];
			$PATID = $PatientPersonalData['patid_id'];
			
			
			$selectArr = array(
				'value' => $USER_PROFILE['doctor_id']
			);
			$defaultArr = array(
				'key'   =>  '0',
				'value' =>  'Выберите врача'
			);
			$SelectDoctor = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'spo_start_doctor_id', 'id="spo_start_doctor_id" class="form-control form-control-lg"', $defaultArr, $selectArr);
			
			$selectArr2 = array(
				'value' => 0
			);
			$SelectDoctor2 = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'spo_end_doctor_id', 'id="spo_end_doctor_id" class="form-control form-control-lg"', $defaultArr, $selectArr2);
			
			$selectArr = array(
				'value' => 0
			);
			$defaultArr = array(
				'key'   =>  '0',
				'value' =>  'Выберите причину закрытия СПО'
			);
			$SelectSPOReasonTypes = array2select($SPOReasonTypesId, 'reason_type_id', 'reason_type_title', 'spo_end_reason_type', 'id="spo_end_reason_type" class="form-control form-control-lg"', $defaultArr, $selectArr);
			
			$button_createSPO = '
				<button type="button" class="btn btn-sm btn-primary newSPO" data-toggle="collapse" data-target="#createSPO">НОВОЕ СПО</button>
				<div class="collapse" id="createSPO">
					<form action="" method="post" id="form_newSPO">
						<input type="hidden" name="spo_patient_id" id="spo_patient_id" value="'.$PATID.'">
						<input type="hidden" name="spo_journal_id" id="spo_journal_id" value="'.$patient_id.'">
						<input type="hidden" name="spo_id" id="spo_id" value="0">
						<div class="form-group">
						
							<div class="row">
								<div class="col font-weight-bolder text-center">Заполняется в начале случая</div>
							</div>
							<div class="dropdown-divider"></div>
							
							<div class="row">
								<div class="col text-right">Дата первого визита: </div>
								<div class="col">
									<input class="form-control form-control-lg" type="date" name="spo_start_date_unix" id="spo_start_date_unix" value="'.date("Y-m-d", time()).'">
								</div>
							</div>
							<div class="row">
								<div class="col text-right">Врач, открывший СПО: </div>
								<div class="col">
									'.$SelectDoctor['result'].'
								</div>
							</div>
							<div class="row">
								<div class="col text-right">Направительный диагноз: </div>
								<div class="col">
									<input class="form-control form-control-lg mkbDiagnosis" type="text" name="spo_mkb_directed" id="spo_mkb_directed" placeholder="Введите код диагноза по МКБ-10">
								</div>
							</div>
							
							<br/>
							<div class="row">
								<div class="col font-weight-bolder text-center">Заполняется в конце случая<br>(случай закончен, установлено ЗНО)</div>
							</div>
							<div class="dropdown-divider"></div>
							
							<div class="row">
								<div class="col text-right">Заключительный диагноз: </div>
								<div class="col">
									<input class="form-control form-control-lg mkbDiagnosis" type="text" name="spo_mkb_finished" id="spo_mkb_finished" placeholder="Введите код диагноза по МКБ-10">
								</div>
							</div>
							<div class="row">
								<div class="col text-right">Дата закрытия СПО: </div>
								<div class="col">
									<input class="form-control form-control-lg" type="date" name="spo_end_date_unix">
								</div>
							</div>
							<div class="row">
								<div class="col text-right">Врач, закрывший СПО: </div>
								<div class="col">
									'.$SelectDoctor2['result'].'
								</div>
							</div>
							<div class="row">
								<div class="col text-right">Причина закрытия СПО: </div>
								<div class="col">
									'.$SelectSPOReasonTypes['result'].'
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col text-center">
									<button type="submit" class="btn btn-lg btn-success">СОХРАНИТЬ СПО</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			<div class="dropdown-divider"></div>
			';
			
			
//			$response['htmlData'] .= $PATID;
			$showEditCase = "innerSPO-openCard";
			$SPO = getarr(CAOP_SPO, "spo_patient_id='{$PATID}'", "ORDER BY spo_end_date_unix DESC");
			if ( count($SPO) > 0 )
			{
				$notice_SPO = '';
				$foundSPO = false;
				foreach ($SPO as $spo_check)
				{
					if ( $spo_check['spo_id'] == $PatientData['journal_spo_id'] )
					{
						$foundSPO = true;
						break;
					}
				}
				
				if (!$foundSPO)
				{
					$notice_SPO = bt_notice('У СЛУЧАЯ НЕ ВЫБРАНО СПО. Прежде чем редактировать дневник, выберите нужны СПО из списка, нажав "Выбрать"', BT_THEME_WARNING, 1);
					$showEditCase = '';
				} else
				{
					$notice_SPO = bt_notice('У СЛУЧАЯ ВЫБРАНО СПО. Можете редактировать дневник.', BT_THEME_SUCCESS, 1);
				}
				
				$ListSPO = '';
				
				$ListSPO .= '
				<table class="table table-sm size-12pt">
				    <thead>
				        <tr>
				        	<th scope="col" class="text-center" data-title="edit" '.super_bootstrap_tooltip('Редактировать СПО').'>'.BT_ICON_PEN_FILL.'</td>
				            <th scope="col" class="text-center" data-title="mkb_directed">МКБ направ.</th>
				            <th scope="col" class="text-center" data-title="opened">Открыто</th>
				            <th scope="col" class="text-center" data-title="opened_doctor_id">Врач, открывший СПО</th>
				            <th scope="col" class="text-center" data-title="mkb_prepared">Предвар. МКБ</th>
				            <th scope="col" class="text-center" data-title="closed">Закрыто</th>
				            <th scope="col" class="text-center" data-title="closed_doctor_id">Врач, закрывший СПО</th>
				            <th scope="col" class="text-center" data-title="mkb_finished">Закл. МКБ</th>
				            <th scope="col" class="text-center" data-title="reason">Закл. МКБ</th>
				            <th scope="col" class="text-center" data-title="selector">Переключить</th>
				            <th scope="col" class="text-center" data-title="delete">Удалить</th>
				            
				        </tr>
				    </thead>
				    <tbody>
				';
				
				foreach ($SPO as $spo_data)
				{
					$doctor_name_opened = docNameShort( $DoctorsListId[ 'id' . $spo_data['spo_start_doctor_id'] ] );
					$doctor_name_closed = docNameShort( $DoctorsListId[ 'id' . $spo_data['spo_end_doctor_id'] ] );
					
					$button_selector = '';
					
					if ( (int)$PatientData['journal_spo_id'] == (int)$spo_data['spo_id'] )
					{
						$button_selector = '<span class="font-weight-bolder">ВЫБРАН</span>';
					} else
					{
						$button_selector = '<button class="btn btn-light btn-sm innerSPO-changeSPO" data-journal="'.$patient_id.'" data-spo="'.$spo_data['spo_id'].'">Выбрать</button>';
					}
					
					$spo_data_json = base64_encode(json_encode($spo_data));
					
					$reason = $SPOReasonTypesId['id' . $spo_data['spo_end_reason_type']]['reason_type_title'];
					$ListSPO .= '
						<tr>
							<td data-cell="edit" '.super_bootstrap_tooltip('Редактировать СПО').'>
								<button type="button" class="btn btn-primary btn-sm editSPO" data-spodata="'.$spo_data_json.'">
                        			'.BT_ICON_PEN_FILL.'
                    			</button>
							</td>
				            <td class="text-center" data-cell="mkb_directed">'.$spo_data['spo_mkb_directed'].'</td>
				            <td class="text-center" data-cell="opened">'.date("d.m.Y", $spo_data['spo_start_date_unix']).'</td>
				            <td class="text-center" data-cell="opened_doctor_id">'.$doctor_name_opened.'</td>
				            <td class="text-center" data-cell="mkb_prepared">'.$spo_data['spo_mkb_prepared'].'</td>
				            <td class="text-center" data-cell="closed">'.date("d.m.Y", $spo_data['spo_end_date_unix']).'</td>
				            <td class="text-center" data-cell="closed_doctor_id">'.$doctor_name_closed.'</td>
				            <td class="text-center" data-cell="mkb_finished">'.$spo_data['spo_mkb_finished'].'</td>
				            <td class="text-center" data-cell="reason">'.$reason.'</td>
				            <td class="text-center" class="text-center" data-cell="selector">'.$button_selector.'</td>
				            <td class="text-center" class="text-center" data-cell="delete"><button class="btn btn-sm btn-info">'.BT_ICON_CLOSE.'</button></td>
			        	</tr>
				        ';
				}
				
				$ListSPO .= '
					</tbody>
				</table>
				';
				
		        $response['htmlData'] .= $button_createSPO;
		        $response['htmlData'] .= $notice_SPO;
				$response['htmlData'] .= '
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active innerSPO-updateListSPO" data-patient="'.$PATID.'" data-journal="'.$patient_id.'" id="spolist-tab" data-toggle="tab" href="#spolist" role="tab" aria-controls="spolist" aria-selected="true">Список СПО ('.count($SPO).')</a>
					</li>
					<li class="nav-item">
						<a class="nav-link '.$showEditCase.'" data-patient="'.$PATID.'" data-journal="'.$patient_id.'" id="visiteditor-tab" data-toggle="tab" href="#visiteditor" role="tab" aria-controls="visiteditor" aria-selected="false">Редактирование дневника</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active" id="spolist" role="tabpanel" aria-labelledby="spolist-tab">
						'.$ListSPO.'
					</div>
					<div class="tab-pane fade" id="visiteditor" role="tabpanel" aria-labelledby="visiteditor-tab">
					
					</div>
				</div>
				';
			
			} else
			{
				$response['htmlData'] .= bt_notice('Еще нет ни одного созданного СПО для пациента. Создайте СПО, прежде чем начать работать с дневником.',BT_THEME_DANGER, 1);
				$response['htmlData'] .= $button_createSPO;
			}
		
		} else {
			$response['htmlData'] = 'Пациент отсутствует в базе данных';
		}
		
	} else {
		$response['htmlData'] = 'Такого пациента нет';
	}
	
} else {
	$response['htmlData'] = "ID не указан";
}