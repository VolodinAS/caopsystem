<div class="size-16pt header boldy">
	Извещение о больном 1A клинической группы<br>
	(заполняется на больного с подозрением на ЗНО)
</div>

<br>

<div class="size-10pt">
	<div>
		<b>Адрес и название учреждения, в котором заполнено извещение:</b> <?=$NoticePrint['f1a_lpu_from'];?>
	</div>
	
	<br>
	
	<div>
		<b>Извещение направлено в:</b> <?=$NoticePrint['f1a_lpu_to'];?>
	</div>
	
	<br>
	
	<div>
		<b>Ф.И.О. пациента:</b> <?=mb_ucwords($PatientData['patid_name']);?>
	</div>
	
	<br>
	
	<div>
		<b>Дата рождения:</b> <?=$PatientData['patid_birth'];?>
	</div>
	
	<br>
	
	<div>
		<b>Домашний адрес:</b> <?=$PatientData['patid_address'];?>
	</div>
	
	<br>
	
	<div>
		<b>Диагноз:</b> [<?=$NoticePrint['f1a_dg_mkb'];?>] <?=$NoticePrint['f1a_dg_text'];?>
	</div>
	
	<br>
	
	<div>
		<b>Причина, не позволившая окончательно установить диагноз:</b> <?=$NoticePrint['f1a_reason'];?>
	</div>
	
	<br>
	
	<div>
		<b>Рекомендации:</b> <?=$NoticePrint['f1a_recom'];?>
	</div>
	
	<br>
	
	<div class="align-right">
		<div>
			<b>Дата заполнения извещения:</b> <?=$NoticePrint['f1a_date_create'];?>
		</div>
		
		<br>
		
		<div>
			<b>Ф.И.О. врача и подпись:</b> (<?=$DoctorData['doctor_miac_login'];?>) <?= docNameShort($DoctorData, 'famimot') ;?>
		</div>
	</div>
</div>



<?php
//debug($NoticePrint);
//debug($PatientData);
//debug($DoctorData);
?>