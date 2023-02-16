<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$SEARCH_RESULTS = array();

$query_searchByID = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_id LIKE '{$finder}' ORDER BY patid_name ASC";
$response['debug']['$query_searchByID'] = $query_searchByID;
$result_searchByID = mqc($query_searchByID);
$amount_searchByID = mnr($result_searchByID);

$SEARCH_RESULTS = array_merge($SEARCH_RESULTS, mr2a($result_searchByID));

$query_searchByCARD = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_ident LIKE '{$finder}' ORDER BY patid_name ASC";
$response['debug']['$query_searchByCARD'] = $query_searchByCARD;
$result_searchByCARD = mqc($query_searchByCARD);
$amount_searchByCARD = mnr($result_searchByCARD);

$SEARCH_RESULTS = array_merge($SEARCH_RESULTS, mr2a($result_searchByCARD));

$finderData = explode(" ", $finder);

$finderQuery = '';
foreach ($finderData as $finderDatum)
{
	if ( $finderQuery == '' )
	{
		$finderQuery = '%' . $finderDatum . '%';
	} else
	{
		$finderQuery .= ' ' . '%' . $finderDatum . '%';
	}
	
}

$query_searchByNAME = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_name LIKE '{$finderQuery}' ORDER BY patid_name ASC";
$response['debug']['$query_searchByNAME'] = $query_searchByNAME;
$result_searchByNAME = mqc($query_searchByNAME);
$amount_searchByNAME = mnr($result_searchByNAME);

$SEARCH_RESULTS = array_merge($SEARCH_RESULTS, mr2a($result_searchByNAME));

$response['debug']['$SEARCH_RESULTS'] = $SEARCH_RESULTS;

if ( count($SEARCH_RESULTS) > 0 )
{
	$response['result'] = true;
	$response['htmlData'] .= bt_notice('Найдено пациентов: '.wrapper( count($SEARCH_RESULTS) ), BT_THEME_SUCCESS, 1);
	
	$response['htmlData'] .= '
	<table class="table table-sm">
		<thead>
			<tr>
				<th width="1%" scope="col" class="text-center font-weight-bolder">ID</th>
				<th width="1%" scope="col" class="text-center font-weight-bolder">Карта</th>
				<th scope="col" class="text-center font-weight-bolder">Ф.И.О.</th>
				<th width="1%" scope="col" class="text-center font-weight-bolder">Дата рождения</th>
				<th scope="col" class="text-center font-weight-bolder">Адрес</th>
				<th width="1%" scope="col" class="text-center font-weight-bolder">Номер полиса ОМС</th>
				<th width="1%" scope="col" class="text-center font-weight-bolder">Добавить</th>
			</tr>
		</thead>
		<tbody>';
	
	foreach ($SEARCH_RESULTS as $patientData)
	{
		$patName = $patientData['patid_name'];
//		$charset = mb_detect_encoding($patName);
//		$patName = iconv($charset, UTF8, $patName);
		
//		$patName = str_ireplace($finder, '<span class="bg-warning">'.$finder.'</span>', $patName );
		
		$patName = mb_ucwords($patName);
		
		$response['htmlData'] .= '
		<tr>
			<td>
				'.$patientData['patid_id'].'
			</td>
			<td>
				'.$patientData['patid_ident'].'
			</td>
			<td>
				'.$patName.'
			</td>
			<td>
				'.$patientData['patid_birth'].'
			</td>
			<td>
				'.$patientData['patid_address'].'
			</td>
			<td>
				'.$patientData['patid_insurance_number'].'
			</td>
			<td>
				<button type="button" class="btn btn-primary btn-sm" onclick="javascript:addInVisit('.$patientData['patid_id'].')">Добавить</button>
			</td>
		</tr>
		';
	}
	
	$response['htmlData'] .= '</tbody>
	</table>';
} else
{
	$response['result'] = true;
	$response['htmlData'] .= bt_notice('Пациентов с заданными параметрами поиска не найдено', BT_THEME_WARNING, 1);
}