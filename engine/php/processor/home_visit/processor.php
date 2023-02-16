<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

switch ($act)
{
    case "get":
    	$response['result'] = true;
	    $HomeVisit = getHomeVisits($home_id);
	    if ( count($HomeVisit) > 0 )
	    {
		    $from_doctor = boolval(intval($from_doctor));
//		    $response['htmlData'] .= var_dump_ret($from_doctor);
		    $HomeVisit = $HomeVisit[0];
		    $response['debug']['$HomeVisit'] = $HomeVisit;
		
		    $mkb = ( strlen($HomeVisit['home_patient_diagnosis_mkb']) > 0 ) ? '['.$HomeVisit['home_patient_diagnosis_mkb'].'] ' : '';
		    $dg = $mkb . $HomeVisit['home_patient_diagnosis_text'];
		    
		    $DoctorName = docNameShort($DoctorsListId['id' . $HomeVisit['home_doctor_id']], "famimot");
//		    $HomeVisit['actions'] = [];
		    $ActionsHTML = bt_notice('Никаких действий пока еще не совершалось', BT_THEME_WARNING, 1);
		    
		    if ( count($HomeVisit['actions']) > 0 )
		    {
			    $ActionsHTML = '';
		        $Actions = $HomeVisit['actions'];
			    foreach ($Actions as $home_action)
			    {
			    	$EmployeeName = docNameShort($home_action);
			    	$comment = ( strlen($home_action['action_comment']) > 0 ) ? '<span class="small text-muted">'.$home_action['action_comment'].'</span>' : '';
				    $ActionsHTML .= '
				    <div class="row alert alert-primary">
						<div class="col">
							'.wrapper( date(DMYHIS, $home_action['action_unix']) ).' - '.$home_action['type_title'].'<br>
							'.$comment.'
						</div>
						<div class="col-auto text-right">
							'.$EmployeeName.'
						</div>
					</div>
				    ';
		        }
			
			    $action_comment_field = '<textarea class="form-control mb-2" placeholder="Комментарий для изменения статуса вызова" name="action_comment" id="action_comment"></textarea>';
			    $BUTTON_STEP = '';
			    $LastAction = $Actions[0];
			    if ( $LastAction['type_id'] == 1 )
			    {
			    	// добавлен. Разрешить только обработку
				    $BUTTON_STEP = '
				    <button class="btn btn-success btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 2)">Перевести в статус "Обработано регистратором"</button>
				    '.$action_comment_field;
				    if ( $from_doctor ) $BUTTON_STEP = bt_notice('Вызов находится в обработке у регистраторов', BT_THEME_WARNING, 1);
			    }
			    if ( $LastAction['type_id'] == 2 )
			    {
			    	// обработан регистратором. Разрешить только передачу врачу
				    $BUTTON_STEP = '
				    <button class="btn btn-success btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 3)">Перевести в статус "Передано врачу"</button>
				    '.$action_comment_field;
				    if ( $from_doctor ) $BUTTON_STEP = bt_notice('Вызов находится в обработке у регистраторов', BT_THEME_WARNING, 1);
			    }
			    if ( $LastAction['type_id'] == 3)
			    {
			    	// у врача. Несколько действий
//				    <button class="btn btn-success btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 4, 1)">
//						        Закончен
//						        </button>
				    $BUTTON_STEP = '
				    <div class="row">
						<div class="col">
							<button class="btn btn-success btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 5, 1)">
						        Возвращен регистратору
					        </button>
						</div>
						<div class="col">
							<button class="btn btn-warning btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 6, 1)">
						        Требуется выезд
					        </button>
						</div>
				    </div>
				    '.$action_comment_field;
				
				    if ( !$from_doctor ) $BUTTON_STEP = bt_notice('Вызов находится в обработке у врача '. $DoctorName, BT_THEME_WARNING, 1);
			    }
			    if ( $LastAction['type_id'] == 5)
			    {
			    	// возвращен регистратору
				    $BUTTON_STEP = '
				    <div class="row">
				    	<div class="col">
					        <button class="btn btn-success btn-lg col" onclick="if ( confirm(\'Вы действительно желаете закончить вызов?\') ){setHomeVisitStatus('.$HomeVisit['home_id'].', 4)}">
						        Закончен
					        </button>
						</div>
						<div class="col">
							<button class="btn btn-success btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 3)">
						        Передан врачу
					        </button>
						</div>
				    </div>
				    '.$action_comment_field;
				
				    if ( $from_doctor ) $BUTTON_STEP = bt_notice('Вызов находится в обработке у регистраторов', BT_THEME_WARNING, 1);
			    }
			    if ( $LastAction['type_id'] == 6)
			    {
				    $BUTTON_STEP = '
				    <div class="row">
				    	<div class="col">
					        <button class="btn btn-success btn-lg col" onclick="if ( confirm(\'Вы действительно желаете закончить вызов?\') ){setHomeVisitStatus('.$HomeVisit['home_id'].', 4)}">
						        Закончен
					        </button>
						</div>
						<div class="col">
							<button class="btn btn-success btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 7)">
						        Карта передана врачу
					        </button>
						</div>
				    </div>
				    '.$action_comment_field;
			    	
				    if ( $from_doctor ) $BUTTON_STEP = bt_notice('Вызов находится в обработке у регистраторов', BT_THEME_WARNING, 1);
			    }
			    if ( $LastAction['type_id'] == 7)
			    {
				    // у врача. Несколько действий
				    $BUTTON_STEP = '
				    <div class="row">
						<div class="col">
							<button class="btn btn-success btn-lg col" onclick="setHomeVisitStatus('.$HomeVisit['home_id'].', 5, 1)">
						        Возвращен регистратору
					        </button>
						</div>
				    </div>
				    '.$action_comment_field;
				
				    if ( !$from_doctor ) $BUTTON_STEP = bt_notice('Вызов находится в обработке у врача '. $DoctorName, BT_THEME_WARNING, 1);
			    }
		    }
		    
		    
		    
		    $visit_edit = '<button class="btn btn-secondary btn-sm col mb-2" onclick="openHomeVisitEditor('.$HomeVisit['home_id'].')">Редактировать</button>';
		    if ( $from_doctor ) $visit_edit = '';
		    
		    $response['htmlData'] .= '
			'.$visit_edit.'
			
			'.$BUTTON_STEP.'
			
		    <div class="row">
				<div class="col text-center">
				'.bt_notice(wrapper('Информация о вызове', 'h4'), BT_THEME_PRIMARY, 1).'
				</div>
			</div>
		    <div class="row">
				<div class="col bg-warning">
					<div class="mb-2">
						<span class="font-weight-bold">Пациент:</span> '.$HomeVisit['home_patient_full_name'].', '.$HomeVisit['home_patient_birth'].'
					</div>
					<div class="mb-2">
						<span class="font-weight-bold">Адрес:</span> '.$HomeVisit['home_patient_address'].'
					</div>
					<div class="mb-2">
						<span class="font-weight-bold">Телефон:</span> '.$HomeVisit['home_patient_phone'].'
					</div>
					<div class="mb-2">
						<span class="font-weight-bold">Диагноз:</span> '.$dg.'
					</div>
					<div class="mb-2">
						<span class="font-weight-bold">Вызвавший врач:</span> '.$HomeVisit['home_doctor_dir'].'
					</div>
					<div class="mb-2">
						<span class="font-weight-bold">Вызвавшее ЛПУ:</span> '.$HomeVisit['home_doctor_apk'].'
					</div>
					<div class="dropdown-divider"></div>
					<div class="mb-2 text-right">
						<span class="font-weight-bold">Врач ЦАОП:</span> '.$DoctorName.'
					</div>
				</div>
			</div>
			
			<div class="row mt-2">
				<div class="col text-center">
				'.bt_notice(wrapper('Процесс обработки вызова', 'h4'), BT_THEME_DANGER, 1).'
				</div>
			</div>
			<div class="row">
				<div class="col">
					'.$ActionsHTML.'
				</div>
			</div>
		    ';
	    } else $response['msg'] = 'Такого вызова не найдено!';
    break;
    case "set_status":
	    $HomeVisit = getHomeVisits($home_id);
	    if ( count($HomeVisit) > 0 )
	    {
		    $HomeVisit = $HomeVisit[0];
		    $param_status = array(
		        'action_home_id' => $home_id,
			    'action_type_id' => $action_id,
			    'action_personal_id' => $USER_PROFILE['doctor_id'],
			    'action_unix' => time(),
			    'action_comment' => $action_comment
		    );
		    $AddAction = appendData(CAOP_HOME_VISIT_ACTIONS, $param_status);
		    if ( $AddAction[ID] > 0 )
		    {
		    	$response['result'] = true;
		    } else
		    {
		    	$response['msg'] = 'Проблема при добавлении действия';
		    	$response['debug']['$AddAction'] = $AddAction;
		    }
	    } else $response['msg'] = 'Такого вызова не найдено!';
    break;
    case "remove":
        $RemoveHomeVisitActions = deleteitem(CAOP_HOME_VISIT_ACTIONS, "action_home_id='{$home_id}'");
        if ( $RemoveHomeVisitActions ['result'] === true )
        {
        	$response['result'] = true;
        	$response['msg'] = 'Вызов успешно удалён';
        } else
        {
        	$response['msg'] = 'При удалении данных вызова произошла ошибка';
        }
    break;
    default:
        $response['msg'] = 'Неизвестное действие с вызовом';
    break;
}