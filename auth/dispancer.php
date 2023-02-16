<?php
//$DispanserFromJournal = getarr(CAOP_JOURNAL, "journal_disp_isDisp='2' OR journal_disp_isReported='1'", "ORDER BY journal_id DESC");

//debug($_SESSION);

$DISPANCER = $_SESSION['dispancer'];

//bt_notice(wrapper('Установленный период посещений: ') . ' от 01.03.2022', BT_THEME_WARNING);

$date = "2022-03-01";
$unix = strtotime($date);

//$DispanserFromJournal_query = "SELECT * FROM {$CAOP_JOURNAL} cj LEFT JOIN {$CAOP_PATIENTS} cp ON cj.journal_patid=cp.patid_id LEFT JOIN {$CAOP_DAYS} cd ON cj.journal_day=cd.day_id WHERE (journal_disp_isDisp='2' OR journal_disp_isReported='1') AND cd.day_unix>='{$unix}' ORDER BY journal_id DESC";
$DispanserFromJournal_query = "SELECT * FROM {$CAOP_JOURNAL} cj LEFT JOIN {$CAOP_PATIENTS} cp ON cj.journal_patid=cp.patid_id LEFT JOIN {$CAOP_DAYS} cd ON cj.journal_day=cd.day_id WHERE (journal_disp_isDisp='2' OR journal_disp_isReported='1') ORDER BY journal_id DESC";
$DispanserFromJournal_result = mqc($DispanserFromJournal_query);
$DispanserFromJournal = mr2a($DispanserFromJournal_result);

$DispanserFromJournal2 = getarr(CAOP_JOURNAL, "journal_disp_isDisp='2'", "ORDER BY journal_id DESC");
//debug($DispanserFromJournal['request']);
if ( count($DispanserFromJournal) > 0 )
{
	$Patients = [];
    $PatientsDispancer = [];
    $PatientsLiename = [];
	
	foreach ($DispanserFromJournal as $journal_visit)
	{
		$Patients['patient_' . $journal_visit['journal_patid']] [] = $journal_visit;
		
		if ( (int)$journal_visit['journal_disp_isDisp'] == 2 )
		{
			$PatientsDispancer['patient_' . $journal_visit['journal_patid']] [] = $journal_visit;
		} else
		{
			if ( (int)$journal_visit['journal_disp_isReported'] == 1 )
			{
				if ( (int)$journal_visit['journal_disp_isDisp'] == 0 || (int)$journal_visit['journal_disp_isDisp'] == 1 )
				{
					$PatientsLiename['patient_' . $journal_visit['journal_patid']] [] = $journal_visit;
				}
			}
		}
    }
	
}
$PatientsMKB = [];
foreach ($Patients as $patid=>$visits)
{
	$visit0 = end($visits);
//	$visit0 = $visits[0];
//	debug($visit0);
//	exit();
	$Visit = $PatientsMKB[$patid]['mkb_' . $visit0['journal_ds']];
	if ( count($Visit) > 0 )
	{
	    continue;
	} else
	{
		$PatientsMKB[$patid]['mkb_' . $visit0['journal_ds']] = $visit0;
	}
}

$PatientsShow = [];

?>
<div class="row">
    <div class="col">
        <label for="disp_visit_from">Дата посещения ОТ:</label>
        <input type="date" class="form-control" name="disp_visit_from" id="disp_visit_from" value="<?=$DISPANCER['date_from'];?>">
    </div>
    <div class="col">
        <label for="disp_visit_to">Дата посещения ДО:</label>
        <input type="date" class="form-control" name="disp_visit_to" id="disp_visit_to" value="<?=$DISPANCER['date_to'];?>">
    </div>
    <div class="col">
        <label for="disp_doctor_id">Принимавший врач:</label>
        <?php
        $zeroArray = array(
	        'key' => 0,
	        'value' => 'по всем врачам'
        );
        $selectedArray = array(
	        'value' => $DISPANCER['doctor_id'],
        );
        $SelectDoctor = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'disp_doctor_id', ' class="form-control" id="disp_doctor_id"', $zeroArray, $selectedArray);
        echo $SelectDoctor['result'];
        ?>
    </div>
    <div class="col-auto text-center">
        <button type="button" name="disp_apply" id="disp_apply" class="btn btn-primary btn_disp_apply">Применить</button>
        <button type="button" name="disp_reset" id="disp_reset" class="btn btn-warning btn_disp_reset">Сброс</button>
    </div>
