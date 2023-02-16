<?php

/*
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * УСТАРЕВШИЙ ФАЙЛ
 * */
require_once ( "engine/html/journal_menu.php" );


//    debug($Today_Array);
$PatientsToday = getarr('caop_journal', "journal_day='{$Today_Array['day_id']}' AND journal_doctor='{$USER_PROFILE['doctor_id']}'", "ORDER BY journal_id DESC");
//    debug($PatientsToday);

if ( count($PatientsToday) > 0 )
{
	$FieldsData = getarr('caop_journal_field', "field_enabled='1'", "ORDER BY field_order ASC");
//        debug($FieldsData);
	?>
	<table class="table tablesorter table-sm" id="patient_journal">
		<thead>
		<tr>
			<th scope="col" width="1%">#</th>
			<?php
            $width1p_array = array('journal_patient_birth', 'journal_ds', 'journal_patient_ident', 'journal_time', 'journal_cardplace', 'journal_patient_phone');
            $sorterTrue_array = array('journal_patient_ident', 'journal_patient_name', 'journal_patient_birth', 'journal_time');
			foreach ($FieldsData as $field) {
				$field['field_desc'] = str_replace(" ", "&nbsp;", $field['field_desc']);
				$width = '';
				if ( in_array($field['field_name'], $width1p_array) ) $width = ' width="1%"';
				$sorterFalse = '';
				if ( !in_array($field['field_name'], $sorterTrue_array) ) $sorterFalse = ' class="sorter-false"';
				?>
				<th field_name="<?=$field['field_name'];?>"<?=$sorterFalse;?> scope="col"<?=$width;?>><?=$field['field_desc'];?></th>
				<?php
			}
			?>
			<th scope="col" width="1%">Действия</th>
            <th scope="col" width="1%" class="sorter-false">Загрузка</th>
		</tr>
		</thead>
		<tbody>
		<?php
        $npp = count($PatientsToday);
		foreach ($PatientsToday as $Patient) {
			?>
			<tr>
				<!--                <td>--><?//=$PatientData['patient_fio'];?><!--</td>-->
				<td>
					<input class="form-check-input move-labeler" type="checkbox" name="labeler<?=$Patient['journal_id'];?>" id="labeler<?=$Patient['journal_id'];?>" data-patid="<?=$Patient['journal_id'];?>" value="1">
                    <label class="form-check-label box-label" for="labeler<?=$Patient['journal_id'];?>"><span></span><b><?=$npp;?>)</b></td>
				<?php
				foreach ($FieldsData as $field) {
                    //debug($field);
                    $value = $Patient[$field['field_default']];

                    if ($field['field_title'] == "Имя")
                    {
                        $value = shorty($Patient[$field['field_default']], "famio");
                        $sortHidden = '<div data-type="fioForSort" style="display: none">'.$value.'</div>';
                    }
					$sortHidden = '';
					if ( in_array($field['field_name'], $sorterTrue_array) )
                    {
	                    $sortHidden = '<div data-type="fioForSort" style="display: none">'.$value.'</div>';
                    }
					//mysqleditor


					?>
					<td><?=$sortHidden;?>
                        <input class="mysqleditor form-control form-control-lg input-padding"
					           type="<?=$field['field_type'];?>"
					           value="<?=$value;?>"
					           style=""
					           data-action="edit"
					           data-table="caop_journal"
					           data-assoc="0"
					           data-fieldid="journal_id"
					           data-id="<?=$Patient['journal_id'];?>"
					           data-field="<?=$field['field_default'];?>"
					           data-return="<?=$field['field_return'];?>"
                               placeholder="<?=$field['field_title'];?>">
					</td>
					<?php

				}
				?>
				<td>

					<div class="dropdown div-inline">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="buttonPatient<?=$Patient['journal_id'];?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Действия
						</button>
						<div class="dropdown-menu" aria-labelledby="buttonPatient<?=$Patient['journal_id'];?>">
							<a class="dropdown-item" href="javascript:journalRemovePatient(<?=$Patient['journal_id'];?>)">Удалить из списка</a>
							<div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:journalResearch(<?=$Patient['journal_id'];?>)">Записать в очередь</a>
						</div>
					</div>

				</td>
                <td>
                    <div class="input-group input-group-sm invisible" id="fieldloader<?=$Patient['journal_id'];?>">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
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
//	    echo 1;
}