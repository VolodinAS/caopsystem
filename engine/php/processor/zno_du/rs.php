<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');
$response['result'] = true;
if ( $patient_id > 0 )
{
	$RouteSheets = getarr(CAOP_ROUTE_SHEET, "rs_patid='{$patient_id}'");
	if ( count($RouteSheets) > 0 )
	{
		$response['htmlData'] .= '<table class="table table-sm allpatients">
        <thead>
        <tr>
            <th scope="col" class="font-weight-bolder full-center" width="1%">#</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">МКБ</th>
            <th scope="col" class="font-weight-bolder full-center">Диагноз</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">префикс</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">T</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">N</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">M</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">G</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">Стадия</th>
            <th scope="col" class="font-weight-bolder full-center" date-format="ddmmyyyy">Дата установки</th>
            <th scope="col" class="font-weight-bolder full-center">Врач</th>
            <th scope="col" class="font-weight-bolder full-center" width="1%">Импортировать</th>
        </tr>
        </thead>
        <tbody>';
		
		
		$npp = 1;
		foreach ($RouteSheets as $route)
		{
			$mbk_an = mkbAnalizer($route['rs_ds_mkb']);
			$docname = nbsper(docNameShort($DoctorsListId['id' . $route['rs_doctor_id']]));
//			$response['htmlData'] .= debug_ret($route);
			$response['htmlData'] .= '<tr class="highlighter">
					<td class="font-weight-bolder align-center">'.$npp.')</td>
					<td class="align-center">'.badge($route['rs_ds_mkb'], mkbAnalizer($route['rs_ds_mkb']), false, 1).'</td>
					<td>'.$route['rs_ds_text'].'</td>
					<td class="align-center">'.$route['rs_tnm_prefix'].'</td>
					<td class="align-center">'.$route['rs_tnm_t'].'</td>
					<td class="align-center">'.$route['rs_tnm_n'].'</td>
					<td class="align-center">'.$route['rs_tnm_m'].'</td>
					<td class="align-center">'.$route['rs_tnm_g'].'</td>
					<td class="align-center">'.$route['rs_stadia'].'</td>
					<td class="align-center">'.$route['rs_ds_set_date'].'</td>
					<td class="align-center">'.$docname.'</td>
					
					<td class="align-center">
						<button class="btn btn-success btn-sm btn-importRS"
							data-rs="'.$route['rs_id'].'"
						>
							Импорт
						</button>
					</td>
				</tr>';
			
			$npp++;
		}
		
		
		$response['htmlData'] .= '</tbody>
    	</table>';
	} else $response['htmlData'] .= bt_notice('У пациента нет маршрутных листов', BT_THEME_WARNING, 1);
	
} else $response['htmlData'] .= bt_notice('Не указан ID пациента', BT_THEME_WARNING, 1);

