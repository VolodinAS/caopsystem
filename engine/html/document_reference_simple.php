<br>
<div class="header">
    <div class="gbuz boldy"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
    <div class="address boldy"><?=$LPU_DOCTOR['lpu_lpu_address'];?></div>
	<div class="listheader boldy">СПРАВКА</div>
</div>
<div>
	<table width="100%">
		<tr>
			<td width="50%" class="size-12pt"><b>Ф.И.О.:</b> <?=mb_ucwords($PatientPersonalData['patid_name']);?></td>
            <td align="right" class="size-12pt"><b>Дата рождения:</b> <?=$PatientPersonalData['patid_birth'];?></td>
		</tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%" class="size-12pt" colspan="2"><b>Диагноз:</b> [<?=$PatientJournal['journal_ds'];?>] <?=$PatientJournal['journal_ds_text'];?></td>
        </tr>
        <? if ( strlen($PatientJournal['journal_ds_follow']) > 0 ): ?>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%" class="size-12pt" colspan="2"><b>Сопутствующий диагноз:</b> [<?=$PatientJournal['journal_ds_follow'];?>] <?=$PatientJournal['journal_ds_follow_text'];?></td>
        </tr>
        <? endif; ?>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%" class="size-12pt" colspan="2"><b>Рекомендации:</b> <?=$PatientJournal['journal_ds_recom'];?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td class="size-12pt"><b>Дата:</b> <?=date("d.m.Y");?></td>
            <td width="50%" class="size-12pt" align="right">Врач-онколог ______________ <?=shorty($DOCTOR_NAME);?></td>
        </tr>
	</table>
</div>