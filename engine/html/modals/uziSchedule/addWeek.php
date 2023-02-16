<?php
$ModalSettings = array(
	'modal_name' => 'graphicAddWeek',
	'modal_header' => 'Добавить в график 1 неделю приёма',
	'modal_width' => CODE_MD, /*РАЗМЕР*/
	'modal_cancel_button' => true, /*ПОКАЗАТЬ КНОПКУ ЗАКРЫТИЯ?*/
	'modal_loader_default' => false /*ПОКАЗАТЬ ИКОНКУ ЗАГРУЗКИ? (ДЛЯ ДИНАМИЧЕСКИХ ОКОН)*/
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
                <h3 class="modal-title"
                    id="<?= $ModalSettings['modal_name']; ?>_header_string"><?= $ModalSettings['modal_header']; ?></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="<?= $ModalSettings['modal_name']; ?>_body">
				<?php
//                debug($UziDoctor);
				$TempsData = getarr(CAOP_SCHEDULE_UZI_DATES_SHIFTS_TEMP, "temp_uzi_id='{$UziDoctor['uzi_id']}'", "ORDER BY temp_title ASC");
				if ( count($TempsData) > 0 )
				{
					$a2sDefault = array(
						'key' => 0,
						'value' => 'Выберите...'
					);
					$a2sSelector = array2select($TempsData, 'temp_id', 'temp_title', 'temp_id',
						'class="form-control" id="temp"', $a2sDefault);
				} else
				{
					bt_notice('Перед тем, как добавить расписание на неделю, создайте шаблон');
				}
				
				
				?>
                <form id="<?= $ModalSettings['modal_name']; ?>_form">
                    <input type="hidden" name="uzi_doctor_id" value="<?=$UziDoctor['uzi_doctor_id'];?>">
                    <div class="form-group">
                        <label for="temp"><b>Выберите шаблон для добавления недели:</b></label>
						<?=$a2sSelector['result'];?>
                    </div>
                    <div class="form-group">
                        <label ><b>С какого дня добавить неделю:</b></label>
<!--                        <div>-->
<!--                            <input class="form-check-input" type="radio" name="first_add" id="first_monday" value="closer" checked>-->
<!--                            <label class="form-check-label box-label" for="first_monday"><span></span>с ближайшей незанятой недели</label>-->
<!--                        </div>-->
                        <div>
                            <input class="form-check-input " type="radio" name="first_add" id="current_date" value="by_date" checked>
                            <label class="form-check-label box-label" for="current_date"><span></span>с указанной даты (выберите Понедельник)</label>
                        </div>
                        <div class="form-group" id="choose_date">
                            <input type="date" class="form-control" name="dater" id="dater">
<!--                            <br>-->
                            <input class="form-check-input " type="checkbox" name="overwrite" id="overwrite" value="1" >
                            <label class="form-check-label box-label" for="overwrite"><span></span>перезаписать смены, если таковые будут</label>
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="<?= $ModalSettings['modal_name']; ?>_footer">
                <button class="btn btn-primary btn-add1Week">Добавить</button>
				<?php
				// buttons
				?>
				<?= $modal_cancel_button; ?>
            </div>
        </div>
    </div>
</div>