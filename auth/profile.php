
<?php

bt_notice('Надоело постоянное переавторизовываться? ' . wrapper('<a href="/quick">Добавьте эту страницу в закладки, чтобы входить в приём по одному клику!</a>'));

$CURRENT_YEAR = strtotime(date("Y", time())."-01-01");
//$CURRENT_YEAR = -10000000000;
//1 - находим самый первый приём
$FirstVisit = getarr(CAOP_DAYS, "day_doctor='{$USER_PROFILE['doctor_id']}' AND day_unix>='{$CURRENT_YEAR}'", "ORDER BY day_unix ASC LIMIT 1", false, 'day_id, day_unix');
if ( count($FirstVisit) > 0 )
{
	$FirstVisitDay = $FirstVisit[0];
//	debug($FirstVisit);
	$LastVisit = getarr(CAOP_DAYS, "day_doctor='{$USER_PROFILE['doctor_id']}'", "ORDER BY day_unix DESC LIMIT 1", 1, 'day_id, day_unix');
//	debug($LastVisit, '$LastVisit');
	if ( count($LastVisit) > 0 )
	{
		$LastVisitDay = $LastVisit[0];
		
		$FirstDayData = getCurrentDay($FirstVisitDay['day_unix']);
		$LastDayData = getCurrentDay($LastVisitDay['day_unix']);
		
//		debug($LastVisit, '$LastVisit');
//		debug($LastDay, '$LastDay');
//		debug($FirstDayData, '$FirstDayData');
//		debug($LastDayData, '$LastDayData');
		
		echo '<h5>Статистика приёмов пациентов по месяцам с <u>ПЕРВЫЙ</u> по <u>ПОСЛЕДНЕЕ</u> числа</h5>';
		for ($year=intval($FirstDayData['year']); $year<=intval($LastDayData['year']); $year++)
		{
			$YearUnix = strtotime($year . '-01-01');
			$YearData = getCurrentDay($YearUnix);
//				    debug($YearData);
			?>
            <table width="100%" class="tbc" border="1" cellpadding="5">
                <tr>
                    <td colspan="12"><?=$year?></td>
                </tr>
                <tr>
					<?php
					for ($month=0; $month<count($MonthsRusShort); $month++)
					{
						echo '<td align="center">'.$MonthsRusShort[$month].'</td>';
					}
					?>
                </tr>
                <tr>
					<?php
					for ($month=0; $month<count($MonthsRusShort); $month++)
					{
						$mm = $month+1;
						$DateString = $year . '-' . $mm . '-01';
						$DateStringUnix = strtotime($DateString);
						$MonthData = getCurrentDay($DateStringUnix);
						$MonthUnixBegin = $MonthData['by_timestamp']['begins']['month']['unix'];
						$MonthUnixEnd = $MonthData['by_timestamp']['ends']['month']['unix'];
						
						$queryAmount = "SELECT COUNT(journal_id) as SumVisits FROM {$CAOP_JOURNAL} WHERE journal_day IN (SELECT day_id FROM {$CAOP_DAYS} WHERE day_unix>='{$MonthUnixBegin}' AND day_unix<='{$MonthUnixEnd}' AND day_doctor='{$USER_PROFILE['doctor_id']}')";
						$resultAmount = mqc($queryAmount);
						$AmountMonth = mr2a($resultAmount)[0];
						echo '<td align="center">'.$AmountMonth['SumVisits'].'</td>';
					}
					?>
                </tr>
            </table>
			<?php
		}
		
		
		
		
		
		
		echo '<br><h5>Статистика приёмов пациентов по месяцам с <u>21-го числа</u> предыдущего по <u>20-ое число</u> указанного</h5>';
		for ($year=intval($FirstDayData['year']); $year<=intval($LastDayData['year']); $year++)
		{
			$YearUnix = strtotime($year . '-01-01');
			$YearData = getCurrentDay($YearUnix);
//				    debug($YearData);
			?>
            <table width="100%" class="tbc" border="1" cellpadding="5">
                <tr>
                    <td colspan="12"><?=$year?></td>
                </tr>
                <tr>
					<?php
					for ($month=0; $month<count($MonthsRusShort); $month++)
					{
						echo '<td align="center">'.$MonthsRusShort[$month].'</td>';
					}
					?>
                </tr>
                <tr>
					<?php
					for ($month=0; $month<count($MonthsRusShort); $month++)
					{
						$dbg = array();
						$mm = $month+1;
						$DateStringCurrent = $year . '-' . $mm . '-21';
						$DateStringCurrentUnix = strtotime($DateStringCurrent);
						
						$dbg['$DateStringCurrentUnix'] = $DateStringCurrentUnix;
						
						$DateStringPrevUnix = strtotime("-1 month", $DateStringCurrentUnix);
						
						$dbg['$DateStringPrevUnix'] = $DateStringPrevUnix;
						
						$MonthUnixBegin = $DateStringPrevUnix;
						$MonthUnixEnd = $DateStringCurrentUnix;
						
						$queryAmount = "SELECT COUNT(journal_id) as SumVisits FROM {$CAOP_JOURNAL} WHERE journal_day IN (SELECT day_id FROM {$CAOP_DAYS} WHERE day_unix>='{$MonthUnixBegin}' AND day_unix<='{$MonthUnixEnd}' AND day_doctor='{$USER_PROFILE['doctor_id']}')";
						$resultAmount = mqc($queryAmount);
						$AmountMonth = mr2a($resultAmount)[0];

//								debug($dbg);
//								exit();
						echo '<td align="center">'.$AmountMonth['SumVisits'].'</td>';
					}
					?>
                </tr>
            </table>
			<?php
		}
		
	}
}
?>