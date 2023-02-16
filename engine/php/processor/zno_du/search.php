<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$response['result'] = true;

$response['htmlData'] = '';

if ( strlen($patid_ident) == 0 && strlen($patid_name) == 0 )
{
	$response['htmlData'] .= bt_notice('Не введены критерии поиска', BT_THEME_WARNING, 1);
} else
{
	
	$patname = name_for_query($patid_name);

//	$response['htmlData'] .= debug_ret($patname);
	
	$SearchPatientByNameData = getarr(CAOP_PATIENTS, "patid_name LIKE '{$patname['querySearchPercentSpaces']}'", "ORDER BY patid_name ASC");
	if ( count($SearchPatientByNameData) == 0 )
	{
		$response['htmlData'] .= bt_notice('Пациента с таким именем нет', BT_THEME_WARNING, 1);
	} else
	{
		if ( count($SearchPatientByNameData) > 50 )
		{
			$response['htmlData'] .= bt_notice('По указанному имени найдено более 50 результатов. Сузьте критерии поиска!', BT_THEME_WARNING, 1);
		} else
		{
//			$response['htmlData'] .= debug_ret($SearchPatientByNameData);
			
			$AllPatients = $SearchPatientByNameData;
			
			$response['htmlData'] .= '<table class="table table-sm allpatients">
        <thead>
        <tr>
            <th scope="col" class="font-weight-bolder full-center" width="1%">#</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">Карта</th>
            <th scope="col" class="font-weight-bolder full-center">Ф.И.О.</th>
            <th scope="col" class="font-weight-bolder full-center" date-format="ddmmyyyy">Дата рождения</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">Назначить</th>
        </tr>
        </thead>
        <tbody>';
			
			
			$npp = 1;
			foreach ($AllPatients as $Patient)
			{
				
				$response['htmlData'] .= '<tr class="highlighter">
					<td class="font-weight-bolder align-center">'.$npp.')</td>
					<td class="align-center">'.$Patient['patid_ident'].'</td>
					<td>'.mb_ucwords($Patient['patid_name']).'</td>
					<td class="align-center">'.$Patient['patid_birth'].'</td>
					<td class="align-center">
						<button class="btn btn-success btn-sm btn-znoduPatient" data-patidid="'.$Patient['patid_id'].'" data-patidname="'.mb_ucwords($Patient['patid_name']).'" data-patidbirth="'.$Patient['patid_birth'].'">
							Назначить
						</button>
					</td>
				</tr>';
				
				$npp++;
			}
			
			
			$response['htmlData'] .= '</tbody>
    	</table>';
			
			
		}
	}
//	$response['htmlData'] .= debug_ret($SearchPatientByNameData);

}