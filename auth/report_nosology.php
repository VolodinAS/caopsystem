<?php
$nosology_caop_date_from = $nosology_caop_date_to = '';
$nosology_ds_date_from = $nosology_ds_date_to = '';
if (isset($_SESSION['nosology_report']))
{
	$nosology_caop_date_from = $_SESSION['nosology_report']['caop']['date_from'];
	$nosology_caop_date_to = $_SESSION['nosology_report']['caop']['date_to'];
	
	$nosology_ds_date_from = $_SESSION['nosology_report']['ds']['date_from'];
	$nosology_ds_date_to = $_SESSION['nosology_report']['ds']['date_to'];
	
	$nosology_caop_doctor_id = $_SESSION['nosology_report']['caop']['doctor_id'];
	$nosology_ds_doctor_id = $_SESSION['nosology_report']['ds']['doctor_id'];
	
	$nosology_caop_diagnosis = $_SESSION['nosology_report']['caop']['diagnosis'];
	$nosology_ds_diagnosis = $_SESSION['nosology_report']['ds']['diagnosis'];
}
?>

    <br>
<? spoiler_begin('Нозология по приёмам ЦАОП', 'nosology_caop'); ?>
    <form action=""
          method="post"
          id="nosology_report_caop">
        <div class="row">
            <div class="col font-weight-bolder">
                Выберите сроки:
            </div>

            <div class="col p-1">
                <input type="date"
                       class="form-control form-control-lg"
                       id="nosology_report_caop_from"
                       name="nosology_report_caop_from"
                       value="<?= $nosology_caop_date_from; ?>">
            </div>

            <div class="col">
                <input type="date"
                       class="form-control form-control-lg"
                       id="nosology_report_caop_to"
                       name="nosology_report_caop_to"
                       value="<?= $nosology_caop_date_to; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col font-weight-bolder">
                Выберите врача:
            </div>
            <div class="col p-1">
				<?php
				$selectArr = array(
					'value' => 'ПО ВСЕМ ВРАЧАМ'
				);
				$defaultArr = array(
					'key' => 0,
					'value' => 'ПО ВСЕМ ВРАЧАМ'
				);
				$SelectDoctor = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'nosology_report_caop_doctor_id', 'id="nosology_report_caop_doctor_id" class="form-control form-control-lg"', $defaultArr, $selectArr);
				echo $SelectDoctor['result'];
				?>
            </div>
        </div>
        <div class="row">
            <div class="col-2 font-weight-bolder">
                Впишите искомые диагнозы:
            </div>
            <div class="col p-1">
                <input type="text"
                       class="form-control form-control-lg"
                       id="nosology_report_caop_diagnosis"
                       name="nosology_report_caop_diagnosis"
                       value="<?= $nosology_caop_diagnosis; ?>">
            </div>
            <div class="col-2">
                <a href="javascript:openPatterns()" <?= super_bootstrap_tooltip('Нужен для выборки определенных диагнозов'); ?>>Паттерны
                                                                                                                                диагнозов</a>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col">
                <button type="button"
                        class="btn btn-primary btn-lg btn-nosology-report-caop">Создать отчёт
                </button>
            </div>
        </div>
    </form>
<? spoiler_end(); ?>
    <br>
<? spoiler_begin('Нозология по дневному стационару', 'nosology_ds'); ?>
    <form action=""
          method="post"
          id="nosology_report_ds">
        <div class="row">
            <div class="col font-weight-bolder">
                Выберите сроки:
            </div>

            <div class="col p-1">
                <input type="date"
                       class="form-control form-control-lg"
                       id="nosology_report_ds_from"
                       name="nosology_report_ds_from"
                       value="<?= $nosology_ds_date_from; ?>">
            </div>

            <div class="col">
                <input type="date"
                       class="form-control form-control-lg"
                       id="nosology_report_ds_to"
                       name="nosology_report_ds_to"
                       value="<?= $nosology_ds_date_to; ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="col-2 font-weight-bolder">
                Впишите искомые диагнозы:
            </div>
            <div class="col p-1">
                <input type="text"
                       class="form-control form-control-lg"
                       id="nosology_report_ds_diagnosis"
                       name="nosology_report_ds_diagnosis"
                       value="<?= $nosology_ds_diagnosis; ?>">
            </div>
            <div class="col-2">
                <a href="javascript:openPatterns()" <?= super_bootstrap_tooltip('Нужен для выборки определенных диагнозов'); ?>>Паттерны
                                                                                                                                диагнозов</a>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col">
                <button type="button"
                        class="btn btn-primary btn-lg btn-nosology-report-ds">Создать отчёт
                </button>
            </div>
        </div>
    </form>
<? spoiler_end(); ?>

    <script defer
            src="/engine/js/nosology_report.js?<?= rand(0, 999999); ?>"
            type="text/javascript"></script>

<?php
require_once("engine/html/modals/dgPatternsList.php");
?>