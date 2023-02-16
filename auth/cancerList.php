<?php
//debug( $request_params );

if ( ifound($request_params, "?") )
{
    $queryData = explode("?", $request_params);
//    debug($queryData);
    $request_params = $queryData[0];
}

$queryString = '';
if ( count($_GET) )
{
//	debug($_GET);
    $queryString = '?' . http_build_query($_GET);
//    debug($queryString);
}

$RequestData = explode("/", $request_params);
if (count($RequestData) != 0)
{
	if (count($RequestData) == 2)
	{
		$request_params = $RequestData[0];
		$doctor_id = $RequestData[1];
	} else
	{
		if (count($RequestData) == 1)
		{
			if ($request_params == 'onlyRoute' || $request_params == 'ignoreRoute')
			{
			
			} else
			{
				$doctor_id = $RequestData[0];
			}
		}
	}
}

$active_all = 'active';
$active_route = '';
$active_noroute = '';

//debug($doctor_id);

if ($doctor_id > 0)
{
	$DOCTOR_ID_CONDITION = "WHERE {$CAOP_CANCER}.cancer_doctor_id='{$doctor_id}'";
}
?>



<?php
/*
<option value="NONE">ВЫБЕРИТЕ</option>
<option value="cancer_added_date">По добавлению</option>
<option value="cancer_route_date">По обязательности МЛ</option>
<option value="rs_stage_po_pe_pr_zno_date">По перв. признакам</option>
<option value="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date">По перв. обращению</option>
<option value="rs_stage_caop_date">По обращению в ЦАОП</option>
<option value="rs_ds_set_date">По установлению</option>
<option value="rs_stage_cure_date">По началу спец. лечения</option>
*/

$SortArray = array(
    'cancer_added_date' =>  array(
        'title' =>  'По добавлению',
        'field' =>  'cancer_unix'
    ),
    'cancer_route_date' =>  array(
        'title' =>  'По обязательности МЛ',
        'field' =>  'journal_unix'
    ),
//    'rs_stage_po_pe_pr_zno_date' =>  array(
//        'title' =>  'По перв. признакам',
//        'field' =>  'rs_stage_po_pe_pr_zno_date'
//    ),
//    'rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date' =>  array(
//        'title' =>  'По перв. обращению',
//        'field' =>  'rs_stage_fap_date;rs_stage_vr_u4_pol_date'
//    ),
    'rs_stage_caop_date' =>  array(
        'title' =>  'По обращению в ЦАОП',
        'field' =>  'rs_stage_caop_date'
    ),
    'rs_ds_set_date' =>  array(
        'title' =>  'По установлению',
        'field' =>  'rs_ds_set_date'
    ),
//    'rs_stage_cure_date' =>  array(
//        'title' =>  'По началу спец. лечения',
//        'field' =>  'rs_stage_cure_date'
//    )
);

//
//LEFT JOIN {$CAOP_JOURNAL} journal ON journal.journal_patid=patients.patid_id
//                        WHERE journal.journal_ds LIKE 'C%' OR journal.journal_ds LIKE 'С%'
// LEFT JOIN {$CAOP_DAYS} days ON days.day_id=journal.journal_day
// HAVING MAX(journal.journal_id)
//LEFT JOIN ( SELECT * FROM {$CAOP_JOURNAL} WHERE journal_ds LIKE 'C%' OR journal_ds LIKE 'С%' WHERE journal_patid=patients.patid_id ORDER BY journal_id DESC LIMIT 0,1 ) journal
//                        ON journal.journal_patid=patients.patid_id
//  journal_patid=patients.patid_id AND
// ON journal.journal_patid = ( SELECT * FROM {$CAOP_JOURNAL} WHERE  (journal_ds LIKE 'C%' OR journal_ds LIKE 'С%') ) journal
// LEFT JOIN {$CAOP_JOURNAL} journal

$queryCancer = "SELECT * FROM {$CAOP_CANCER}
                    LEFT JOIN {$CAOP_PATIENTS}
                        ON {$CAOP_CANCER}.cancer_patid={$CAOP_PATIENTS}.patid_id
                    LEFT JOIN {$CAOP_ROUTE_SHEET}
                        ON {$CAOP_ROUTE_SHEET}.rs_patid={$CAOP_PATIENTS}.patid_id
                    LEFT JOIN   (
                                SELECT * FROM {$CAOP_JOURNAL} WHERE (journal_ds LIKE 'C%' OR journal_ds LIKE 'С%') OR (journal_ds LIKE 'D0%')
                                GROUP BY journal_patid
                                HAVING journal_unix = MIN(journal_unix)
                                ) last_c_visit
                        ON {$CAOP_CANCER}.cancer_patid=last_c_visit.journal_patid

                        ORDER BY {$CAOP_PATIENTS}.patid_name ASC";
