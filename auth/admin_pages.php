<div class="css-table">
	<div class="css-table-header header-sticky bg-white">
		<?php
		require_once("engine/html/include/inc_admin_pages_editor_title.php");
		?>
	</div>
	
	<?php
	$activeColor = 'bg-primary';
	?>
	
	<div class="css-table-body">
		
		<?php
		require_once("engine/html/include/inc_admin_pages_editor_form.php");
		?>
		
		<?php
		$AllPages = getarr(CAOP_PAGES, 1, 'ORDER BY pages_title ASC');
		if (count($AllPages) > 0)
		{
			$pagesCounter = 1;
			$isFirst = true;
			foreach ($AllPages as $pageItem)
			{
				$activeColor = '';
				
				if ($isFirst) $isFirst = false;
				else
				{
					?>
					<div class="css-table-row"
					     style="height: 20px">
					</div>
					<?php
				}

					include("engine/html/include/inc_admin_pages_editor_main.php");
				
				$pagesCounter++;
				
			}
		}
		?>
	</div>
</div>

<script defer type="text/javascript" src="/engine/js/admin/admin_pages.js?<?=rand(0, 99999);?>"></script>