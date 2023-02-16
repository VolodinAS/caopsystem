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
			<td><h5>Настройка смен УЗИ для врача <?=docNameShort($DoctorData);?>:</h5></td>
		</tr>
	</table>
	
	
	
	<?php
} else
{
	bt_notice('Врача в списке не найдено', BT_THEME_DANGER);
}