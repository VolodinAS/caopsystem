<nav class="navbar navbar-light navbar-expand-xl">
	<a class="navbar-brand"
	   href="/news">
		<img src="/engine/images/logo/main_logo.png"
		     width="30"
		     height="30"
		     class="d-inline-block align-top"
		     alt="">
		<span class="font-weight-bolder">ЦАОП</span>
	</a>
	
	<button class="navbar-toggler"
	        type="button"
	        data-toggle="collapse"
	        data-target="#navbarNavAltMarkup"
	        aria-controls="navbarNavAltMarkup"
	        aria-expanded="false"
	        aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	
	
	
	
	<div class="collapse navbar-collapse" style="z-index: 6"
	     id="navbarNavAltMarkup">
		<ul class="navbar-nav mr-auto">
			
			<?php
			$USER_HEADMENU = $params['headmenu'];
			if (count($USER_HEADMENU) > 0)
			{
				
				$debug_arr = array();
				$debarr = array();
				$accessQuery = AccessString2QueryORs($params['profile']['access_level'], 'headmenu_access');
				$debarr['$accessQuery'] = $accessQuery;
				foreach ($USER_HEADMENU as $MenuItem)
				{
					$new_window = ( $MenuItem['header_blank'] == 1 ) ? ' target="_blank"' : '';
					
					$active = ($MenuItem['headmenu_link'] == $params['page']) ? ' font-weight-bolder' : '';
					if ($MenuItem['headmenu_hassubmenu'] == 1)
					{
						$profile_access_level = str_replace(';', ',', $params['profile']['access_level']);
						$profile_access_level_data = explode(';', $params['profile']['access_level']);
						$submenu = array();
						$submenu_keeper = array();
						/*
						foreach($profile_access_level_data as $access_level)
						{
							$pre_submenu = getarr('caop_headmenu', "headmenu_subid='{$MenuItem['headmenu_id']}' AND headmenu_enabled='1' AND headmenu_access LIKE '%{$access_level}%'", "ORDER BY headmenu_order ASC");
							$debug_arr['pre_submenu'][] = $pre_submenu;
							$Diff_submenu = array_diff($pre_submenu, $submenu_keeper);
							//$debug_arr['Diff_submenu'][] = $Diff_submenu;
							if ( count($Diff_submenu) != 0 )
							{
								$submenu = array_merge($submenu, $Diff_submenu);
								//$debug_arr['submenu'][] = $submenu;
								$submenu_keeper = $submenu;
							}
						}
						*/
						
						/*
						foreach ($profile_access_level_data as $access_level)
						{
							
							$pre_submenu = getarr('caop_headmenu', "headmenu_subid='{$MenuItem['headmenu_id']}' AND headmenu_enabled='1' AND headmenu_access LIKE '%{$access_level}%'", "ORDER BY headmenu_order ASC");
							//$debarr['pre_submenu_' . $access_level] = $pre_submenu;
							//$debarr['submenu_keeper_' . $access_level] = $submenu_keeper;
							$Diff_submenu = array_diff($pre_submenu, $submenu_keeper);
							//$Diff_submenu = array_diff($submenu_keeper, $pre_submenu);
							//$debarr['Diff_submenu_' . $access_level] = $Diff_submenu;
							if (count($submenu_keeper) > 0 && count($pre_submenu) > 0)
							{
								if (count($Diff_submenu) > 0)
								{
									$submenu = array_merge($submenu, $Diff_submenu);
								}
							} else
							{
								$submenu = array_merge($submenu, $pre_submenu);
							}
							
							
							//$debarr['submenu_' . $access_level] = $submenu;
							$submenu_keeper = $submenu;
							//$debug_arr['submenuers_' . $access_level][] = $debarr;
						}
						*/
						$submenu = getarr('caop_headmenu', "headmenu_subid='{$MenuItem['headmenu_id']}' AND headmenu_enabled='1' AND " . $accessQuery, "ORDER BY headmenu_order ASC");
						
						$debug_arr['submenu'] = $submenu;
						?>
						<li class="nav-item dropdown">
							<a data-menuorder="<?= $MenuItem['headmenu_order']; ?>"
							   class="nav-item nav-link dropdown-toggle"
							   href="#"
							   id="<?= $MenuItem['headmenu_link']; ?>"
							   data-toggle="dropdown"
							   aria-haspopup="true"
							   aria-expanded="false"><?= $MenuItem['headmenu_title']; ?></a>
							
							<div class="dropdown-menu"
							     aria-labelledby="<?= $MenuItem['headmenu_link']; ?>">
								<?php
								foreach ($submenu as $submenuItem)
								{
									$new_window_sm = ( $submenuItem['header_blank'] == 1 ) ? ' target="_blank"' : '';
									
									if ($submenuItem['headmenu_divider_before'] == 1)
									{
										bt_divider();
									}
									$activeSubmenu = ($submenuItem['headmenu_link'] == $params['page']) ? ' active' : '';
									if ($submenuItem['headmenu_header'] == 1)
									{
										?>
										<a <?=$new_window_sm;?> data-menuorder="<?= $submenuItem['headmenu_order']; ?>"
										                        class="dropdown-item disabled"><b><?= $submenuItem['headmenu_title']; ?></b></a>
										<?php
									} else
									{
										?>
										<a <?=$new_window_sm;?> data-menuorder="<?= $submenuItem['headmenu_order']; ?>"
										                        class="dropdown-item <?= $activeSubmenu; ?>"
										                        href="/<?= $submenuItem['headmenu_link']; ?>"><?= $submenuItem['headmenu_title']; ?></a>
										<?php
									}
									?>
									
									<?php
									if ($submenuItem['headmenu_divider_after'] == 1)
									{
										bt_divider();
									}
								}
								?>
							</div>
						</li>
						<?php
						
					} else
					{
						?>
						<li class="nav-item">
							<a <?=$new_window;?> data-menuorder="<?= $MenuItem['headmenu_order']; ?>"
							                     class="nav-item nav-link<?= $active; ?>"
							                     href="/<?= $MenuItem['headmenu_link']; ?>"><?= $MenuItem['headmenu_title']; ?></a>
						</li>
						<?php
					}
					$debug_arr[] = $debarr;
				}
			}
			?>
		
		</ul>
		
		<span class="navbar-text font-weight-bolder">
            <?= $params['username']; ?>
        </span>
	</div>
</nav>