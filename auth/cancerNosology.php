<ul class="list-group list-group-horizontal">
    <li class="list-group-item">
        <a href="/cancerList">Основная информация</a>
    </li>
    <li class="list-group-item">
        <a href="/cancerNosology">Нозология</a>
    </li>
</ul>
<button type="button" class="btn btn-sm btn-primary nosologyAll">Распечатать ВСЁ</button>
<button type="button" class="btn btn-sm btn-secondary nosologySelected">Распечатать выбранное</button>
<button type="button" class="btn btn-sm btn-warning nosologyUnselect">Убрать все отметки</button>
<?php
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
                        ORDER BY {$CAOP_CANCER}.cancer_ds ASC";
$resultCancer = mqc($queryCancer);
$CancerData = mr2a($resultCancer);

$CancerNosologyData = [];

foreach ($CancerData as $cancerDatum)
{
	//debug($cancerDatum);
	$mkbData = explode(".", $cancerDatum['cancer_ds']);
	$MAIN_MKB = '';
	if ( count($mkbData) == 2 )
	{
		$MAIN_MKB = $mkbData[0];
	} else $MAIN_MKB = $cancerDatum['cancer_ds'];
	
	$CancerNosologyData[$MAIN_MKB]['data'][] = $cancerDatum;
}
$MAIN_ARRAY = $CancerData;
unset($CancerData);

//debug($CancerNosologyData);

foreach ($CancerNosologyData as $mkbGroup => $mkbData)
{
    $MkbData = getarr(CAOP_MKB, "mkb_code='{$mkbGroup}'");
    if ( count($MkbData) == 1 )
    {
        $MkbData = $MkbData[0];
    }
    ?>
    <div class="row">
    	<div class="col-auto m-3">
    		<input class="form-check-input move-labeler-mkb" type="checkbox" name="labeler_<?=$mkbGroup?>" id="labeler_<?=$mkbGroup?>" value="1" data-mkb="<?=$mkbGroup?>">
    		<label class="form-check-label box-label" for="labeler_<?=$mkbGroup?>"><span></span></label>
    	</div>
    	<div class="col">
		    <?php
			spoiler_begin('[' . count($mkbData['data']) . '] ' . wrapper($mkbGroup) . ' - '.$MkbData['mkb_title'], 'mkb_'.rand(0, 99999));
			?>
			<button type="button" class="btn btn-sm btn-primary nosologyThis" data-nosology="<?=$mkbGroup?>">Распечатать по нозологии <?=wrapper($mkbGroup)?></button>
			<?php
			{

				?>
				<table class="table">
					<tbody>
					<?php

					include ( "engine/html/include/inc_cancerList_table.php" );

					$npp = 1;
					$useHighlight = false;
					foreach ($mkbData['data'] as $mkbDatum)
					{
		//					debug($mkbDatum);
						extract($mkbDatum);
						//extract($mkbDatum['data']);

						include ( "engine/html/include/inc_cancerList_row.php" );
						$npp++;
					}
				?>
					</tbody>
				</table>
				<?php
			}
			spoiler_end();
			?>
		</div>
	</div>
	<?php
}
	
	?>


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