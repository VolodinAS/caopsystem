<div class="header">
	<table width="100%">
		<tr>
			<td align="center">
                <div class="gbuz boldy size-20pt"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
                <div class="address boldy"><?=$LPU_DOCTOR['lpu_lpu_address'];?></div>
			</td>
		</tr>
	</table>
	<div style="height: 5px"></div>
	<br/>
	<div class="boldy size-16pt">ТАЛОН на ЭКГ (КАБИНЕТ <b><span class="size-16pt">242</span></b>) - CITO</div>
	<br/>
	<div class="boldy size-12pt">Амбулаторная карта №<?=$PatientPersonalData['patid_ident'];?></div>
</div>
<br>
<?php
//debug($Doctor);
?>
<div>
	<table width="100%">
		<tr>
			<td width="50%" class="size-12pt" colspan="2"><b>ДАТА обследования:</b> <b>____.____.____________</b></td>
		</tr>
		<tr>
			<td width="50%" class="size-12pt" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%" class="size-12pt"><b>Ф.И.О. больного:</b> <?=mb_ucwords($PatientPersonalData['patid_name']);?></td>
			<td align="right" class="size-12pt"><b>Дата рождения:</b> <?=$PatientPersonalData['patid_birth'];?></td>
		</tr>
		
		<tr>
			<td width="50%" class="size-12pt" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
			<td width="50%" class="size-12pt" colspan="2"><b>АДРЕС:</b> <?=$PatientPersonalData['patid_address'];?></td>
		</tr>

		<tr>
			<td width="50%" class="size-12pt" colspan="2">&nbsp;</td>
		</tr>

		<tr>
			<td width="50%" class="size-12pt" colspan="2">
				<b>Страховая компания:</b> <?=$Insurance;?>, <b>Номер полиса:</b> <?=$PatientPersonalData['patid_insurance_number'];?>
			</td>
		</tr>
		<tr>
			<td width="50%" class="size-12pt" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td width="50%" class="size-12pt" colspan="2"><b>Диагноз:</b> [<?=$PatientJournal['journal_ds'];?>] <?=$PatientJournal['journal_ds_text'];?></td>
		</tr>
		<tr>
			<td width="50%" class="size-12pt" colspan="2">&nbsp;</td>
		</tr>
		
		<tr>
			<td width="50%" class="size-12pt" colspan="2" align="right"><b>Врач:</b> <?=$doc_name_sh?></td>
		</tr>

	</table>
</div>