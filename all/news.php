<?php

$DoctorsListBirths = getarr(CAOP_DOCTOR, "doctor_isBirth='1'", "ORDER BY doctor_birth_unix ASC");

$Birth_Panel = $AdminParams['data']['birth_panel']['param_value'];
if ($Birth_Panel)
{
	for ($index = 0; $index < count($DoctorsListBirths); $index++)
	{
		$birth_data = $DoctorsListBirths[$index]['doctor_birth'];
		$bd = explode('.', $birth_data);
		$bd = mktime(0, 0, 0, $bd[1], $bd[0], date('Y') + ($bd[1] . $bd[0] <= date('md')));
		$days_until = ceil(($bd - time()) / 86400);
		
		if ($days_until == 365)
		{
			$days_until = 0;
		}
		
		$DoctorsListBirths[$index]['days_until'] = $days_until;
	}
	$DoctorsListBirths = array_orderby($DoctorsListBirths, 'days_until', SORT_ASC);
	$DoctorCloser = $DoctorsListBirths[0];
	$DoctorCloserMany = searchArrayMany($DoctorsListBirths, 'days_until', $DoctorCloser['days_until']);
	if ($DoctorCloserMany['status'] == RES_SUCCESS)
	{
		$DoctorCloserMany = $DoctorCloserMany['data'];
		$Doc = $DoctorCloserMany[0];
//			debug($Doc);
		if ($Doc['days_until'] > 0)
		{
			if (count($DoctorCloserMany) > 1)
			{
				bt_notice('Ближайший день рождения (у ' . count($DoctorCloserMany) . ' ' . wordEnd(count($DoctorCloserMany), 'человека', 'людей', 'человек') . '): ' . wrapper('через ' . $DoctorCloser['days_until'] . ' ' . wordEnd($DoctorCloser['days_until'], 'день', 'дня', 'дней')) . '. <a href="/births">Посмотреть список</a>', BT_THEME_WARNING);
			} else
			{
				bt_notice('Ближайший день рождения: ' . wrapper('через ' . $DoctorCloser['days_until'] . ' ' . wordEnd($DoctorCloser['days_until'], 'день', 'дня', 'дней')) . '. <a href="/births">Посмотреть список</a>', BT_THEME_WARNING);
			}
			
		} else
		{
			foreach ($DoctorCloserMany as $DoctorCloser)
			{
				bt_notice(wrapper(docNameShort($DoctorCloser, 'famimot') . '! Всем коллективом ЦАОП поздравляем Вас с ДНЁМ РОЖДЕНИЯ!'), BT_THEME_PRIMARY);
			}
		}
	}
}
?>
<?php
bt_notice(wrapper('НЕ ЗАБЫВАЙТЕ! Все картинки - КЛИКАБЕЛЬНЫЕ! Кликните на картинке, чтобы ее УВЕЛИЧИТЬ!'), BT_THEME_PRIMARY);
?>
<?php

if ( strlen($request_params) > 0 )
{
	// news ID
	$NEWS_ID = $request_params;
	
	$CheckNews = getarr(CAOP_NEWS, "news_id='$NEWS_ID'");
	if ( count($CheckNews) == 1 )
	{
		$News = $CheckNews;
	} else
    {
        bt_notice('Такой новости не существует', BT_THEME_WARNING);
	    $News = getarr('caop_news', "news_publish='1'", "ORDER BY news_id DESC");
    }
	
} else
{
	$News = getarr('caop_news', "news_publish='1'", "ORDER BY news_id DESC");
}

$BigPictureHtmlDefault = '
<div class="align-center">
    <a href="[IMGSRC]" data-lightbox="image-' . rand(0, 1000000) . '" data-title="Фотография">
        <img src="[IMGSRC]" class="img-fluid manual-img" alt="">
    </a>
</div>';

$BigPictureHtmlDefaultRegExp = '
<div class="align-center">
    <a href="/engine/images/$1" data-lightbox="image-' . rand(0, 1000000) . '" data-title="Фотография">
        <img src="/engine/images/$1" class="img-fluid manual-img" alt="">
    </a>
</div>';

if (count($News) > 0)
{
	foreach ($News as $newItem)
	{
		
		$news_body = str_replace("\n", "<br/><br/>", $newItem['news_body']);
		
		$patterns = array(
			'/\{badge(.*?)\}(.*?)\{\/badge\}/mi',
			'/\{bigpicture}(.*?)\{\/bigpicture\}/mi',
            '/\{line\}/mi'
		);
		$replacements = array(
			'<span class="badge badge$1">$2</span>',
			$BigPictureHtmlDefaultRegExp,
            '<div class="dropdown-divider"></div>'
		);
		$news_body = preg_replace($patterns,$replacements, $news_body);
		
		$header = $newItem['news_title'];
		
		if ($newItem['news_breaking'] == '1')
		{
			$header = '<span class="blink_me bg-danger br-10px pd-5px color-yellow">НОВОЕ</span> ' . $header;
		}
		
		$isOpened = ($newItem['news_isOpened'] == '1') ? '' : 'collapse';
		
		?>
        <div class="h3 cursor-pointer" onclick="javascript:window.location.href='/news/<?=$newItem['news_id'];?>'"><?= $header; ?></div>
        <div class="h6"><?= $newItem['news_subtitle']; ?></div>
		<? spoiler_begin('Нажмите, чтобы прочитать полностью...', 'news_spoiler_' . $newItem['news_id'], $isOpened); ?>
		<?= $news_body ?>
        <div class="dropdown-divider"></div>
        <div class="text-right">
			<?= $newItem['news_author']; ?>, <?= date("d.m.Y H:i", $newItem['news_unix']); ?>
        </div>
		<? spoiler_end(); ?>
        <br/><br/>
		<?php
	}
	/*Здравствуйте, уважаемые коллеги!
Меня зовут Володин Александр Сергеевич. Я - врач-онколог в ЦАОП, но дополнительно занимаюсь разработкой данного проекта.
Цель данного проекта - удобство хранения данных, избегая "бумажной писанины" в журналах. Через этот проект я планирую создать полноценную систему, которая позволит облегчить труд сотрудников ЦАОП, а также сделать более прозрачную и удобную систему отчётов и очередей. Система еще только-только запущена, поэтому, на ранней стадии могут возникать различные ошибки и недочёты - будьте нисходительными.
Если вы увидели такую ошибку - не забудьте сообщить мне о ней, и я постараюсь в кратчайшие сроки ее устранить. Надеюсь на наше дальнейшее долгосрочное сотрудничество! Удачи в работе!
С уважением, ваш коллега, Володин Александр Сергеевич*/
} else
{
	bt_notice('Новостей пока нет', BT_THEME_PRIMARY);
}
?>