<?php
$day_id = $Today_Array['day_id'];

?>

<? if ($Today_Array['day_signature_state'] == 1): ?>
	<?php
//debug($DoctorsListId);
	$Doctor = $DoctorsListId['id' . $Today_Array['day_signature_doctor_id']];
	$doc_name = mb_ucwords($Doctor['doctor_f'] . ' ' . $Doctor['doctor_i'] . ' ' . $Doctor['doctor_o']);
	bt_notice('<b>ЖУРНАЛ ПОДПИСАН</b>. <u>Дата подписания:</u> ' . date("d.m.Y H:i", $Today_Array['day_signature_unix']) . ', <u>врач:</u> ' . $doc_name, "danger");
	?>
<? endif; ?>

<?php

if ($page == "journalAlldays" || $page=="journalAlldaysDoctors"):
	?>
	<button type="button" class="btn btn-warning" id="button_journal_refresh">Обновить</button>
	<!--    <button type="button" class="btn btn-secondary" id="button_journal_print" data-dayid="--><?//=$day_id;
	?><!--">Распечатать</button>-->

	<div class="dropdown div-inline">
		<button class="btn btn-secondary dropdown-toggle" type="button" id="printer" data-toggle="dropdown"
		        aria-haspopup="true" aria-expanded="false">
			Распечатать
		</button>
		<div class="dropdown-menu" aria-labelledby="printer">
			<a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="stattalones">Реестр для статотдела</a>
			<a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="journalday">Журнал приёма</a>
			<a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="dispanser">Диспансерные пациенты</a>
            <a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="dirs">Направленные пациенты</a>
			<!--            <div class="dropdown-divider"></div>-->
			<!--            <a class="dropdown-item button_journal_print" href="#" data-dayid="--><?//=$day_id;
			?><!--" data-type="journal">Лист приёма</a>-->
		</div>
	</div>

	<? if ($Today_Array['day_signature_state'] == 0): ?>
	<div class="dropdown div-inline">
		<button class="btn btn-secondary dropdown-toggle" type="button" id="mappControl" data-toggle="dropdown"
		        aria-haspopup="true" aria-expanded="false">
			Управление журналом
		</button>
		<div class="dropdown-menu" aria-labelledby="mappControl">
            <a class="dropdown-item" href="javascript:checkingAll()"><b>Выделить всех пациентов</b></a>
            <div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:journalSignature(<?= $day_id; ?>)">Подписать журнал</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:moveLabeler()">Перенести отмеченных</a>
			<a class="dropdown-item" href="javascript:moveAll()">Перенести всех пациентов</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:moveDoctorLabeler()">Передать отмеченных другому врачу</a>
			<a class="dropdown-item" href="javascript:moveDoctorAll()">Передать всех пациентов другому врачу</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:deleteDay(<?= $day_id; ?>)">Удалить день</a>
		</div>
	</div>
<? endif; ?>
<?php
endif;
?>
<?php
if ($page == "journalCurrent" || $page == "appointment"):
	?>
	<button type="button" class="btn btn-warning" id="button_journal_refresh">Обновить</button>
<!--	<button type="button" class="btn btn-primary" id="button_journal_newPat">Добавить</button>-->
	<button type="button" class="btn btn-primary" id="button_journal_search4add">Добавить</button>
	<!--    <button type="button" class="btn btn-secondary" id="button_journal_print" data-dayid="--><?//=$day_id;
	?><!--">Распечатать</button>-->

	<div class="dropdown div-inline">
		<button class="btn btn-success dropdown-toggle" type="button" id="printer" data-toggle="dropdown"
		        aria-haspopup="true" aria-expanded="false">
			Распечатать
		</button>
		<div class="dropdown-menu" aria-labelledby="printer">
			<a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="stattalones">Реестр для статотдела</a>
			<a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="journalday">Журнал приёма</a>
            <a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="dispanser">Диспансерные пациенты</a>
            <a class="dropdown-item button_journal_print" href="#" data-dayid="<?= $day_id; ?>" data-type="dirs">Направленные пациенты</a>
			<!--            <div class="dropdown-divider"></div>-->
			<!--            <a class="dropdown-item button_journal_print" href="#" data-dayid="--><?//=$day_id;
			?><!--" data-type="journal">Лист приёма</a>-->
		</div>
	</div>
	<? if ($Today_Array['day_signature_state'] == 0): ?>
	<div class="dropdown div-inline">
		<button class="btn btn-secondary dropdown-toggle" type="button" id="mappControl" data-toggle="dropdown"
		        aria-haspopup="true" aria-expanded="false">
			Управление
		</button>
		<div class="dropdown-menu" aria-labelledby="mappControl">
            <a class="dropdown-item" href="javascript:checkingAll()"><b>Выделить всех пациентов</b></a>
            <div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:clearList()">Очистить журнал</a>
			<a class="dropdown-item" href="javascript:deleteDay(<?= $day_id; ?>)">Удалить день</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:moveLabeler()">Перенести отмеченных</a>
			<a class="dropdown-item" href="javascript:deleteLabeler()">Удалить отмеченных</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:moveAll()">Перенести всех пациентов</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="javascript:moveDoctorLabeler()">Передать отмеченных другому врачу</a>
			<a class="dropdown-item" href="javascript:moveDoctorAll()">Передать всех пациентов другому врачу</a>
			<div class="dropdown-divider"></div>
<!--			<a class="dropdown-item" href="javascript:miasImport()">Импорт из МИАС</a>-->
			<a class="dropdown-item" href="javascript:miasImportMain()">Импорт из МИАС</a>
<!--            javascript:miasImport2()-->
		</div>
	</div>
    
    <div class="dropdown div-inline">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="mappNurse" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Выбрать медсестру
        </button>
        <div class="dropdown-menu" aria-labelledby="mappNurse">
            <?php
            $MyNurseId = $Today_Array['day_nurse'];
            foreach ($DoctorsNurseId as $nurse)
            {
            	$active = '';
				if ( $nurse['doctor_id'] == $MyNurseId ) $active = " active";
                echo '<a class="dropdown-item'.$active.'" href="javascript:setNurseToday('.$nurse['doctor_id'].')">[#'.$nurse['doctor_id'].'] '.docNameShort($nurse, "famio").'</a>';
            }
            ?>
        </div>
    </div>
    
    <!--<div class="dropdown div-inline">
        <button class="btn btn-danger dropdown-toggle" type="button" id="menuChecks" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Проверки
        </button>
        <div class="dropdown-menu" aria-labelledby="menuChecks">
            <a class="dropdown-item journal-check-all">Все проверки</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item journal-check-data" data-day="<?/*=$Today_Array['day_id'];*/?>" data-doctor="<?/*=$CHOSEN_DOCTOR_ID;*/?>">Проверить данные</a>
            <a class="dropdown-item journal-check-moves" data-day="<?/*=$Today_Array['day_id'];*/?>" data-doctor="<?/*=$CHOSEN_DOCTOR_ID;*/?>">Проверить переносы</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item journal-move-all" data-day="<?/*=$Today_Array['day_id'];*/?>" data-doctor="<?/*=$CHOSEN_DOCTOR_ID;*/?>">Осуществить все переносы</a>
        </div>
    </div>-->
<? endif; ?>
<?php
endif;
?>