</div>
<div class="dropdown-divider"></div>
<?php

foreach ($PatientsMKB as $patid=>$mkbdata)
{
    $is_date_from = false;
    $is_date_to = false;
    $is_doctor = false;
	$visitdata = current($mkbdata);
//	debug($visitdata);
//	exit();
	
	if ( strlen($DISPANCER['date_from']) > 0 )
	{
	    $date_from = strtotime($DISPANCER['date_from']);
	    if ( $visitdata['day_unix'] >= $date_from )
        {
            $is_date_from = true;
        }
	} else
    {
	    $is_date_from = true;
    }
	
	if ( strlen($DISPANCER['date_to']) > 0 )
	{
		$date_to = strtotime($DISPANCER['date_to']);
		if ( $visitdata['day_unix'] <= $date_to )
		{
			$is_date_to = true;
		}
	} else
	{
		$is_date_to = true;
	}
	
	if ( $DISPANCER['doctor_id'] > 0)
	{
		if ( $visitdata['journal_doctor'] == $DISPANCER['doctor_id'] )
		{
			$is_doctor = true;
		}
	} else
	{
		$is_doctor = true;
	}
	
	if ( $is_date_from && $is_date_to && $is_doctor )
    {
	    $PatientsShow[] = $visitdata;
    }
}

//debug('Всего пациентов: ' . count($Patients));
//debug('Из них диспансерных: ' . count($PatientsDispancer));
//debug('Из них самозванцев: ' . count($PatientsLiename));
//debug('Первичный МКБ: ' . count($PatientsMKB));

//foreach ($PatientsDispancer as $patient_ident=>$patient_data)
//{
//	$FindPatient = $PatientsLiename[$patient_ident];
//	if ( count($FindPatient) > 0 )
//	{
//		debug($FindPatient);
//	}
//}
//
//debug($PatientsDispancer);
//debug($PatientsLiename);
//debug($PatientsMKB);

//debug($PatientsShow[0]);
$PatientsShow = array_orderby($PatientsShow, 'day_unix', SORT_DESC);
$AllPatients = $PatientsShow;
$fields = array(
    array(
        'field'         =>  'VAR_NPP',
	    'title'         =>  '#',
	    'onePer'        =>  true,
	    'data_title'    =>  'npp'
    ),
    array(
        'field'         =>  'patid_ident',
	    'title'         =>  'Карта',
	    'onePer'        =>  true,
	    'data_title'    =>  'patid_ident'
    ),
    array(
        'field'         =>  'VAR_FIO',
	    'title'         =>  'Ф.И.О.',
	    'data_title'    =>  'fio'
    ),
    array(
        'field'         =>  'patid_birth',
	    'title'         =>  nbsper('Дата рождения'),
	    'addon'         =>  ' date-format="ddmmyyyy"',
	    'data_title'    =>  'patid_birth',
	    'onePer'        =>  true,
    ),
    array(
        'field'         =>  'day_date',
	    'title'         =>  nbsper('Первый визит'),
	    'data_title'    =>  'day_date',
	    'onePer'        =>  true,
    ),
    array(
        'field'         =>  'VAR_DOCTOR',
	    'title'         =>  nbsper('К врачу'),
	    'data_title'    =>  'doctor',
    ),
    array(
        'field'         =>  'journal_ds',
	    'title'         =>  nbsper('Диагноз МКБ'),
	    'data_title'    =>  'journal_ds',
	    'onePer'        =>  true,
    ),
    array(
	        'field'         =>  'VAR_DISP_TYPE',
	    'title'         =>  nbsper('Тип диспансера'),
	    'data_title'    =>  'disp_or_not',
	    'onePer'        =>  true,
    ),
    array(
        'field'         =>  'VAR_VISITS',
	    'title'         =>  'Посещения',
	    'onePer'        =>  true,
	    'data_title'    =>  'visits'
    )
);
include ( "engine/php/patient_search_gen.php" );

require_once ( "engine/html/modals/visitsPatientData.php" );

?>
<script defer language="JavaScript" type="text/javascript" src="/engine/js/admin/admin_dispancer.js?<?=rand(0,1000000);?>"></script>