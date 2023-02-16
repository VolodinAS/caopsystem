<?php
/*
 * Все дни
 * Эта неделя
 * Этот месяц
 * Этот год
*/
?>

<?php

$queryDays = "SELECT *, COUNT(journal.journal_id) as amount FROM {$CAOP_DAYS} days
                LEFT JOIN {$CAOP_JOURNAL} journal
                    ON days.day_id=journal.journal_day
                WHERE days.day_doctor='{$USER_PROFILE['doctor_id']}'
                GROUP BY days.day_id
                ORDER BY days.day_unix DESC";

//debug($queryDays);

$resultDays = mqc($queryDays);
$DaysData = mr2a($resultDays);

//debug($DaysData);

$TabsData = array(
    'current_week' => array(
        'data'  =>  []
    ),
    'current_month' => array(
        'data'  =>  []
    ),
    'current_year' => array(
        'data'  =>  []
    )
);

//debug($CURRENT_DAY);

foreach ($DaysData as $daysDatum)
{
    $UNIX = intval($daysDatum['day_unix']);
    
    if ( ( $UNIX >= intval($CURRENT_DAY['begins']['week']['unix']) ) && ( $UNIX <= intval($CURRENT_DAY['ends']['week']['unix']) ) )
    {
        $TabsData['current_week']['data'][] = $daysDatum;
    }
    if ( ( $UNIX >= intval($CURRENT_DAY['begins']['month']['unix']) ) && ( $UNIX <= intval($CURRENT_DAY['ends']['month']['unix']) ) )
    {
        $TabsData['current_month']['data'][] = $daysDatum;
    }
    if ( ( $UNIX >= intval($CURRENT_DAY['begins']['year']['unix']) ) && ( $UNIX <= intval($CURRENT_DAY['ends']['year']['unix']) ) )
    {
        $TabsData['current_year']['data'][] = $daysDatum;
    }
}


//debug($TabsData['current_month']);
//exit();
?>

<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#all_days">Все дни (<?=count($DaysData);?>)</a>
	</li>
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#current_week">Эта неделя (<?=count($TabsData['current_week']['data']);?>)</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#current_month">Этот месяц (<?=count($TabsData['current_month']['data']);?>)</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#current_year">Этот год (<?=count($TabsData['current_year']['data']);?>)</a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane fade" id="all_days">
		<?php
		$AllDaysDoctor = $DaysData;
		if ( count($AllDaysDoctor) > 0 )
		{
			include ( "engine/html/alldaytabs_list.php" );
		} else
		{
			bt_notice('К сожалению, нет ни одного дня приёма');
		}
		?>
	</div>
	<div class="tab-pane fade show active" id="current_week">
		<?php
//		+++$AllDaysDoctor = getarr('caop_days', "day_doctor='{$USER_PROFILE['doctor_id']}' AND day_unix>='{$CURRENT_DAY['begins']['week']['unix']}' AND day_unix<='{$CURRENT_DAY['ends']['week']['unix']}'", "ORDER BY day_unix DESC");
		$AllDaysDoctor = $TabsData['current_week']['data'];
		if ( count($AllDaysDoctor) > 0 )
		{
			include ( "engine/html/alldaytabs_list.php" );
		} else
		{
			bt_notice('К сожалению, нет ни одного дня приёма');
		}
		?>
	</div>
	<div class="tab-pane fade" id="current_month">
		<?php
//		$AllDaysDoctor = getarr('caop_days', "day_doctor='{$USER_PROFILE['doctor_id']}' AND day_unix>='{$CURRENT_DAY['begins']['month']['unix']}' AND day_unix<='{$CURRENT_DAY['ends']['month']['unix']}'", "ORDER BY day_unix DESC");
		$AllDaysDoctor = $TabsData['current_month']['data'];
		if ( count($AllDaysDoctor) > 0 )
		{
			include ( "engine/html/alldaytabs_list.php" );
		} else
		{
			bt_notice('К сожалению, нет ни одного дня приёма');
		}
		?>
	</div>
	<div class="tab-pane fade" id="current_year">
		<?php
//		$AllDaysDoctor = getarr('caop_days', "day_doctor='{$USER_PROFILE['doctor_id']}' AND day_unix>='{$CURRENT_DAY['begins']['year']['unix']}' AND day_unix<='{$CURRENT_DAY['ends']['year']['unix']}'", "ORDER BY day_unix DESC");
		$AllDaysDoctor = $TabsData['current_year']['data'];
		if ( count($AllDaysDoctor) > 0 )
		{
			include ( "engine/html/alldaytabs_list.php" );
		} else
		{
			bt_notice('К сожалению, нет ни одного дня приёма');
		}
		?>
	</div>
</div>
<?php
//debug( $CURRENT_DAY );
?>