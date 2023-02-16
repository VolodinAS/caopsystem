<?php
$response['stage'] = $action;
$response['msg'] = 'begin';
$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

//$response['htmlData'] = 'Загрузка...';

$TableRM = RecordManipulation($table_id, CAOP_TABLES, 'table_id');
if ($TableRM['result'])
{
	$TableData = $TableRM['data'];
	
	$FileRM = RecordManipulation($file_id, CAOP_TABLE_FILES, 'file_id');
	if ($FileRM['result'])
	{
		$FileData = $FileRM['data'];
		$file_path = getFullPathOfTableFile($FileData);

//		$response['htmlData'] .= debug_ret($FileData);
		
		try
		{
			$table = Xls2Array($file_path, 5);
			
			$response['debug']['table_header'] = $table[1];
			
			
			$response['htmlData'] .= spoiler_begin_return('Полученная таблица', 'table_example', '');
			{
				$response['htmlData'] .= table_generator($table, [
					'width' => '100%',
					'header' => true,
					'num_str' => true
				]);
			}
			$header_string = (strlen($hs)) ? $hs : '1';
			$response['debug']['$hs'] = $hs;
			
			$response['htmlData'] .= spoiler_end_return();
			$response['htmlData'] .= '<form id="form_createImportProfile">';
			$response['htmlData'] .= '
				<div class="dropdown-divider"></div>
				<div class="form-group">
				    <label for="title">Название профиля:</label>
				    <input type="text" class="form-control" id="title" name="title" value="Профиль">
				</div>';
			$response['htmlData'] .= '<div class="dropdown-divider"></div>';
			$response['htmlData'] .= '
				<div class="dropdown-divider"></div>
				
				<div class="row">
					<div class="col">
						<label for="header_string">Строка с заголовками:</label>
					</div>
					<div class="col">
				    	<input type="text" class="form-control" id="header_string" name="header_string" value="' . $header_string . '">
					</div>
					<div class="col">
						<button
				        class="btn btn-secondary btn-recountTable"
				        data-table="' . $TableData['table_id'] . '"
				        data-file="' . $FileData['file_id'] . '"
						data-fromfile="'.$fromfile.'"
			        >Обновить данные</button>
					</div>
				</div>';
			$response['htmlData'] .= '<input type="hidden" name="table_id" id="table_id" value="' . $TableData['table_id'] . '">';
			$response['htmlData'] .= '<input type="hidden" name="file_id" id="file_id" value="' . $FileData['file_id'] . '">';
			$response['htmlData'] .= '<br><b>Выберите собираемые столбцы:</b><br>';
			$response['htmlData'] .= '
			<input class="form-check-input checkbox-checkall" data-target=".checks_createImportProfile" type="checkbox" name="check_all" id="check_all" value="1" >
			<label class="form-check-label box-label" for="check_all"><span></span><b>Выбрать всё</b></label>
			';
			$response['htmlData'] .= '<div class="dropdown-divider"></div>';
			$response['htmlData'] .= '<ul>';
			foreach ($table[$header_string] as $column_id => $column_name)
			{
				$response['htmlData'] .= '
				<li>
					<input class="form-check-input checks_createImportProfile" type="checkbox" name="columns" id="column_' . $column_id . '" value="' . $column_name . '" >
					<label class="form-check-label box-label" for="column_' . $column_id . '"><span></span>' . $column_name . '</label>
				</li>
				';
			}
			$response['htmlData'] .= '</ul>';
			$response['htmlData'] .= '<div class="dropdown-divider"></div>';
			$response['htmlData'] .= '
				<div class="form-group">
				    <label for="offset">Введите номер строки, с которой собираются данные:</label>
				    <input type="text" class="form-control" id="offset" name="offset" value="2">
				</div>';
			$response['htmlData'] .= '<button class="btn btn-sm btn-primary col btn-addImportProfile">Создать</button>';
			$response['htmlData'] .= '</form>';
		} catch (PHPExcel_Reader_Exception $e)
		{
			$response['msg'] = $e;
		} catch (PHPExcel_Exception $e)
		{
			$response['msg'] = $e;
		}
		
	} else $response['msg'] = $FileRM['msg'];
	
} else $response['msg'] = $TableRM['msg'];