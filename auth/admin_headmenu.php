<div class="css-table">
    <div class="css-table-header header-sticky bg-white">
        <?php
        require_once("engine/html/include/inc_admin_headmenu_editor_title.php");
        ?>
    </div>
	
	<?php
	$activeColor = 'bg-primary';
	?>

    <div class="css-table-body">

        <?php
        require_once("engine/html/include/inc_admin_headmenu_editor_form.php");
        ?>
		
		<?php
		$AllMenu = getarr(CAOP_HEADMENU, 1, 'ORDER BY headmenu_order ASC');
		if (count($AllMenu) > 0)
		{
			$MenuStructure = [];
			$mainMenuCounter = 1;
			$subMenuCounter = 1;
			$isFirst = true;
			foreach ($AllMenu as $menuItem)
			{
				$activeColor = '';
				if ($menuItem['headmenu_enabled'] == 0)
				{
					$activeColor = 'bg-secondary';
				}
				$inSubmenu = false;
//		debug($menuItem['headmenu_title']);
				if ($menuItem['headmenu_subid'] == 0)
				{
					if ($isFirst) $isFirst = false;
					else
					{
						?>
                        <div class="css-table-row"
                             style="height: 20px">
                        </div>
						<?php
					}
					
					include("engine/html/include/inc_admin_headmenu_editor_main.php");
					
					$mainMenuCounter++;
				}
				if ($menuItem['headmenu_hassubmenu'])
				{
					$inSubmenu = true;
					$subItems = searchArrayMany($AllMenu, 'headmenu_subid', $menuItem['headmenu_id']);
					if ($subItems['status'] == RES_SUCCESS)
					{
						?>
                        <div class="css-table-row"
                             style="height: 5px"></div>
						<?php
						
						$subItemsData = $subItems['data'];
						$subItemsData = array_orderby($subItemsData, 'headmenu_order', SORT_ASC);
						foreach ($subItemsData as $subMenuItem)
						{
							$activeColor = '';
							if ($subMenuItem['headmenu_enabled'] == 0)
							{
								$activeColor = 'bg-secondary';
							}

							include("engine/html/include/inc_admin_headmenu_editor_submain.php");
							
							$subMenuCounter++;
						}
						$subMenuCounter = 1;
					}
					?>
                    <div class="css-table-row"
                         style="height: 20px">

                    </div>
					<?php
				} else
				{
				
				}
				
			}
		}
		?>
    </div>
</div>

<script defer type="text/javascript" src="/engine/js/admin/admin_headmenu.js?<?=rand(0, 99999);;?>"></script>