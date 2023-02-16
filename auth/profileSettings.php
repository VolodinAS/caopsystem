<?php

spoiler_begin('Переадресация после входа на страницу:', 'redirectAfterAuth', '');
{
    $go_next = false;
	$defaultRedirect = doctor_param('get', $USER_PROFILE['doctor_id'], 'defaultRedirect');
	if ( $defaultRedirect['stat'] == RES_SUCCESS )
	{
	    $go_next = true;
	} else
    {
	    $defaultRedirect = doctor_param('set', $USER_PROFILE['doctor_id'], 'defaultRedirect', '8');
	    $defaultRedirect = doctor_param('get', $USER_PROFILE['doctor_id'], 'defaultRedirect');
	    $go_next = true;
    }
	if ( $go_next )
	{
	    $Redirect = $defaultRedirect['data']['settings_param_value'];
	    $Pages = getarr(CAOP_PAGES, 1, "ORDER BY pages_title ASC");
	    $PagesAccess = pages_access_filter($USER_PROFILE, $Pages);
	    
//	    debugn($defaultRedirect, '$defaultRedirect');
	    
	    $pageDefault = array(
	        'key' => 0,
	        'value' => 'Выберите страницу для перехода после авторизации'
	    );
	    $pageSelected = array(
	        'value' => $Redirect
	    );
	    $pageSelector = array2select($PagesAccess, 'pages_id', 'pages_title', 'page_settings_auth',
	    'class="form-control mysqleditor" data-action="edit"
	    data-table="'.CAOP_DOCTOR_SETTINGS.'"
	    data-assoc="0"
	    data-fieldid="'.$PK[CAOP_DOCTOR_SETTINGS].'"
	    data-id="'.$defaultRedirect['data']['settings_id'].'"
	    data-field="settings_param_value"', $pageDefault, $pageSelected);
	    echo $pageSelector['result'];
	    
//	    debugn($PagesAccess, '$PagesAccess');
	}
}
spoiler_end();
?>
<br>
<?php
spoiler_begin('Медсестра по умолчанию', 'defaultNurse', '');
{
	$SetNurse = 0;
	$defaultNurse = doctor_param('get', $USER_PROFILE['doctor_id'], 'defaultNurse');
	if ( $defaultNurse['stat'] == RES_SUCCESS )
	{
		//debug($defaultNurse);
		$MyNurseId = $defaultNurse['data']['settings_param_value'];
	}
	
	foreach ($DoctorsNurseId as $nurse)
	{
		$active = '';
		if ( $nurse['doctor_id'] == $MyNurseId ) $active = " active";
		echo '<a class="dropdown-item'.$active.'" href="javascript:setNurseDefault(' . $nurse['doctor_id'] . ')">[#' . $nurse['doctor_id'] . '] ' . docNameShort($nurse, "famio") . '</a>';
	}
}
spoiler_end();
?>
<br>
<?php
spoiler_begin('Настройка отображения результатов ОБСЛЕДОВАНИЙ', 'researchSettings', '');
{
    spoiler_begin('Отображать результаты только за:', 'researchShowMeOnly', '');
    {
	    $active_all = $active_24h = $active_7d = $active_14d = $active_21d = $active_30d = $active_3mon = $active_6mon = $active_1y = '';
	    $researchShowMePeriod = doctor_param('get', $USER_PROFILE['doctor_id'], 'researchShowMePeriod');
	    if ( $researchShowMePeriod['msg'] == "notfound" )
        {
	        $researchShowMePeriod = doctor_param('set', $USER_PROFILE['doctor_id'], 'researchShowMePeriod', 'all');
	        $researchShowMePeriod = doctor_param('get', $USER_PROFILE['doctor_id'], 'researchShowMePeriod');
        }
	
	    if ( $researchShowMePeriod['stat'] == RES_SUCCESS )
        {
            $ShowMePeriodResearch = $researchShowMePeriod['data']['settings_param_value'];
        }
	    
        $active = 'active_' . $ShowMePeriodResearch;
	    $$active = ' active';
	    echo '<a class="dropdown-item'.$active_all.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'all\')">всё время</a>';
	    echo '<a class="dropdown-item'.$active_24h.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'24h\')">последние 24 часа</a>';
	    echo '<a class="dropdown-item'.$active_7d.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'7d\')">последнюю неделю</a>';
	    echo '<a class="dropdown-item'.$active_14d.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'14d\')">последние 2 недели</a>';
	    echo '<a class="dropdown-item'.$active_21d.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'21d\')">последние 3 недели</a>';
	    echo '<a class="dropdown-item'.$active_30d.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'30d\')">последний месяц (30 дней)</a>';
	    echo '<a class="dropdown-item'.$active_3mon.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'3mon\')">последний квартал (3 месяца)</a>';
	    echo '<a class="dropdown-item'.$active_6mon.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'6mon\')">последние полгода (6 месяцев)</a>';
	    echo '<a class="dropdown-item'.$active_1y.'" href="javascript:setPeriod(\'researchShowMePeriod\', \'1y\')">последний год</a>';
    }
    spoiler_end();
	spoiler_begin('Отображение архивных результатов:', 'researchShowArchive', '');
	{
		$active_1 = $active_0 = '';
		$researchShowArchive = doctor_param('get', $USER_PROFILE['doctor_id'], 'researchShowArchive');
		if ( $researchShowArchive['msg'] == "notfound" )
		{
			$researchShowArchive = doctor_param('set', $USER_PROFILE['doctor_id'], 'researchShowArchive', '1');
			$researchShowArchive = doctor_param('get', $USER_PROFILE['doctor_id'], 'researchShowArchive');
		}
		if ( $researchShowArchive['stat'] == RES_SUCCESS )
		{
			$ShowArchive = $researchShowArchive['data']['settings_param_value'];
		}
		$active = 'active_' . $ShowArchive;
		$$active = ' active';
		echo '<a class="dropdown-item'.$active_1.'" href="javascript:setPeriod(\'researchShowArchive\', \'1\')">отображать</a>';
		echo '<a class="dropdown-item'.$active_0.'" href="javascript:setPeriod(\'researchShowArchive\', \'0\')">не отображать</a>';
	}
	spoiler_end();
}
spoiler_end();
?>
<br>
<?php
spoiler_begin('Настройка отображения результатов ЦИТОЛОГИИ', 'citologySettings', '');
{
    spoiler_begin('Отображать результаты только за:', 'citologyShowMeOnly', '');
    {
	    $active_all = $active_24h = $active_7d = $active_14d = $active_21d = $active_30d = $active_3mon = $active_6mon = $active_1y = '';
	    $citologyShowMePeriod = doctor_param('get', $USER_PROFILE['doctor_id'], 'citologyShowMePeriod');
	    if ( $citologyShowMePeriod['msg'] == "notfound" )
	    {
		    $citologyShowMePeriod = doctor_param('set', $USER_PROFILE['doctor_id'], 'citologyShowMePeriod', 'all');
		    $citologyShowMePeriod = doctor_param('get', $USER_PROFILE['doctor_id'], 'citologyShowMePeriod');
	    }
	
	    if ( $citologyShowMePeriod['stat'] == RES_SUCCESS )
	    {
		    $ShowMePeriodCitology = $citologyShowMePeriod['data']['settings_param_value'];
	    }
	
	    $active = 'active_' . $ShowMePeriodCitology;
	    $$active = ' active';
	    echo '<a class="dropdown-item'.$active_all.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'all\')">всё время</a>';
	    echo '<a class="dropdown-item'.$active_24h.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'24h\')">последние 24 часа</a>';
	    echo '<a class="dropdown-item'.$active_7d.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'7d\')">последнюю неделю</a>';
	    echo '<a class="dropdown-item'.$active_14d.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'14d\')">последние 2 недели</a>';
	    echo '<a class="dropdown-item'.$active_21d.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'21d\')">последние 3 недели</a>';
	    echo '<a class="dropdown-item'.$active_30d.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'30d\')">последний месяц (30 дней)</a>';
	    echo '<a class="dropdown-item'.$active_3mon.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'3mon\')">последний квартал (3 месяца)</a>';
	    echo '<a class="dropdown-item'.$active_6mon.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'6mon\')">последние полгода (6 месяцев)</a>';
	    echo '<a class="dropdown-item'.$active_1y.'" href="javascript:setPeriod(\'citologyShowMePeriod\', \'1y\')">последний год</a>';
    }
    spoiler_end();
	spoiler_begin('Отображение архивных результатов:', 'citologyShowArchive', '');
    {
	    $active_1 = $active_0 = '';
	    $citologyShowArchive = doctor_param('get', $USER_PROFILE['doctor_id'], 'citologyShowArchive');
	    if ( $citologyShowArchive['msg'] == "notfound" )
	    {
		    $citologyShowArchive = doctor_param('set', $USER_PROFILE['doctor_id'], 'citologyShowArchive', '1');
		    $citologyShowArchive = doctor_param('get', $USER_PROFILE['doctor_id'], 'citologyShowArchive');
	    }
	    if ( $citologyShowArchive['stat'] == RES_SUCCESS )
	    {
		    $ShowArchive = $citologyShowArchive['data']['settings_param_value'];
	    }
	    $active = 'active_' . $ShowArchive;
	    $$active = ' active';
	    echo '<a class="dropdown-item'.$active_1.'" href="javascript:setPeriod(\'citologyShowArchive\', \'1\')">отображать</a>';
	    echo '<a class="dropdown-item'.$active_0.'" href="javascript:setPeriod(\'citologyShowArchive\', \'0\')">не отображать</a>';
    }
    spoiler_end();
}
spoiler_end();

?>

<script defer src="/engine/js/profileSettings.js?<?=rand(0,999999)?>" type="text/javascript"></script>