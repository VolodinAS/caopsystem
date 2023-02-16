<div class="size-16pt header boldy">
	Отказ от видов медицинских вмешательств, включенных<br>
	в Перечень определенных видов медицинских вмешательств,<br>
	на которые граждане дают информированное добровольное согласие<br>
	при выборе врача и медицинской организации для получения<br>
	первичной медико-санитарной помощи<br>
</div>

<br>

<div class="size-14pt" style="text-align: justify">
	Я, <b><?=mb_strtoupper($PatientData['patid_name'], UTF8);?>  <?=$PatientData['patid_birth'];?> года
    рождения</b>, зарегистрированный(-ая) по адресу <b>ТОЛЬЯТТИ, <?=$PatientData['patid_address'];?></b>, отказываюсь от
	следующих видов медицинских вмешательств, включенных в Перечень определенных видов медицинских вмешательств, на
	которые граждане дают информированное добровольное согласие при выборе врача и медицинской организации для
	получения первичной медико-санитарной помощи, утвержденный приказом Министерства здравоохранения и социального
	развития Российской Федерации от 23 апреля 2012 г. N 390н (зарегистрирован Министерством юстиции Российской
	Федерации 5 мая 2012 г. N  24082) (далее - виды медицинских вмешательств):
</div>
<br>
<div class="size-14pt" style="text-align: center; font-weight: bolder">
	<?=$BlankDeclinePrint['decline_phrase'];?>.
</div>
<br>
<div class="size-14pt">
	Медицинский работник ЦАОП
</div>
<div class="size-14pt" style="text-align: center; font-weight: bolder">
	врач-онколог <?=docNameShort($DoctorData, 'famimot')?>
</div>
<br>
<div class="size-14pt" style="text-align: justify">
	в доступной для меня форме мне разъяснил возможные последствия отказа от вышеуказанных видов медицинских
	вмешательств, в том числе вероятность развития осложнений заболевания (состояния). Мне разъяснено, что при
	возникновении необходимости в осуществлении одного или нескольких видов медицинских вмешательств, в отношении
	которых оформлен настоящий отказ, я имею право оформить информированное добровольное согласие на такой вид
	(такие виды) медицинского вмешательства.
</div>
<br><br><br>
<div>
	<table class="size-14pt" width="100%" style="border-collapse: collapse" border="0">
		<tr>
			<td width="15%" style="border-bottom: solid 1px #000">&nbsp;</td>
			<td>&nbsp;</td>
			<td style="border-bottom: solid 1px #000"><?=mb_strtoupper($PatientData['patid_name'], UTF8);?>, <?=$PatientData['patid_phone'];?></td>
		</tr>
		<tr>
			<td style="text-align: center">(подпись)</td>
			<td>&nbsp;</td>
			<td style="text-align: center">(Ф.И.О. гражданина, контактный телефон)</td>
		</tr>
		
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		
		<tr>
			<td style="border-bottom: solid 1px #000" width="15%">&nbsp;</td>
			<td>&nbsp;</td>
			<td style="border-bottom: solid 1px #000"><?=docNameShort($DoctorData, 'famimot'); ?></td>
		</tr>
		<tr>
			<td style="text-align: center">(подпись)</td>
			<td>&nbsp;</td>
			<td style="text-align: center">(Ф.И.О. медицинского работника)</td>
		</tr>
	</table>
</div>
<br><br><br>
<div class="size-14pt" style="text-align: right">
	<b>Дата оформления бланка:</b> <?=$BlankDeclinePrint['decline_date_create'];?>
</div>


<?php
//debug($BlankDeclinePrint);
//debug($PatientData);
//debug($DoctorData);
?>