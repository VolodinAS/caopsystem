<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ($myemf_action)
{
	$go_next = false;
	switch ($myemf_action)
	{
	    case "edit":
		    if ($id)
		    {
				$go_next = true;
		    } else $response['msg'] = 'Не указан ID записи';
	    break;
	    case "add":
		    $go_next = true;
	    break;
	    case "save":
	        $param_values = $_POST;
	        unset($param_values['myemf_action']);
	        unset($param_values['table']);
	        unset($param_values['callbacks']);
	        if ( strlen($table) > 0 )
	        {
		        $AddRecord = appendData($table, $param_values);
		        if ($AddRecord[ID] > 0)
		        {
		        	$response['result'] = true;
		        	$response['record'] = $AddRecord;
		        } else
		        {
			        $response['msg'] = 'Ошибка при сохранении данных';
			        $response['debug']['$AddRecord'] = $AddRecord;
		        }
		        
	        } else $response['msg'] = 'Не указана таблица';
	    break;
	    default:
	        $response['msg'] = 'Действие ModalForm не определено';
	    break;
	}
	
	if ( $go_next )
	{
		if (strlen($table) > 0)
		{
			if (strlen($field_id) > 0)
			{
				
				switch ($myemf_action)
				{
					case "edit":
						
						if ( strlen($options) > 0 )
						{
							$options = mysqleditor_modal_coder($options, 0);
							if ( count($options['fields']) > 0 )
							{
								$FIELDS = $options['fields'];
							}
						}
						
						$TableData = getarr($table, "{$field_id}='{$id}'");
						if ( count($TableData) == 1 )
						{
							$TableData = $TableData[0];
							
							$fields = nospaces($fields);
							$fields_data = explode(',', $fields);
							
							$response['debug']['$fields'] = $fields;
							$response['debug']['$fields_data'] = $fields_data;
							
							$response['result'] = true;
							$TableStructure = tablestructure2(array($table), 1);
							$TableStructure = $TableStructure[$table];
							$FirstField = key($TableStructure);
							
							
							if ( count($fields_data) == 0 )
							{
								$response['debug']['$TableStructure'] = $TableStructure;
								$fields_data = array_keys($TableStructure);
							} else
							{
								if ( count($fields_data) == 1 )
								{
								    $field = $fields_data[0];
								    if ( strlen($field) == 0 )
								    {
									    $response['debug']['$TableStructure'] = $TableStructure;
									    $fields_data = array_keys($TableStructure);
								    }
								}
							}
							
							$response['debug']['$fields_data'] = $fields_data;
							
//							$response['htmlData'] .= debug_ret($TableStructure);
//							$response['htmlData'] .= debug_ret($fields_data);
							
							foreach ($fields_data as $field)
							{
								if ( !array_key_exists($field, $TableStructure) )
								{
									$response['msg'] = 'Искомые поля не соответствуют полям таблицы';
									break;
								}
							}
							
							$FilterTableStructure = [];
							foreach ($TableStructure as $Structure => $Data)
							{
								foreach ($fields_data as $field)
								{
									if ( $Structure == $field )
									{
//										$response['htmlData'] .= debug_ret('add');
										$FilterTableStructure[$Structure] = $Data;
										break;
									}
								}
								
							}
							$TableStructure = $FilterTableStructure;
							unset($FilterTableStructure);
							foreach ($TableStructure as $Structure => $Desc)
							{
								
								$disabled = '';
								if ( $Structure == $FirstField ) $disabled = ' disabled';
								
								$Structure_format = $Structure;
								$Placeholder_format = "Значение";
								$Class_format = "";
								$Value_format = "";
								$Required_format = "";
								if ( count($FIELDS) > 0 )
								{
									if ( $FIELDS[$Structure] !== null )
									{
										$FIELD = $FIELDS[$Structure];
										
										$Structure_format = $FIELD['title'];
										$Placeholder_format = $FIELD['placeholder'];
										$Class_format = $FIELD['class'];
										$Value_format = $FIELD['default'];
										$Required_format = ($FIELD['required']) ? ' required' : '';
									}
								}
								
								$response['htmlData'] .= '
								<div class="input-group input-group">
		                            <div class="input-group-prepend">
		                                <span class="input-group-text font-weight-bold">'.$Structure_format.'</span>
		                            </div>
	                            ';
								if ($Desc != 'text')
								{
									
									if ( count($FIELD['related_array']) > 0 )
									{
										$doctorDefault = array(
											'key' => '',
											'value' => 'Выберите врача...'
										);
										$doctorSelect = array(
											'value' => $TableData[$Structure]
										);
										$doctorSelector = array2select($FIELD['related_array'], $FIELD['related_value'], $FIELD['related_title'], $Structure,
											'class="form-control form-control-lg mysqleditor '.$Class_format.'" id="'.$Structure.'" data-action="edit"
											data-table="'.$table.'"
											data-assoc="0"
											data-fieldid="'.$FirstField.'"
											data-id="'.$TableData[$FirstField].'"
											data-field="'.$Structure.'"' . $Required_format, $doctorDefault, $doctorSelect);
										$response['htmlData'] .= $doctorSelector['result'];
										
									} else
									{
										$response['htmlData'] .= '
										<input '.$disabled.'
											'.$Required_format.'
				                            type="text"
				                            class="mysqleditor form-control mysqleditor '.$Class_format.'"
				                            data-action="edit"
				                            data-table="'.$table.'"
				                            data-assoc="0"
				                            data-fieldid="'.$FirstField.'"
				                            data-id="'.$TableData[$FirstField].'"
				                            data-field="'.$Structure.'"
				                            placeholder="'.$Placeholder_format.'"
				                            value="'.htmlspecialchars($TableData[$Structure]).'">
				                        ';
									}
									
									
								}
								if ($Desc == 'text')
								{
									$response['htmlData'] .= '
									<textarea '.$disabled.'
										'.$Required_format.'
	                                    class="mysqleditor form-control '.$Class_format.'"
	                                    data-action="edit"
	                                    data-table="'.$table.'"
			                            data-assoc="0"
			                            data-fieldid="'.$FirstField.'"
			                            data-id="'.$TableData[$FirstField].'"
			                            data-field="'.$Structure.'"
	                                    placeholder="'.$Placeholder_format.'"
	                                    rows="5"
	                                >'.htmlspecialchars($TableData[$Structure]).'</textarea>
									';
								}
								$response['htmlData'] .= '</div>';
							}
							$response['htmlData'] .= '<div class="dropdown-divider"></div>';
							$response['htmlData'] .= '
								<button
									class="btn btn-danger btn-sm col"
									onclick="if (confirm(\'Вы действительно хотите удалить данную запись?\')){MySQLEditorAction(this, true); window.location.reload()}"
                                    data-action="remove"
		                            data-table="'.$table.'"
		                            data-assoc="0"
		                            data-fieldid="'.$field_id.'"
		                            data-id="'.$id.'"
								>Удалить запись</button>';
						} else $response['msg'] = 'Такой записи не существует';
					break;
					case "add":
						
						if ( strlen($options) > 0 )
						{
						    $options = mysqleditor_modal_coder($options, 0);
						    if ( count($options['fields']) > 0 )
						    {
						        $FIELDS = $options['fields'];
						    }
						}
						
						$response['debug']['$FIELDS'] = $FIELDS;
						
						$response['result'] = true;
						$TableStructure = tablestructure2(array($table), 1);
						$TableStructure = $TableStructure[$table];
						$FirstField = key($TableStructure);
						
						$response['htmlData'] .= '<form id="modal_form_add">';
						if ( strlen($callbacks) > 0 )
						{
						    $response['htmlData'] .= '<input type="hidden" name="callbacks" id="callbacks" value="'.$callbacks.'">';
						} 
						foreach ($TableStructure as $Structure => $Desc)
						{
							$disabled = '';
							if ( $Structure == $FirstField ) $disabled = ' disabled';
							
							$Structure_format = $Structure;
							$Placeholder_format = "Значение";
							$Class_format = "";
							$Value_format = "";
							$Required_format = "";
							if ( count($FIELDS) > 0 )
							{
							    if ( $FIELDS[$Structure] !== null )
							    {
								    $FIELD = $FIELDS[$Structure];
								    
								    $Structure_format = $FIELD['title'];
								    $Placeholder_format = $FIELD['placeholder'];
								    $Class_format = $FIELD['class'];
								    $Value_format = $FIELD['default'];
								    $Required_format = ($FIELD['required']) ? ' required' : '';
							    }
							}
							
							$response['htmlData'] .= '
							
								<div class="input-group">
		                            <div class="input-group-prepend">
		                                <span class="input-group-text font-weight-bold">'.$Structure_format.'</span>
		                            </div>
	                   		';
							if ($Desc != 'text')
							{
								if ( count($FIELD['related_array']) > 0 )
								{
								    $doctorDefault = array(
								        'key' => '',
								        'value' => 'Выберите врача...'
								    );
								    $doctorSelector = array2select($FIELD['related_array'], $FIELD['related_value'], $FIELD['related_title'], $Structure,
								    'class="form-control form-control-lg '.$Class_format.'" id="'.$Structure.'"' . $Required_format, $doctorDefault);
								    $response['htmlData'] .= $doctorSelector['result'];
								} else
								{
									$response['htmlData'] .= '
									<input '.$disabled.'
										id="'.$Structure.'"
										name="'.$Structure.'"
			                            type="text"
			                            class="form-control form-control-lg '.$Class_format.'"
			                            placeholder="'.$Placeholder_format.'"
			                            value="'.$Value_format.'"'.$Required_format.'>
			                        ';
								}
								
							}
							if ($Desc == 'text')
							{
								$response['htmlData'] .= '
								<textarea '.$disabled.'
									id="'.$Structure.'"
									name="'.$Structure.'"
                                    class="form-control form-control-lg '.$Class_format.'"
                                    placeholder="'.$Placeholder_format.'"
                                    rows="5"
                                    '.$Required_format.'
                                >'.$Value_format.'</textarea>
								';
							}
							$response['htmlData'] .= '</div>';
						}
						$response['htmlData'] .= '</form>';
						$response['htmlData'] .= '<div class="dropdown-divider"></div>';
						$response['htmlData'] .= '
						<button
							onclick="SaveForm(this)"
							class="btn btn-success col "
							data-action="save"
							data-table="'.$table.'"
						>
							Добавить запись
						</button>';
					break;
					default:
						$response['msg'] = 'Действие ModalForm не определено';
					break;
				}
			} else $response['msg'] = 'Не указано поле индекса таблицы';
		} else $response['msg'] = 'Не указана таблица';
	}
}