//
//" . $DOCTOR_ID_CONDITION . "
//
//$queryCancer = "SELECT * FROM {$CAOP_CANCER}
//                    LEFT JOIN {$CAOP_PATIENTS}
//                        ON {$CAOP_CANCER}.cancer_patid={$CAOP_PATIENTS}.patid_id
//                    LEFT JOIN {$CAOP_ROUTE_SHEET}
//                        ON {$CAOP_ROUTE_SHEET}.rs_patid={$CAOP_PATIENTS}.patid_id
//                        " . $DOCTOR_ID_CONDITION . "
//                        ORDER BY {$CAOP_PATIENTS}.patid_name ASC";

//debug($queryCancer);

$resultCancer = mqc($queryCancer);
$CancerData = mr2a($resultCancer);

$ROUTE = 0;
$NOROUTE = 0;

$ROUTE_DATA = array();
$NOROUTE_DATA = array();

//for ($pat=0; $pat<count($CancerData)-1; $pat++)
//{
//	$VisitsData = getarr(CAOP_JOURNAL, "journal_patid='{$CancerData[$pat]['patid_id']}' AND (journal_ds LIKE 'C%' OR journal_ds LIKE 'С%')", "ORDER BY journal_id DESC LIMIT 0,1");
//	if ( count($VisitsData) == 1 )
//	{
//		$VisitData = $VisitsData[0];
//		$CancerData[$pat] = array_merge($CancerData[$pat], $VisitData);
//	}
//}

/*spoiler_begin( 'count($CancerData): ' . count($CancerData), 'count_CancerData' );
debug($CancerData);
spoiler_end();*/
//debug($CancerData);
//exit();
//

$CancerDoctor = array();
if ($doctor_id > 0)
{
	//debug('doctor_id: ' . $doctor_id);
	foreach ($CancerData as $cancerDatum)
	{
		//debug( '$cancerDatum[cancer_doctor_id]:' . $cancerDatum['cancer_doctor_id'] );
		//debug( '$cancerDatum[journal_doctor]:' . $cancerDatum['journal_doctor'] );
		if ( $cancerDatum['cancer_doctor_id'] > 0 )
		{
			if ( intval($cancerDatum['cancer_doctor_id']) == intval($doctor_id) )
			{
				$CancerDoctor[] = $cancerDatum;
			}
		} else
		{
			if ( intval($cancerDatum['journal_doctor']) == intval($doctor_id) )
			{
				$CancerDoctor[] = $cancerDatum;
			}
		}

	}
	$CancerData = $CancerDoctor;
	unset($CancerDoctor);
}

//debug($CancerData);

/*spoiler_begin( 'AFTER {DOCTOR} count($CancerData): ' . count($CancerData), 'count_doctor_CancerData' );
debug($CancerData);
spoiler_end();*/

$CancerDataSorter = array();

if ( count($_SESSION) )
{
	
	if ( isset($_SESSION['cancerList']) )
	{
		if ( !notnull($_SESSION['cancerList']['sortFrom']) && !notnull($_SESSION['cancerList']['sortTo']) )
		{
		} else
		{
			for ($pat=0; $pat<count($CancerData); $pat++)
			{
				$addAuto = false;
				$SortType = $SortArray[$_SESSION['cancerList']['sortType']];
//			debug('sorttype: ' . $SortType['field']);
				if ( notnull($SortType) )
				{
					$from_date_unix = strtotime($_SESSION['cancerList']['sortFrom']);
					$from_date = date("d.m.Y", $from_date_unix);
					
					$to_date_unix = strtotime($_SESSION['cancerList']['sortTo']);
					$to_date = date("d.m.Y", $to_date_unix);
					
					$from_MAIN = $from_date_unix;
					$to_MAIN = $to_date_unix;
					
					$searcherDate = $CancerData[$pat][ $SortType['field'] ];
					
					if ( $SortType['field']=="cancer_unix" || $SortType['field']=="journal_unix" )
					{
					
					} else
					{
						
						if ( $SortType['field'] == 'rs_stage_caop_date' )
						{
							if ( strlen($CancerData[$pat]['rs_stage_caop_date']) > 0 )
							{
								$searcherDate = birthToUnix($CancerData[$pat][ $SortType['field'] ]);
							} else
							{
								if ( strlen($CancerData[$pat]['journal_unix']) > 0 )
								{
									$searcherDate = $CancerData[$pat]['journal_unix'];
								}
							}
						} else
						{
							$searcherDate = birthToUnix($CancerData[$pat][ $SortType['field'] ]);
							if ( strlen($searcherDate) > 0 )
							{
							
							} else $addAuto = true;
						}


//                    exit();
					}
					//debug('TIME CHECKER ' . $pat);
					//debug($CancerData[$pat]);
					//debug($searcherDate);
					
					if ( strlen($from_MAIN) > 0 )
					{
						if ( $searcherDate >= $from_MAIN )
						{
							if ( strlen($to_MAIN) > 0 )
							{
								if ( $searcherDate <= $to_MAIN )
								{
									$CancerDataSorter[] = $CancerData[$pat];
								}
							} else
							{
								$CancerDataSorter[] = $CancerData[$pat];
							}
						}
					}
					if ( strlen($to_MAIN) > 0 )
					{
						if ( $searcherDate <= $to_MAIN )
						{
							$CancerDataSorter[] = $CancerData[$pat];
						}
					}
					
					if ( $addAuto )
					{
						$CancerDataSorter[] = $CancerData[$pat];
					}
					
				}
//			break;
			}
//        exit();
		}
	} else
	{
		$CancerDataSorter = $CancerData;
	}
 
} else
{
	$CancerDataSorter = $CancerData;
}


