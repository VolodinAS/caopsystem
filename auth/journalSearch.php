<?php

$queryMinUnix = "SELECT MIN(day_unix) as unix_from FROM {$CAOP_DAYS}";
$resultMinUnix = mqc($queryMinUnix);
$MinUnixData = mr2a($resultMinUnix);
$visits_unix_from = $MinUnixData[0]['unix_from'];
$visits_from = date('d.m.Y', $visits_unix_from);

$queryMaxUnix = "SELECT MAX(day_unix) as unix_to FROM {$CAOP_DAYS}";
$resultMaxUnix = mqc($queryMaxUnix);
$MaxUnixData = mr2a($resultMaxUnix);
$visits_unix_to = intval($MaxUnixData[0]['unix_to']) + 86399;
$visits_to = date('d.m.Y', $visits_unix_to);

if (strlen($request_params) > 0)
{
	$RequestParamsData = explode('/', $request_params);
//	debug($RequestParamsData);
	switch ($RequestParamsData[0])
	{
		case "card":
			$_POST['search_by_card'] = $RequestParamsData[1];
			break;
	}
}

if (count($_POST) > 0)
{
//    debug($_POST);
	
	
	$birth2unix_from = ($_POST['search_by_birth_from']) ? birthToUnix($_POST['search_by_birth_from']) : '';
	$birth2unix_to = ($_POST['search_by_birth_to']) ? birthToUnix($_POST['search_by_birth_to']) : '';
	
//	debug($birth2unix_to);
//
	if (strlen($birth2unix_to) > 0)
	{
		$birth2unix_to += (TIME_DAY - 1);
	}
//
//	debug($birth2unix_to);
	
	$visit2unix_from = ($_POST['search_by_visit_from']) ? birthToUnix($_POST['search_by_visit_from']) : '';
	$visit2unix_to = ($_POST['search_by_visit_to']) ? birthToUnix($_POST['search_by_visit_to']) : '';
	if (strlen($visit2unix_from) > 0)
	{
		$visit2unix_to += (TIME_DAY - 1);
	}


//    debug('$birth2unix_from: ' . $birth2unix_from);
//    debug('$birth2unix_to: ' . $birth2unix_to);
//
//    debug('$visit2unix_from: ' . $visit2unix_from);
//    debug('$visit2unix_to: ' . $visit2unix_to);
	
	$checked = ($_POST['onlyfrom'] == 1) ? ' checked' : '';
	
	if ($_POST['onlyfrom'] == 1)
	{
		$queryBirth = "AND patid_birth_unix='{$birth2unix_from}'";
	} else
	{
		$queryBirth = '';
		$queryBirthFrom = '';
		$queryBirthTo = '';
		if (strlen($birth2unix_from) > 0) $queryBirthFrom = "(patid_birth_unix>='{$birth2unix_from}')";
		if (strlen($birth2unix_to) > 0) $queryBirthTo = "(patid_birth_unix<='{$birth2unix_to}')";
		if (strlen($queryBirthFrom)) $queryBirth .= " AND {$queryBirthFrom}";
		if (strlen($queryBirthTo)) $queryBirth .= " AND {$queryBirthTo}";
	}
	
	if (strlen($_POST['search_by_name']) > 0)
	{
		$pat_name = name_for_query($_POST['search_by_name']);
//        debug($pat_name);
//        $queryName = "AND (patid_name LIKE '{$pat_name['querySearchPercent']}')";
		$pat_name2 = $_POST['search_by_name'];
		$pat_name2 = str_replace(' ', '% %', $pat_name2);
		$queryName = " AND (patid_name LIKE '%{$pat_name2}%')";
	}
	
	if (strlen($_POST['search_by_result']) > 0)
	{
		$queryByresult = " AND (cj.journal_recom LIKE '%{$_POST['search_by_result']}%')";
	}
	
	$queryDoctor = '';
	if (strlen($_POST['search_by_doctor']) > 0)
	{
		if ($_POST['search_by_doctor'] > 0)
		{
			$queryDoctor = " day_doctor='{$_POST['search_by_doctor']}' AND ";
		}
	}

//	$visitQuery = '';
	$visitQueryFrom = '';
	$visitQueryTo = '';
	if (strlen($_POST['search_by_visit_from']) > 0)
	{
		$visits_unix_from_form = birthToUnix($_POST['search_by_visit_from']);
		$visitDataFrom = getCurrentDay($visits_unix_from_form);
		$visitQueryFrom = " AND ( journal_day IN (SELECT day_id FROM {$CAOP_DAYS} WHERE {$queryDoctor} day_unix>='{$visitDataFrom['by_timestamp']['begins']['day']['unix']}'[PLACE_FOR_TO])  )";
	} else
	{
	
	}
	if (strlen($_POST['search_by_visit_to']) > 0)
	{
		$visits_unix_to_form = birthToUnix($_POST['search_by_visit_to']);
		$visitDataTo = getCurrentDay($visits_unix_to_form);
		$visitQueryTo = " AND day_unix<='{$visitDataTo['by_timestamp']['ends']['day']['unix']}'";
		$visitQueryFrom = str_replace('[PLACE_FOR_TO]', $visitQueryTo, $visitQueryFrom);
	} else
	{
		$visitQueryFrom = str_replace('[PLACE_FOR_TO]', '', $visitQueryFrom);
	}
	
	$queryMKB = "";
	if (strlen($_POST['search_by_mkb']) > 0)
	{
		$mkbString = nospaces($_POST['search_by_mkb']);
		$mkbString = strtoupper($mkbString);
		
		$queryMKB = getQueryByPattern($mkbString, 'cj.journal_ds');
		
		$queryMKB = " AND ($queryMKB) ";
//	    debug($queryMKB);
	}
	
	
	$queryCard = (strlen($_POST['search_by_card'])) ? " AND (patid_ident='{$_POST['search_by_card']}')" : '';

//    $querySearch = "SELECT * FROM {$CAOP_PATIENTS} WHERE 1 {$queryCard} {$queryName} {$queryBirth} ORDER BY patid_name ASC";
	$querySearch = "SELECT *, COUNT(cj.journal_id) as visits FROM {$CAOP_PATIENTS} cp LEFT JOIN {$CAOP_JOURNAL} cj ON cp.patid_id=cj.journal_patid WHERE 1 {$queryCard} {$queryName} {$queryBirth} {$queryByresult} {$queryMKB} {$visitQueryFrom} GROUP BY cp.patid_id ORDER BY cp.patid_name ASC";
//    debug($pat_name);
//    debug($querySearch);
//    exit();
	
	
	if (ifound($USER_PROFILE['doctor_access'], "2"))
	{
		spoiler_begin('Строка запроса $querySearch', 'querySearch');
		{
			debug($querySearch);
		}
		spoiler_end();
	}
	
	$resultSearch = mqc($querySearch);
	$amountSearch = mnr($resultSearch);
	if ($amountSearch > 0)
	{
		$go_output = true;
	}
	
}

