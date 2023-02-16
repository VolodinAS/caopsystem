<?php
$VisitsAmount = $Patient['visits'];
if ( gettype($VisitsAmount) != "integer")
{
	//$Visits = getarr(CAOP_JOURNAL, "journal_patid='{$Patient['patid_id']}'");
	//$VisitsAmount = count($Visits);
}

$patient_name = editPersonalDataLink( shorty($Patient['patid_name'], "famimot"), $Patient['patid_id'], super_bootstrap_tooltip( mb_ucwords($Patient['patid_name']) ) );
if ( $imFromSearch )
{
	$patient_name = editPersonalDataLink( mb_ucwords($Patient['patid_name']), $Patient['patid_id'] );
}

$Cancer = getarr(CAOP_CANCER, "cancer_patid='{$Patient['patid_id']}'", "ORDER BY cancer_order_number ASC");
$cancer_image = '';
if ( count($Cancer) > 0 )
{
	$cancer_image = '<img src="/engine/images/icons/cancer.png" /><div style="display: none">1</div>';
}

$openVisits = 'открыть';
if ( strlen($VisitsAmount) > 0 )
{
    $openVisits = "открыть ({$VisitsAmount})";
}

$dead = patient_dead($Patient);
$mark = patient_mark($Patient);


?>
	<tr class="highlighter" data-case="<?=$activeTab;?>" id="tr_patid_<?=$activeTab;?>_<?=$Patient['patid_id'];?>">
		<td class="font-weight-bolder align-center"><?=$npp;?>)</td>
		<td class="align-center"><?=$Patient['patid_ident'];?></td>
		<td class="align-center"><?=$cancer_image;?></td>
		<td><?=$dead;?><?=$mark;?><?=$patient_name?></td>
		<td class="align-center"><?=$Patient['patid_birth'];?></td>
		<td class="align-center"><?=$Patient['patid_phone'];?></td>
		<td class="align-center font-weight-bolder">
			<button class="btn btn-secondary btn-sm" onclick="javascript:allVisits('<?=$Patient['patid_id'];?>')"><?=$openVisits;?></button>
		</td>
	</tr>
<?php
$npp++;
?>