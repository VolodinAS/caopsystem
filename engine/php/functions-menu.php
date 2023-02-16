<?php
function getTreeMenu($profile, $get_all = false)
{
	if ($get_all)
	{
		$AllMenu = getarr(CAOP_HEADMENU, "1", "ORDER BY headmenu_order ASC");
		$AllMenu_access = [];
		foreach ($AllMenu as $menu_item)
		{
			$AllMenu_access[$menu_item['headmenu_id']] = $menu_item;
		}
	} else
	{
		$access_array = explode(";", $profile['doctor_access']);
		$AllMenu = getarr(CAOP_HEADMENU, "headmenu_enabled='1'", "ORDER BY headmenu_order ASC");
		
		$AllMenu_access = [];
		foreach ($AllMenu as $menu_item)
		{
			$link_access_data = explode(';', $menu_item['headmenu_access']);
			$is = array_intersect($access_array, $link_access_data);
			if ( $is )
			{
				$AllMenu_access[$menu_item['headmenu_id']] = $menu_item;
//				$AllMenu_access[] = $menu_item;
			}
		}
		
		if ( count($AllMenu_access) == 0 )
		{
			foreach ($AllMenu as $menu_item)
			{
				$link_access_data = explode(';', $menu_item['headmenu_access']);
				
				if ( in_array('0', $link_access_data) )
				{
					$AllMenu_access[$menu_item['headmenu_id']] = $menu_item;
				}
			}
		}

//		return $AllMenu_access;
	}
	return $AllMenu_access;
}

function getTree($dataset) {
	$tree = array();
	
	foreach ($dataset as $id => &$node) {
		//Если нет вложений
		if (!$node['headmenu_subid']){
			$tree[$id] = &$node;
		}else{
			//Если есть потомки то перебераем массив
			$dataset[$node['headmenu_subid']]['childs'][$id] = &$node;
		}
	}
	return $tree;
}

function tplMenu($category, $params){
	
	$new_window = ( $category['header_blank'] == 1 ) ? ' target="_blank"' : '';
	
	$active = '';
	$active_prefix = '';
	if ( $category['headmenu_link'] == $params['page'] )
	{
		$active = ' font-weight-bolder';
		$active_prefix = '▶';
	}
	
	$href = 'href="/'. $category['headmenu_link'] .'"';
	$menu .= '
	<li>
        <a
        	{link-href}
        	title="'. $category['headmenu_title'] .'"
        	class="'.$active.'"
        	'.$new_window.'
        >
            '.$active_prefix.$category['headmenu_title'].'{row-down}
        </a>
    ';
	
	if(isset($category['childs']))
	{
		$menu = str_replace('{class-dropdown}', ' dropdown', $menu);
		$menu = str_replace('{row-down}', ' <span class="bi bi-caret-down-fill"></span>', $menu);
		$menu = str_replace('{link-href}', '', $menu);
		
		$menu .= '<ul class="submenu">'. showCat($category['childs'], $params) .'</ul>';
	} else
	{
		$menu = str_replace('{class-dropdown}', '', $menu);
		$menu = str_replace('{row-down}', '', $menu);
		$menu = str_replace('{link-href}', $href, $menu);
	}
	$menu .= '</li>';

//	if ($category['headmenu_divider_after'] == 1)
//	{
//		$menu .= bt_divider(1);
//	}
	
	return $menu;
}

function tplMenuEditor($category){
	global $PK;
	$new_window = ( $category['header_blank'] == 1 ) ? ' target="_blank"' : '';
	
	$href = 'href="'. $category['headmenu_link'] .'"';
	
	$class = ( $category['headmenu_enabled'] == 0 ) ? 'text-muted striked' : '';
	
	$menu .= '
	<div id="category_'.$category['headmenu_id'].'">
		<button
			class="btn btn-info mysqleditor-modal-form"
			data-action="edit"
            data-table="'.CAOP_HEADMENU.'"
            data-fieldid="'.$PK[CAOP_HEADMENU].'"
            data-id="'.$category[$PK[CAOP_HEADMENU]].'"
            data-title="Редактирование меню"
		>
			Edit
		</button>
		<span class="'.$class.'">['.$category['headmenu_id'].'] '.$category['headmenu_title'].' - '.$category['headmenu_order'].'</span>
    ';
	
	if(isset($category['childs']))
	{
//		$menu = str_replace('{class-dropdown}', ' dropdown', $menu);
//		$menu = str_replace('{row-down}', ' <span class="bi bi-caret-down-fill"></span>', $menu);
//		$menu = str_replace('{link-href}', '', $menu);
		
		$menu .= '
		'.spoiler_begin_return('Подменю ('.count($category['childs']).')', 'child_' . $category['headmenu_id']).'
			'. showCatEditor($category['childs']) .'
		'.spoiler_end_return().'
		';
	} else
	{
//		$menu = str_replace('{class-dropdown}', '', $menu);
//		$menu = str_replace('{row-down}', '', $menu);
//		$menu = str_replace('{link-href}', $href, $menu);
	}
	$menu .= '</div><div class="dropdown-divider"></div>';

//	if ($category['headmenu_divider_after'] == 1)
//	{
//		$menu .= bt_divider(1);
//	}
	
	return $menu;
}

function showCat($data, $params){
	$string = '';
	foreach($data as $item){
		$string .= tplMenu($item, $params);
	}
	return $string;
}

function showCatEditor($data){
	$string = '';
	foreach($data as $item){
		$string .= tplMenuEditor($item);
	}
	return $string;
}