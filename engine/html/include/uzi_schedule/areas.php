<?php
$UziDoctor = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$ParamPage'");
if ( count($UziDoctor) == 1 )
{
	$UziDoctor = $UziDoctor[0];
	$DoctorData = $DoctorsListId['id' . $UziDoctor['uzi_doctor_id']];
	?>
	<table>
		<tr>
			<td width="1%">
				<button class="btn btn-secondary btn-sm" onclick="location.href='/uziSchedule'">Вернуться</button>
			</td>
			<td><h5>Настройка областей исследования для врача <?=docNameShort($DoctorData);?>:</h5></td>
		</tr>
	</table>
    <br>
	<?php
    
//    debug($UziDoctor);
	
	$AreasDoctor = stripcslashes($UziDoctor['uzi_research_area_ids']);
	$AreasDoctorData = json_decode($AreasDoctor, 1);
	$AreasCollect = array2KeyValueArray($AreasDoctorData);
	
//	debug($AreasCollect);
	
	$AreasData = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "1", "ORDER BY area_title ASC");
	if ( count($AreasData) > 0 )
	{
		foreach ($AreasData as $areasDatum)
		{
			$tag_id = 'uzi_research_areas_' . $areasDatum['area_id'];
			$checked = ( $AreasCollect[$tag_id] ) ? ' checked' : '';
		?>
			<div class="form-group">
				<input <?=$checked;?> class="form-check-input mysqleditor" type="checkbox" name="uzi_research_areas[]" id="<?=$tag_id;?>" value="<?=$areasDatum['area_id'];?>" data-action="edit"
				data-table="<?=CAOP_SCHEDULE_UZI_DOCTORS;?>"
				data-assoc="0"
				data-fieldid="uzi_id"
				data-id="<?=$UziDoctor['uzi_id'];?>"
				data-field="uzi_research_area_ids"
				data-checkarray="1">
				<label class="form-check-label box-label" for="<?=$tag_id;?>"><span></span><b><?=$areasDatum['area_title'];?></b> (<?=$areasDatum['area_description'];?>)</label>
			</div>
		<?php
		}
	} else
	{
		bt_notice('Нет доступных областей исследования', BT_THEME_WARNING);
	}
	
} else
{
	bt_notice('Врача в списке не найдено', BT_THEME_DANGER);
}