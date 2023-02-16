<br>
<form action="/report_dir_all" method="post">
	<div class="form-group row">
		<div class="col"><b>Выберите период отчётности:</b> от</div>
		<div class="col"><input type="date" class="form-control form-control-lg" id="report_dir_date_from" name="report_dir_date_from" value="<?=$_POST['report_dir_date_from']?>" required></div>
		<div class="col">до</div>
		<div class="col"><input type="date" class="form-control form-control-lg" id="report_dir_date_to" name="report_dir_date_to" value="<?=$_POST['report_dir_date_to']?>" required></div>
	</div>
	<br>
	<div class="form-group row">
		<button type="submit" class="btn btn-primary col-12">Результат</button>
	</div>
</form>

<?php

$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');

$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');

$ResearchTypesIdReport = $ResearchTypesId;
$CitologyTypesIdReport = $CitologyTypesId;
//debug($CitologyTypesIdReport);


if ( count($_POST) > 0 )
{

	$unix_from = strtotime($_POST['report_dir_date_from']);
	$unix_to = strtotime($_POST['report_dir_date_to']);

	// НАХОДИМ ОБСЛЕДОВАНИЯ
	$queryResearch = "SELECT * FROM {$CAOP_RESEARCH} WHERE research_unix>='{$unix_from}' AND research_unix<='{$unix_to}'";
	$resultResearch = mqc($queryResearch);
	$amountResearch = mnr($resultResearch);
	$ResearchesData = mr2a($resultResearch);
//		debug($ResearchesData);
	$ResearchSummary = 0;
	for ($i=0; $i<count($ResearchesData); $i++)
	{
		$RData = $ResearchesData[$i];
		if ( array_key_exists('id'.$RData['research_type'], $ResearchTypesIdReport) )
		{
			if ( array_key_exists('amount', $ResearchTypesIdReport['id'.$RData['research_type']]) )
			{
				$ResearchTypesIdReport['id'.$RData['research_type']]['amount'] += 1;
				$ResearchSummary += 1;
			} else
			{
				$ResearchTypesIdReport['id'.$RData['research_type']]['amount'] = 1;
				$ResearchSummary += 1;
			}
		}

	}

	// НАХОДИМ ЦИТОЛОГИЮ
	$queryCitology = "SELECT * FROM {$CAOP_CITOLOGY} WHERE citology_dir_date_unix>='{$unix_from}' AND citology_dir_date_unix<='{$unix_to}'";
	$resultCitology = mqc($queryCitology);
	$amountCitology = mnr($resultCitology);
	$CitologysData = mr2a($resultCitology);
	$CitologySummary = 0;
	for ($i=0; $i<count($CitologysData); $i++)
	{
		$CData = $CitologysData[$i];
		if ( array_key_exists('id'.$CData['citology_analise_type'], $CitologyTypesIdReport) )
		{
			if ( array_key_exists('amount', $CitologyTypesIdReport['id'.$CData['citology_analise_type']]) )
			{
				$CitologyTypesIdReport['id'.$CData['citology_analise_type']]['amount'] += 1;
				$CitologySummary += 1;
			} else
			{
				$CitologyTypesIdReport['id'.$CData['citology_analise_type']]['amount'] = 1;
				$CitologySummary += 1;
			}
		}

	}
	?>
	<hr>
	<div class="print-selected" id="print-selected">
		<div class="row">
			<div class="col full-center font-weight-bolder size-20pt">
				<?=$LPU_DOCTOR['lpu_blank_name'];?>
            </div>
        </div>
        <div class="row">
            <div class="col full-center font-weight-bolder size-10pt">
				<?=$LPU_DOCTOR['lpu_lpu_address'];?>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col full-center font-weight-bolder size-16pt">
				ОТЧЁТ
			</div>
		</div>
		<div class="row">
			<div class="col full-center font-weight-bolder size-16pt">
				по направленным пациентам ВСЕМИ врачами<br>
				в период с <u> <?=date("d.m.Y", $unix_from)?> </u> по <u> <?=date("d.m.Y", $unix_to)?> </u>
			</div>
		</div>
		<br>
		<hr>
		<div class="row">
			<div class="col border-right">
				<b>ОБСЛЕДОВАНИЯ (всего: <?=$ResearchSummary?>):</b><br><br>
				<?php
				foreach ($ResearchTypesIdReport as $ReportData) {
					if ( $ReportData['amount'] > 0 )
					{
						echo '<b> - '.$ReportData['type_title'].'</b> - '.$ReportData['amount'].' чел.<br>';
					}
				}
				?>
			</div>

			<div class="col">
				<b>ЦИТОЛОГИЯ (всего: <?=$amountCitology?>):</b><br><br>
				<?php
				foreach ($CitologyTypesIdReport as $CitologyData) {
					if ( $CitologyData['amount'] > 0 )
					{
						echo '<b> - '.$CitologyData['type_title'].'</b> - '.$CitologyData['amount'].' чел.<br>';
					}
				}
				?>
			</div>
		</div>


	</div>
	<?php
}
?>


<script defer language="JavaScript" type="text/javascript" src="/engine/js/report_dir.js?<?=rand(0,1000000)?>"></script>