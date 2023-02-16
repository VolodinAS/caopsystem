<?php
// TODO Чат
//  Текстовые сообщения
//  Отправка по Enter
//  Прикрепление файлов
//  Файлы должны быть оставаться заркпленными за доктором ДО отправки
//  Файлы сохраняются под чатом ДО перезагрузки страницы
//  Если есть закрепленные файлы и отправляется сообщение - в чат отправляется сообщение + прикрепление
//  Ограничение на файл - 20 МБ
//  Текстовое поле наверху, рядом кнопка "Прикрепить файл" и "Отправить" - промежуточное сохранение в сессию?...
//  Под полем - возможные прикрепления ► требуется новая таблица для временных прикреплений
//  Каждый файл есть возможность удалить
//  autosize для текстового поля
//  при загрузке страницы - инициация #files для проверки наличия ранее закрепленных файлов ► БД со списком файлов, от кого и отправлено в чате или нет
?>

<div class="row">
	<div class="col">
		<textarea class="form-control form-control-lg autosizer" name="chat" id="chat" cols="30" rows="3" onkeydown="SendMessage(event)"></textarea>
	</div>
	<div class="col-auto">
		<button type="button" class="btn btn-sm btn-primary" id="btn-file">Файл</button>
	</div>
	<div class="col-auto">
		<button type="button" class="btn btn-sm btn-success" id="btn-send">Отправить</button>
	</div>
</div>
<div class="row">
	<div class="col">
		<div id="files" class="small">
			Загрузка файлов...
		</div>
	</div>
</div>
<div class="dropdown-divider"></div>
<div class="row">
	<div class="col">
		<div id="messages">
			Загрузка чата...
		</div>
	</div>
</div>

<script defer type="text/javascript" src="/engine/js/chat.js?<?=rand(0, 999999);?>"></script>