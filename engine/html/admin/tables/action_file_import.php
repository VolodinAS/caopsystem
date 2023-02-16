<?php
$TableRM = RecordManipulation($table, CAOP_TABLES, 'table_id');
if ($TableRM['result'])
{
	$TableData = $TableRM['data'];
	
	$FileRM = RecordManipulation($file_id, CAOP_TABLE_FILES, 'file_id');
	if ($FileRM['result'])
	{
		$FileData = $FileRM['data'];
		$file_path = getFullPathOfTableFile($FileData);
		
		if ($file_path !== false)
		{
			$Profiles = getarr(CAOP_TABLE_IMPORT_PROFILES, "1", "ORDER BY profile_title ASC");
			$ProfilesId = getDoctorsById($Profiles, 'profile_id');
			
			$use_profile = false;
			
			$profile_id = (int)$profile_id;
			
			if ( $profile_id > 0 )
			{
				$response['debug']['$profile_id'] = $profile_id;
				$CurrentProfile = $ProfilesId['id' . $profile_id];
				$response['debug']['$CurrentProfile'] = $CurrentProfile;
				if ( count($CurrentProfile) > 0 )
				{
					$use_profile = true;
					$response['debug']['$use_profile'] = $use_profile;
				}
			}
			
			$response['htmlData'] .= '
					<button class="btn btn-sm btn-primary btn-updateImport"
						data-table="' . $TableData['table_id'] . '"
						data-file="'.$FileData['file_id'].'">Обновить</button>
					';
			$response['result'] = true;
			$response['debug']['$file_path'] = $file_path;
			
			$response['htmlData'] .= spoiler_begin_return('Профиль импорта', 'import-profile', '');
			{
//						МОДАЛЬНОЕ ОКНО СОЗДАНИЯ
//						modal(table_id, file_id, create_new_flag|create_from_flag)
				$response['htmlData'] .= '
							<button class="btn btn-sm btn-success btn-createProfile"
								data-table="' . $TableData['table_id'] . '"
								data-file="'.$FileData['file_id'].'"
								data-fromfile="0">Создать профиль</button>
							<button class="btn btn-sm btn-info btn-createProfile"
								data-table="' . $TableData['table_id'] . '"
								data-file="'.$FileData['file_id'].'"
								data-fromfile="1">Создать из файла</button>
							<button class="btn btn-sm btn-warning btn-removeProfile"
								data-table="' . $TableData['table_id'] . '"
								data-file="'.$FileData['file_id'].'"
							>Удалить профиль</button>
							<button class="btn btn-sm btn-primary btn-editProfile"
								data-table="' . $TableData['table_id'] . '"
								data-file="'.$FileData['file_id'].'"
							>Редактировать профиль</button>
							
							<div class="dropdown-divider"></div>
						';
				$ImportProfiles = getarr(CAOP_TABLE_IMPORT_PROFILES, "profile_table_id='{$TableData['table_id']}'", "ORDER BY profile_title ASC");
				if ( count($ImportProfiles) > 0 )
				{
					$a2s_selected = ( $use_profile ) ? $CurrentProfile['profile_id'] : '';
					$response['debug']['$a2s_selected'] = $a2s_selected;
//							$response['htmlData'] .= debug_ret($ImportProfiles);
					$a2sDefault = array(
						'key' => 0,
						'value' => 'Выберите...'
					);
					$a2sSelected = array(
						'value' => $a2s_selected
					);
					$ProfileSelector = array2select($ProfilesId, 'profile_id', 'profile_title', null,
						'class="form-control profile-selector" id="profile-selector"', $a2sDefault, $a2sSelected);
					$response['htmlData'] .= $ProfileSelector['result'];
				} else $response['htmlData'] .= bt_notice('Не создано еще ни одного профиля', BT_THEME_WARNING, 1);
			}
			$response['htmlData'] .= spoiler_end_return();
			
			$table = Xls2Array($file_path, 5);
			
			$header_index = 0;
			if ($use_profile) $header_index = $CurrentProfile['profile_header_index'];
			
			$response['htmlData'] .= spoiler_begin_return('Предварительный просмотр', 'preview-table', '');
			{
				$response['debug']['$header_index'] = $header_index;
				
				$response['htmlData'] .= table_generator($table, array(
					'width' => '100%',
					'class' => 'table',
					'header' => true,
					'header_index' => $header_index,
					'if_header_index' => true
				));
			}
			$response['htmlData'] .= spoiler_end_return();
			
			$response['htmlData'] .= '<div class="dropdown-divider"></div>';
			$response['htmlData'] .= '<input class="form-check-input" type="checkbox" name="openTable" id="openTable" value="1" >
			<label class="form-check-label box-label" for="openTable"><span></span>Открыть таблицу после импорта</label>';
			$response['htmlData'] .= '<div class="dropdown-divider"></div>';
			
			$response['htmlData'] .= bt_notice(wrapper('ПЕРЕД ТЕМ, КАК ИМПОРТИРОВАТЬ ТАБЛИЦУ, ПРОВЕРЬТЕ И НАСТРОЙТЕ ТИПЫ ДАННЫХ СТОЛБЦОВ'), BT_THEME_DANGER, 1);
			
			$response['htmlData'] .= '<div class="dropdown-divider"></div>';
			
			$response['htmlData'] .= '<button class="btn btn-lg col btn-primary btn-import"
										data-table="' . $TableData['table_id'] . '"
										data-file="'.$FileData['file_id'].'"
										>ИМПОРТИРОВАТЬ</button>';
			
			$response['debug']['$table'] = $table;
			
		} else $response['msg'] = 'Данного файла на сервере нет!';
		
	} else $response['msg'] = $FileRM['msg'];
	
} else $response['msg'] = $TableRM['msg'];