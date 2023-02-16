<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

if ( $USER_PROFILE['access_level'] == 2 )
{
	$CatRM = RecordManipulation($cat_id, CAOP_CAT_SYSTEM, 'cat_id');
	if ( $CatRM['result'] )
	{
	    $CatData = $CatRM['data'];
	    
	    if ( $CatData['cat_auto_renewal'] == 1 )
	    {
	    	$new_state = 0;
		    $new_button = 'вкл.';
		    $new_icon = BT_ICON_CLOSE_LG;
	    } else
	    {
		    $new_state = 1;
		    $new_button = 'выкл.';
		    $new_icon = BT_ICON_OK;
	    }
		
		$autorenewal_values = array(
			'cat_auto_renewal' => $new_state,
		);
		$UpdateCat = updateData(CAOP_CAT_SYSTEM, $autorenewal_values, $CatData, "{$PK[CAOP_CAT_SYSTEM]}='{$CatData[$PK[CAOP_CAT_SYSTEM]]}'");
		if ( $UpdateCat['stat'] == RES_SUCCESS )
		{
			$response['result'] = true;
			$response['button'] = $new_button;
			$response['state'] = $new_state;
			$response['icon'] = $new_icon;
		} else
		{
			$response['msg'] = 'Ошибка обновления при одобрении';
			$response['debug']['$UpdateCat'] = $UpdateCat;
		}
	
	} else $response['msg'] = $CatRM['msg'];
} else $response['msg'] = 'У Вас нет доступа к данному разделу';