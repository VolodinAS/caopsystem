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
	    
	    if ( $CatData['cat_approved'] == 1 )
	    {
	    	$new_approve = 0;
	    	$new_button = 'Одобрить';
	    	$new_icon = BT_ICON_CLOSE_CIRCLE;
	    } else
	    {
	    	$new_approve = 1;
	    	$new_button = 'Запретить';
	    	$new_icon = BT_ICON_CASE_ALLGOOD;
	    }
	    
	    $unix = time();
	    
	    $approve_values = array(
	        'cat_approved' => $new_approve,
		    'cat_approved_date_unix' => $unix
	    );
	    $UpdateCat = updateData(CAOP_CAT_SYSTEM, $approve_values, $CatData, "{$PK[CAOP_CAT_SYSTEM]}='{$CatData[$PK[CAOP_CAT_SYSTEM]]}'");
	    if ( $UpdateCat['stat'] == RES_SUCCESS )
	    {
	    	$response['result'] = true;
	    	$response['button'] = $new_button;
	    	$response['icon'] = $new_icon;
	    	$response['unix'] = $unix;
	    	$response['date'] = super_bootstrap_tooltip(date(DMYHIS, $unix));
	    } else
	    {
	    	$response['msg'] = 'Ошибка обновления при одобрении';
	    	$response['debug']['$UpdateCat'] = $UpdateCat;
	    }
	
	} else $response['msg'] = $CatRM['msg'];
	
} else $response['msg'] = 'У Вас нет доступа к данному разделу';