//debug($CancerDataSorter);
//exit();
$CancerData = $CancerDataSorter;
unset($CancerDataSorter);

/*spoiler_begin( 'AFTER {DOCTOR} {SORTER} count($CancerData): ' . count($CancerData), 'count_doctor_sorter_CancerData' );
debug($CancerData);
spoiler_end();*/

$TOTAL = count($CancerData);


foreach ($CancerData as $cancerDatum)
{
	if (intval($cancerDatum['rs_id']) > 0)
	{
		$ROUTE++;
		$ROUTE_DATA[] = $cancerDatum;
	} else
	{
		$NOROUTE++;
		$NOROUTE_DATA[] = $cancerDatum;
	}
}
$MAIN_ARRAY = $CancerData;
unset($CancerData);

/*spoiler_begin( 'count($ROUTE_DATA): ' . count($ROUTE_DATA), 'count_ROUTE_DATA' );
debug($ROUTE_DATA);
spoiler_end();*/

/*spoiler_begin( 'count($NOROUTE_DATA): ' . count($NOROUTE_DATA), 'count_NOROUTE_DATA' );
debug($NOROUTE_DATA);
spoiler_end();*/

$doctor_link = '';
switch ($request_params)
{
	case "onlyRoute":
		$active_all = '';
		$active_route = 'active';
		$active_noroute = '';
		$MAIN_ARRAY = $ROUTE_DATA;
		unset($ROUTE_DATA);
		$doctor_link = '/' . $request_params;
		break;
	
	case "ignoreRoute":
		$active_all = '';
		$active_route = '';
		$active_noroute = 'active';
		$MAIN_ARRAY = $NOROUTE_DATA;
		unset($NOROUTE_DATA);
		$doctor_link = '/' . $request_params;
		break;
	default:
		$active_all = 'active';
		$active_route = '';
		$active_noroute = '';
		unset($ROUTE_DATA);
		unset($NOROUTE_DATA);
		//$doctor_link = '/' . $request_params;
		break;
}


?>

<ul class="list-group list-group-horizontal">
    <li class="list-group-item">
        <a href="/cancerList">Основная информация</a>
    </li>
    <li class="list-group-item">
        <a href="/cancerNosology">Нозология</a>
    </li>
</ul>

<?php
spoiler_begin('ФИЛЬТР', 'filter');
?>

<div class="container">

    <ul class="list-group list-group-horizontal">

        <li class="list-group-item">
            <a href="/cancerList<?= $doctor_link; ?><?=$queryString;?>" class="font-weight-bolder"><?= nbsper('Все врачи'); ?></a>
        </li>
		
		<?php
		foreach ($DoctorsListId as $doctorData)
		{
			$doctor_active = '';
			if ($doctor_id == $doctorData['doctor_id']) $doctor_active = ' bg-info';
			?>
            <li class="list-group-item<?= $doctor_active; ?>">
                <a href="/cancerList<?= $doctor_link; ?>/<?= $doctorData['doctor_id']; ?><?=$queryString;?>">
					<?= nbsper(docNameShort($doctorData)); ?>
                </a>
            </li>
			<?php
		}
		?>

    </ul>

</div>

<?php
$did = '';
if ($doctor_id > 0)
{
	$did = '/' . $doctor_id;
}
?>

<div class="container text-center">
    <ul class="list-group list-group-horizontal">
        <li class="list-group-item d-flex justify-content-between align-items-center <?= $active_all; ?>">
            <a href="/cancerList<?= $did; ?><?=$queryString;?>">Показать всех</a>
            
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center <?= $active_route; ?>">
            <a href="/cancerList/onlyRoute<?= $did; ?><?=$queryString;?>">Показать пациентов с маршрутным листом</a>
	        <? badge($ROUTE, BT_THEME_PRIMARY, 1); ?>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center <?= $active_noroute; ?>">
            <a href="/cancerList/ignoreRoute<?= $did; ?><?=$queryString;?>">Показать пациентов без маршрутного листа</a>
	        <? badge($NOROUTE, BT_THEME_PRIMARY, 1); ?>
        </li>
    </ul>
