<?php
$ModalSettings = array(
	'modal_name' => 'search4add',
	'modal_header' => 'Поиск пациента для приёма',
	'modal_width' => 'xl',
	'modal_cancel_button' => true,
	'modal_loader_default' => false
);

$modal_cancel_button = '';
if ($ModalSettings['modal_cancel_button']) $modal_cancel_button = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>';

$modal_loader_default = '';
if ($ModalSettings['modal_loader_default']) $modal_loader_default = '<div class="input-group input-group-sm"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'

?>
<div class="modal fade" id="<?= $ModalSettings['modal_name']; ?>" tabindex="-1" role="dialog"
     aria-labelledby="<?= $ModalSettings['modal_name']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-<?= $ModalSettings['modal_width']; ?>" role="document">
        <div class="modal-content">
            <div class="modal-header" id="<?= $ModalSettings['modal_name']; ?>_header">
                <h5 class="modal-title"
                    id="<?= $ModalSettings['modal_name']; ?>_header_string"><?= $ModalSettings['modal_header']; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="<?= $ModalSettings['modal_name']; ?>_body">
				<?= spoiler_begin(wrapper('Как пользоваться данным поиском?'), 'how_use_it'); ?>
                Уважаемые врачи и медсёстры!<br><br>
                Данный поиск позволит исключить появление в системе карт-дубликатов.<br>
                Чтобы найти пациента, вы можете:<br>
                1 - Ввести номер карты пациента<br>
                2 - Ввести фамилию, имя, отчество пациента<br>
                3 - Ввести ID пациента (системная информация)<br><br>
                Чтобы найти по ФИО, Вам НЕ ОБЯЗАТЕЛЬНО вводить ФИО полностью. К примеру, если к Вам на прием повторно
                пришла пациентка <b>абдухаирова кашифа мухтаровна</b>, то не обязательно ее ФИО вводить полностью.
                Достаточно будет в поиске ввести <b>абд ка м</b> - то есть, только первые несколько букв от ФИО.
                <br>
                Также, вы можете просто ввести первые несколько букв фамилии (например, <b>абду</b>) - система найдет
                все совпадения и Вы
                сможете выбрать нужно Вам пациента <br><br>
                При поиске по номеру карты вы можете воспользоваться точным совпадением, полностью введя номер карты
                (например, <b>243331</b>) или, так называемым, относительным поиском, окружив искомую информацию знаками
                процента (например, <b>%4333%</b>).
                <br>
                В первом случае, система будет искать точное совпадение по номеру карты, во втором - будут отображаться
                все пациенты, в чьих номерах карт встречаются числа "4333"
                <br><br>
                Если Вы уверены, что пациента никогда не было на приёмах, вы можете сразу нажать "Создать карту", чтобы
                добавить пациента в список, однако <b>НАСТОЯТЕЛЬНО</b> рекомендую <b>ВСЕГДА</b> сначала проверять
                наличие карты по Фамилии Имени Отчеству пациента. И если его не окажется в списке - только тогда нажать
                "Создать карту".
                <br><br>
                Спасибо за понимание!<br><br>Ваш разработчик и коллега, Вол Алек Серг
				<?= spoiler_end(); ?>
				<?= $modal_loader_default; ?>
				<?php
				// body
				?>

                <form action="" id="search4add_form">
                    <div class="form-group row">
                        <div class="col">
                            <input type="text" class="form-control form-control-lg" id="searchString"
                                   placeholder="Номер карты ИЛИ ФИО пациента: петров ив вал">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-lg" id="search4add_button">Поиск</button>
                        </div>
                        <div class="col-auto">
                            <b>ИЛИ</b>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-warning btn-lg" id="netPat_go">Создать карту</button>
                        </div>
                    </div>

                </form>
                <div id="<?= $ModalSettings['modal_name']; ?>_result">
                </div>
            </div>
            <div class="modal-footer" id="<?= $ModalSettings['modal_name']; ?>_footer">
				<?php
				// buttons
				?>
				<?= $modal_cancel_button; ?>
            </div>
        </div>
    </div>
</div>