$search_by_visit_from = (!$_POST['search_by_visit_from']) ? $visits_from : $_POST['search_by_visit_from'];
$search_by_visit_to = (!$_POST['search_by_visit_to']) ? $visits_to : $_POST['search_by_visit_to'];
?>

<form action="/journalSearch"
      method="post">
    <div class="form-group row">
        <label for="search_by_card"
               class="col-sm-3 col-form-label">Номер карты:</label>
        <div class="col-sm-9">
            <input type="text"
                   class="form-control"
                   id="search_by_card"
                   name="search_by_card"
                   placeholder="номер карты"
                   value="<?= $_POST['search_by_card']; ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="search_by_name"
               class="col-sm-3 col-form-label">Ф.И.О.:</label>
        <div class="col-sm-9">
            <input type="text"
                   class="form-control"
                   id="search_by_name"
                   name="search_by_name"
                   placeholder="фамилия и о"
                   value="<?= $_POST['search_by_name']; ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="search_by_birth_from"
               class="col-sm-3 col-form-label">Дата рождения:</label>
        <div class="col-sm-2">
            <input type="text"
                   class="form-control russianBirth"
                   id="search_by_birth_from"
                   name="search_by_birth_from"
                   placeholder="дата от"
                   value="<?= $_POST['search_by_birth_from']; ?>">
        </div>
        <div class="col-sm-1 align-center">
            -
        </div>
        <div class="col-sm-2">
            <input type="text"
                   class="form-control russianBirth"
                   id="search_by_birth_to"
                   name="search_by_birth_to"
                   placeholder="дата до"
                   value="<?= $_POST['search_by_birth_to']; ?>">
        </div>
        <div class="col-sm-4">
            <input class="form-check-input move-labeler"
                   type="checkbox"
                   name="onlyfrom"
                   id="onlyfrom"
                   value="1"<?= $checked; ?>>
            <label class="form-check-label box-label"
                   for="onlyfrom"><span></span><b>Искать по точной дате</b></label>
        </div>
    </div>

    <div class="form-group row">
        <label for="search_by_result"
               class="col-sm-3 col-form-label">Исход случая:</label>
        <div class="col-sm-9">
            <input type="text"
                   class="form-control"
                   id="search_by_result"
                   name="search_by_result"
                   placeholder="охо, сокод и т.д."
                   value="<?= $_POST['search_by_result']; ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="search_by_mkb"
               class="col-sm-3 col-form-label">По диагнозу:</label>
        <div class="col-sm-7">
            <input type="text"
                   class="form-control"
                   id="search_by_mkb"
                   name="search_by_mkb"
                   placeholder="Впишите диагнозы, можно несколько через точку с запятой: D24;N60;D48.5"
                   value="<?= $_POST['search_by_mkb']; ?>">
        </div>
        <div class="col-sm-2 align-center">
            <a href="javascript:openPatterns()" <?= super_bootstrap_tooltip('Нужен для выборки определенных диагнозов'); ?>>Паттерны
                                                                                                                            диагнозов</a>
        </div>
    </div>

    <div class="form-group row">
        <label for="search_by_visit_from"
               class="col-sm-3 col-form-label">Период посещения:</label>
        <div class="col-sm-2">
            <input type="text"
                   class="form-control russianBirth"
                   id="search_by_visit_from"
                   name="search_by_visit_from"
                   placeholder="дата от"
                   value="<?= $search_by_visit_from; ?>">
        </div>
        <div class="col-sm-1 align-center">
            -
        </div>
        <div class="col-sm-2">
            <input type="text"
                   class="form-control russianBirth"
                   id="search_by_visit_to"
                   name="search_by_visit_to"
                   placeholder="дата до"
                   value="<?= $search_by_visit_to; ?>">
        </div>

    </div>


    <div class="form-group row">
        <label for="search_by_doctor"
               class="col-sm-3 col-form-label">Врач:</label>
        <div class="col-sm-9">
			<?php
			$defaultArr = array(
				'key' => '0',
				'value' => 'ВЫБЕРИТЕ ПРИ НЕОБХОДИМОСТИ'
			);
			$searchArr = array(
				'value' => $_POST['search_by_doctor']
			);
			//        debug($searchArr);
			$SelectDoctor = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'search_by_doctor', 'id="search_by_doctor" class="form-control"', $defaultArr, $searchArr);
			echo $SelectDoctor['result']
			?>
        </div>
    </div>

    <div class="form-group row">
        <div class="col">
			<?= bt_notice('Внимание! Данный инструмент поиска находится на стадии разработки, данные могут быть не точными', BT_THEME_WARNING) ?>
        </div>
    </div>

    <div class="form-group row">
        <button type="submit"
                class="btn btn-primary col-12">Искать
        </button>
    </div>

</form>

<?php
if ($go_output)
{
	bt_notice('Найдено <b>' . $amountSearch . '</b> ' . pluralForm($amountSearch, 'совпадение', 'совпадения', 'совпадений'));
	$AllPatients = mr2a($resultSearch);
	$imFromSearch = true;
	require_once("engine/php/patient_search.php");
}

include ("engine/html/modals/uziSchedule/modal_uziList.php");
include ("engine/html/modals/uziSchedule/modal_uziTimeEditor.php");
require_once("engine/html/modals/dgPatternsList.php");
require_once("engine/html/modals/visitsPatientData.php");
?>
<script>
    $(document).ready(function () {
        $(".allpatients").mark("<?=$_POST['search_by_name'];?>");
    });
</script>
<script defer
        language="JavaScript"
        type="text/javascript"
        src="/engine/js/allpatients.js?<?= rand(0, 1000000); ?>"></script>
