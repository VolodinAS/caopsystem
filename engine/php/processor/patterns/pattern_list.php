<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$response['result'] = true;

$response['htmlData'] =  '';

$Patterns = getarr(CAOP_DIAGNOSIS_PATTERNS, 1, "ORDER BY pattern_updated_unix DESC");
//$Patterns = [];
if ( count($Patterns) > 0 )
{
	$response['htmlData'] .= bt_notice(wrapper('Доступных паттернов:') . ' ' . count($Patterns) . '. <a href="/dgPatterns" target="_blank">'.wrapper('Создать новый?').'</a>',BT_THEME_PRIMARY, 1);
	
	foreach ($Patterns as $pattern)
	{
//		$response['htmlData'] .= debug_ret($pattern);
		$response['htmlData'] .= '<h4>'.$pattern['pattern_title'].'</h4>';
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
		$response['htmlData'] .= wrapper($pattern['pattern_description']);
		$response['htmlData'] .= '<div class="row">';
		$response['htmlData'] .= '<div class="col-10">';
		$response['htmlData'] .= '<textarea id="copyArea'.$pattern['pattern_id'].'" class="form-control">'.$pattern['pattern_codes'].'</textarea>';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '<div class="col-auto">';
		$response['htmlData'] .= '<button class="btn btn-primary clickForCopy" data-target="copyArea'.$pattern['pattern_id'].'">Копировать</button>';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '</div>';
		$response['htmlData'] .= '<div>'.wrapper('Создано:').' '.date("d.m.Y H:i:s", $pattern['pattern_created_unix']).'</div>';
		$response['htmlData'] .= '<div>'.wrapper('Обновлено:').' '.date("d.m.Y H:i:s", $pattern['pattern_updated_unix']).'</div>';
		$response['htmlData'] .= '<br/><br/>';
	}
	
} else
{
	$response['htmlData'] .= bt_notice('Нет доступных паттернов. <a href="/dgPatterns" target="_blank"><b>Создать?</b></a>', BT_THEME_WARNING, 1);
}