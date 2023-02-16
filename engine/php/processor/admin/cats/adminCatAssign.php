<?php
$response['stage'] = $action;
$catid = $_POST['catid'];

if ( $USER_PROFILE['access_level'] == 2 )
{
	
	$Cat = getarr(CAOP_CAT_SYSTEM, "cat_id='{$catid}'");
	
	if (count($Cat) == 1)
	{
		$Cat = $Cat[0];
		$updateCat = array(
			'cat_approved' => '1',
			'cat_approved_date' => $CURRENT_DAY['format']['dd.mm.yyyy hh:mm:ss'],
			'cat_approved_date_unix' => $CURRENT_DAY['full_unix']
		);
		$Update = updateData(CAOP_CAT_SYSTEM, $updateCat, $Cat, "{$PK[CAOP_CAT_SYSTEM]}='{$Cat[$PK[CAOP_CAT_SYSTEM]]}'");
		if ($Update['stat'] == RES_SUCCESS)
		{
			setcookie('GEY_MACHINE', $Cat['cat_content'], time() + 86400 * 365, "/");
			$response['result'] = true;
			$response['msg'] = 'Ключ успешно прикреплён к данному устройству!';
		}
	}
} else $response['msg'] = 'У Вас нет доступа к данному разделу';