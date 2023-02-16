<?php
//$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
//$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');
?>

<table style="border-collapse: collapse" width="100%">
	<tr>
		<td style="font-size: 6pt">
			<br>
			Штамп учреждения, направившего биоматериал
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
			на исследование крови на наличие антител к ВИЧ
		</td>
	</tr>

	<tr>
		<td style="font-size: 10pt">
			Регистрационный номер анализа  _____________________________________________
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Ф.И.О. полностью: <u> <?=mb_ucwords($PatientPersonalData['patid_name']);?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Пол ___________________ Дата рождения <u> <?=$PatientPersonalData['patid_birth'];?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Домашний адрес  <u> <?=$PatientPersonalData['patid_address'];?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Номер страхового полиса:  <u> <?=$PatientPersonalData['patid_insurance_number'];?> </u>, Страховая компания: <u> <?=$CompanyListId['id'.$PatientPersonalData['patid_insurance_company']]['insurance_title'];?> </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Место работы, учёбы  <u> пенсионер </u>
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Дата взятия крови  ___________________________________________________________
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			Результат исследования  ______________________________________________________
			___________________________________________________________________________
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			<br>
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt" align="center">
			<u> онколог, <?=$doc_name_sh;?> </u> _______________________ М.П.
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt" align="center">
			(должность, фамилия, подпись лица, направившего материал на исследование)
		</td>
	</tr>
	<tr>
		<td style="font-size: 10pt">
			<?=date("d.m.Y г.")?>
		</td>
	</tr>
	<tr>
		<td style="font-size: 6pt">
			(указывается дата оформления направления)
		</td>
	</tr>
</table>