</div>
<br>
<div class="container text-center">
    <h5>Выборка по датам (для печати)</h5><br>
    <div class="form-group row">
        <label for="sortField" class="col-sm-2 col-form-label"><b>Сортируемое поле</b></label>
        <div class="col-sm-10">
            <select class="form-control form-control-sm" name="sortField" id="sortField">
                <option value="NONE">ВЫБЕРИТЕ</option>
                <?php
                foreach ($SortArray as $option=>$data)
                {
                    $selected = '';
                    if ( isset($_SESSION['cancerList']) )
                    {
                        if ( notnull($_SESSION['cancerList']['sortType']) )
                        {
                            if ( $option == $_SESSION['cancerList']['sortType'] ) $selected = ' selected';
                        }
                    }
                    echo '<option value="'.$option.'"'.$selected.'>'.$data['title'].'</option>';
                }
                ?>
<!--                <option value="cancer_added_date">По добавлению</option>-->
<!--                <option value="cancer_route_date">По обязательности МЛ</option>-->
<!--                <option value="rs_stage_po_pe_pr_zno_date">По перв. признакам</option>-->
<!--                <option value="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date">По перв. обращению</option>-->
<!--                <option value="rs_stage_caop_date">По обращению в ЦАОП</option>-->
<!--                <option value="rs_ds_set_date">По установлению</option>-->
<!--                <option value="rs_stage_cure_date">По началу спец. лечения</option>-->
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="sortField" class="col-sm-2 col-form-label"><b>Дата ОТ</b></label>
        <div class="col-sm-10">
            <input type="date" name="sortFrom" id="sortFrom" class="form-control form-control-sm" placeholder="Дата начала выборки" value="<?=$_SESSION['cancerList']['sortFrom'];?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="sortField" class="col-sm-2 col-form-label"><b>Дата ДО</b></label>
        <div class="col-sm-10">
            <input type="date" name="sortTo" id="sortTo" class="form-control form-control-sm" placeholder="Дата конца выборки" value="<?=$_SESSION['cancerList']['sortTo'];?>">
        </div>
    </div>
    <div class="form-group row">
        <button class="btn btn-warning btn-sm col" type="button" id="resetSort" data-reset="/cancerList/<?=$queryData[0];?>">Сбросить выборку</button>
        <button class="btn btn-primary btn-sm col" type="button" id="getSort">Показать выборку</button>
        <button class="btn btn-info btn-sm col" type="button" id="letsPrint" data-routeType="<?=$request_params;?>" data-doctor="<?=$doctor_id;?>" data-sortType="<?=$_SESSION['cancerList']['sortType'];?>" data-sortFrom="<?=$_SESSION['cancerList']['sortFrom'];?>" data-sortTo="<?=$_SESSION['cancerList']['sortTo'];?>">Распечатать</button>
    </div>
</div>
<?php
spoiler_end();
//fixtable  ▼
?>
<table class="table cancerList fixtable">
    <?php
    include ( "engine/html/include/inc_cancerList_table.php" );
    ?>
    <tbody>
    
    
    <?php
    //$PatientData = array();
    //foreach ($CancerData as $patientRouteSheets)
    //{
    //    $patientDatum = $PatientData['id' . $patientRouteSheets['patid_id']];
    //    if ( notnull($patientDatum) )
    //    {
    //
    //    } else
    //    {
    //
    //    }
    //}
    
    $unixML = birthToUnix('01.06.2021');
    
    $npp = count($MAIN_ARRAY);
    $rs_warning = '<span ' . super_bootstrap_tooltip('Маршрутный лист не найден') . '>' . BT_ICON_CASE_WARNING . '</span>';
    $useHighlight = true;
    foreach ($MAIN_ARRAY as $cancerDatum)
    {
	    extract($cancerDatum);
	    include ( "engine/html/include/inc_cancerList_row.php" );
        $npp--;
    }
    ?>
    </tbody>
</table>

<?php
require_once("engine/html/modals/viewRouteSheet.php");
require_once("engine/html/modals/editCancerData.php");
require_once("engine/html/modals/visitsPatientData.php");
//require_once("engine/html/modals/routeSheets.php");
?>

<script defer type="text/javascript" src="/engine/js/cancerList.js?<?= rand(0, 999999); ?>"></script>
<script defer language="JavaScript" type="text/javascript"
        src="/engine/js/allpatients.js?<?= rand(0, 1000000); ?>"></script>

<style>
    .active a {
        color: #212529 !important;
    }
</style>