<?php
//debug($PatientPersonalData);
?>

<table width="100%" class="tbc size-14pt">
	<tr>
		<td class="textcenter boldy">
            ИНФОРМИРОВАННОЕ СОГЛАСИЕ ПАЦИЕНТА
            <br><br>
        </td>
	</tr>

<!--    <tr><td><br></td></tr>-->

<!--    <tr><td><br></td></tr>-->
    
	<tr>
		<td class="rare-text">
            Я, <b><u><?=$PatientPersonalData['patid_name'];?>, <?=$PatientPersonalData['patid_birth'];?> г.р.,</u></b> обязуюсь после прохождения назначенных мне бесплатных обследований и консультаций по бесплатно выданным направлениям <u>врачом-онкологом Володиным Александром Сергеевичем</u>, вернуться на повторный приём с результатами данных обследований.
            <br><br>
        </td>
	</tr>

<!--    <tr><td><br></td></tr>-->
    
    <tr>
        <td class="rare-text">
            Если результаты врачу переданы не будут, <u>врач не сможет</u> поставить мне <u>точный диагноз</u> и дать соответствующие рекомендации, о чем я проинформирован(-а).
            <br><br>
        </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td>
            <table class="tbc" width="100%" cellpadding="10">
                <tr>
                    <td align="right">Подпись пациента</td>
                    <td width="20%" style="border-bottom: solid 1px #000">&nbsp;</td>
                    <td align="right" width="1%"><?=nbsper(shorty($PatientPersonalData['patid_name']));?></td>
                </tr>
                <tr><td colspan="3"><br></td></tr>
                <tr>
                    <td align="right">Подпись врача</td>
                    <td width="20%" style="border-bottom: solid 1px #000">&nbsp;</td>
                    <td align="right"><?=nbsper($doc_name_sh);?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>