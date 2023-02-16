<?php
//$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
//$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');
?>
<table style="border-collapse: collapse" width="100%">
	<tr>
		<td style="font-size: 6pt" align="right">
			<br>
			Форма бланка №2<br>
			(для лечебно-профилактических учреждений)
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 14pt" align="center">
			<b>НАПРАВЛЕНИЕ № п/п ___________</b>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt" align="center">
			на исследование <u><b>крови</b></u>, спинномозговой жидкости на сифилис
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Гр.  <u> <?=mb_ucwords($PatientPersonalData['patid_name']);?> </u>, Дата рождения: <u> <?=$PatientPersonalData['patid_birth'];?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Адрес  <u> <?=$PatientPersonalData['patid_address'];?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Номер страхового полиса:  <u> <?=$PatientPersonalData['patid_insurance_number'];?> </u>, Страховая компания: <u> <?=$CompanyListId['id'.$PatientPersonalData['patid_insurance_company']]['insurance_title'];?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Направившее ЛПУ, отделение  <u> (<?=$LPU_DOCTOR['lpu_code'];?>) <?=$LPU_DOCTOR['lpu_blank_name'];?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Врач <u> <?=$doc_name_sh;?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			РМП  ____________________________________________________________________
			<br>
			ИФА сум G, M  ___________________________________________________________
			<br>
			РПГА  ___________________________________________________________________
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Исследование провёл врач  _________________________________________________
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Дата исследования «‎____» ____________________ 20___ г.
		</td>
	</tr>
</table>