<div class="header">
    <table width="100%">
        <tr>
            <td width="15%">&nbsp;</td>
            <td align="center">
                <div class="gbuz boldy"><?=$LPU_DOCTOR['lpu_blank_name'];?></div>
                <div class="address boldy"><?=$LPU_DOCTOR['lpu_lpu_address'];?></div>
            </td>
            <td width="15%" align="center" class="size-8pt">
                Форма №203/у-02<br>
                Утверждена приказом<br>
                Минздрава
            </td>
        </tr>
    </table>
    <div style="height: 5px"></div>
    <div class="cito-listheader boldy">Направление</div>
    <div class="cito-gbuz boldy">на цитологическое диагностическое исследование и результат исследования</div>
    <div class="cito-gbuz boldy">Амбулаторная карта №<?=$PatientPersonalData['patid_ident'];?></div>
</div>
<div>
    <table width="100%">
        <tr>
            <td width="50%" class="size-10pt"><b>Ф.И.О. больного:</b> <?=mb_ucwords($PatientPersonalData['patid_name']);?></td>
            <td align="right" class="size-10pt"><b>Дата рождения:</b> <?=$PatientPersonalData['patid_birth'];?></td>
        </tr>
        <tr>
            <td width="50%" class="size-10pt"><b>Адрес больного:</b> <?=$PatientPersonalData['patid_address'];?></td>
            <td align="right" class="size-10pt"><b>Телефон больного:</b> <?=$PatientPersonalData['patid_phone'];?></td>
        </tr>

        <tr>
            <td width="50%" class="size-10pt" colspan="2">
                <b>Страховая компания:</b> <?=$Insurance;?>, <b>Номер полиса:</b> <?=$PatientPersonalData['patid_insurance_number'];?>
            </td>
        </tr>
        <tr>
            <td width="50%" class="size-10pt"><b>Диагноз:</b> [<?=$CitologyData['citology_ds_mkb'];?>] <?=$CitologyData['citology_ds_text'];?></td>
            <td align="right" class="size-10pt"><b>Метод анализа:</b> <?=$Patient_analize_data;?></td>
        </tr>
<!--        <tr>-->
<!--            <td width="50%" class="size-10pt" colspan="2"></td>-->
<!--        </tr>-->
        <tr>
            <td width="50%" class="size-10pt"><b>Лечащий врач (Ф.И.О., телефон):</b> <?=$doc_name_sh;?></td>
            <td align="right" class="size-10pt"><b>Дата взятия материала:</b> <?=date("d.m.Y H:i", $CitologyData['citology_dir_date_unix']);?></td>
        </tr>
        <tr>
            <td width="50%" class="size-10pt" colspan="2"></td>
        </tr>
	    <tr>
		    <td width="100%" class="size-10pt" colspan="2"><b>Маркировка материала:</b><br>
			    <?php
			    for ($i=0; $i<count($MarkersData); $i++)
			    {
				    $npp = $i+1;
				    echo '<div class="">&nbsp;&nbsp;&nbsp;<b>'.$npp.'</b> - ' . $MarkersData[$i].'</div>';
			    }
			    ?>
		    </td>
	    </tr>
<!--        <tr>-->
<!--            <td width="50%" class="size-10pt"></td>-->
<!--            <td align="right" class="size-10pt"></td>-->
<!--        </tr>-->
        <tr>
            <td class="size-10pt" colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td width="50%" class="size-10pt"><b>Направивший врач:</b> <?=$doc_name_sh;?></td>
            <td align="right" class="size-10pt"><b>Подпись врача</b> _________________</td>
        </tr>
    </table>
</div>