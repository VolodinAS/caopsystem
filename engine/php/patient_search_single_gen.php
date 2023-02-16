<?php
$imFromSearch = true;
$patient_name = editPersonalDataLink( shorty($Patient['patid_name']), $Patient['patid_id'], super_bootstrap_tooltip( mb_ucwords($Patient['patid_name']) ) );
if ( $imFromSearch )
{
	$patient_name = editPersonalDataLink( mb_ucwords($Patient['patid_name']), $Patient['patid_id'] );
}

$dispancer_or_not = '';
if ( $Patient['journal_disp_isDisp'] == 2 )
{
    $dispancer_or_not = 'Диспансерный';
} else
{
	if ( $Patient['journal_disp_isDisp'] == 1 )
    {
        $dispancer_or_not = 'Самотёком';
    } else
    {
	    if ( $Patient['journal_disp_isDisp'] == 0 )
	    {
		    $dispancer_or_not = nbsper('<b>не указано</b>');
	    }
    }
}

$doctor_name = nbsper( docNameShort( $DoctorsListId['id' . $Patient['journal_doctor']] ) );

?>
<tr class="highlighter" data-case="<?=$activeTab;?>" id="tr_patid_<?=$activeTab;?>_<?=$Patient['patid_id'];?>">
	<?php
	foreach ($fields as $field)
	{
		if ( $field['field'] == "VAR_NPP" )
		{
			echo '<td class="font-weight-bolder align-center" data-cell="'.$field['data_title'].'">'.$npp .')</td>';
		} else
		if ( $field['field'] == "VAR_FIO" )
		{
			echo '<td data-cell="'.$field['data_title'].'">'.$patient_name.'</td>';
		} else
		if ( $field['field'] == "VAR_DISP_TYPE" )
		{
			echo '<td data-cell="'.$field['data_title'].'">'.$dispancer_or_not.'</td>';
		} else
		if ( $field['field'] == "VAR_DOCTOR" )
		{
			echo '<td data-cell="'.$field['data_title'].'">'.$doctor_name.'</td>';
		} else
		if ( $field['field'] == "VAR_VISITS" )
		{
			echo '
			<td class="align-center font-weight-bolder" data-cell="'.$field['data_title'].'">
				<button class="btn btn-secondary btn-sm" onclick="javascript:allVisits(\''.$Patient['patid_id'].'\')">открыть</button>
			</td>';
		} else
		{
			echo '<td class="align-center" data-cell="'.$field['data_title'].'">'.$Patient[ $field['field'] ].'</td>';
		}
		
	}
	?>
	<!--
	<td class="font-weight-bolder align-center"><?=$npp;?>)</td>
	<td class="align-center"><?=$Patient['patid_ident'];?></td>
	<td><?=$patient_name?></td>
	<td class="align-center"><?=$Patient['patid_birth'];?></td>
	
	<td class="align-center font-weight-bolder">
		<button class="btn btn-secondary btn-sm" onclick="javascript:allVisits('<?=$Patient['patid_id'];?>')">открыть</button>
	</td>
	-->
</tr>
<?php
$npp++;
?>