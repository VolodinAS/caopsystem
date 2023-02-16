<?php

$q_AllDS = "SELECT COUNT(*) AS amount FROM {$CAOP_DS_PATIENTS} WHERE 1";
$r_AllDS = mqc($q_AllDS);
$AllDS = mfa($r_AllDS);


$DOSE_MEASURE_TYPES_DEFAULT = $DOSE_PERIOD_TYPES_DEFAULT = $DOSE_FREQ_PERIOD_TYPES_DEFAULT = array(
    'key'   =>  '0',
    'value' =>  'выбрать'
);

$RegimensList = getarr(CAOP_DS_REGIMENS, 1, "ORDER BY regimen_title ASC");
$RegimenSelectOptions = '<option value="0">шаблонов нет</option>';
if ( count($RegimensList) > 0 )
{
	foreach ($RegimensList as $regimenData)
	{
        if ( ifound($RegimenSelectOptions, 'шаблонов нет') )
        {
	        $RegimenSelectOptions = '<option value="0">выберите шаблон...</option>';
	        $RegimenSelectOptions .= '<option value="'.$regimenData['regimen_id'].'">'.$regimenData['regimen_title'].' ('.$regimenData['regimen_drug'].')</option>';
        } else
        {
	        $RegimenSelectOptions .= '<option value="'.$regimenData['regimen_id'].'">'.$regimenData['regimen_title'].' ('.$regimenData['regimen_drug'].')</option>';
        }
    }
}

if ($Title['request_params'] == '') $calendar_active = ' active';
else
{
    $PageTypeData = explode("/", $Title['request_params'], 2);
    $PageType = $PageTypeData[0];
    $active_var = $PageType . '_active';
    $$active_var = ' active';
    
    if ( count($PageTypeData) > 0 )
    {
        $OtherParams = $PageTypeData[1];
    }
}

$padding_class = " p-2";
?>

<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link<?=$calendar_active;?><?=$padding_class;?>" href="/dayStac">Календарь</a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?=$newPatient_active;?><?=$padding_class;?>" href="/dayStac/newPatient">Добавить/Редактировать</a>
    </li>
    <!--<li class="nav-item">
        <a class="nav-link<?/*=$search_active;*/?><?/*=$padding_class;*/?>" href="/dayStac/search">Поиск</a>
    </li>-->
    <li class="nav-item">
        <a class="nav-link<?=$patientsList_active;?><?=$padding_class;?>" href="/dayStac/patientsList">Список (<?=$AllDS['amount'];?>)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?=$regimens_active;?><?=$padding_class;?>" href="/dayStac/regimens">Схемы лечения</a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?=$measures_active;?><?=$padding_class;?>" href="/dayStac/measures">Единицы измерения</a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?=$statistic_active;?><?=$padding_class;?>" href="/dayStac/statistic">Статистика</a>
    </li>
    <!--<li class="nav-item">
        <a class="nav-link<?/*=$concept_active;*/?><?/*=$padding_class;*/?>" href="/dayStac/concept">Концепт</a>
    </li>-->
</ul>

<div id="main_content">
<?php
switch ($PageType)
{
    case "calendar":
    default:
        require_once ( "engine/html/dayStac/ds_page_calendar.php" );
    break;
    case "newPatient":
        require_once ( "engine/html/dayStac/ds_page_newPatient.php" );
    break;
    case "search":
        require_once ( "engine/html/dayStac/ds_page_search.php" );
    break;
    case "patientsList":
        require_once ( "engine/html/dayStac/ds_page_patientsList.php" );
    break;
    case "regimens":
        require_once ( "engine/html/dayStac/ds_page_regimens.php" );
    break;
    case "measures":
        require_once ( "engine/html/dayStac/ds_page_measures.php" );
    break;
    case "statistic":
        require_once ( "engine/html/dayStac/ds_page_statistic.php" );
    break;
    case "concept":
        require_once ( "engine/html/dayStac/ds_page_concept.php" );
    break;
}
?>
</div>

<?php
require_once ( "engine/html/modals/openDayCalendar.php" );
require_once ( "engine/html/modals/openDayPatient.php" );
require_once ( "engine/html/modals/openVisitPatient.php" );
?>

<script defer type="text/javascript">
var VISREG_TEMPLATE = <?php echo json_encode($RegimensList) ?>//;// don't use quotes
</script>

<script defer type="text/javascript" src="/engine/js/dayStac/dayStac_main.js?<?=rand(0, 999999);;?>"></script>
