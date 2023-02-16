<div class="css-table">
	<div class="css-table-header">
		<?php
		require_once("engine/html/include/patterns/inc_title.php");
		?>
	</div>
	
	<?php
	$activeColor = 'bg-primary';
	?>
	
	<div class="css-table-body">
		
		<?php
		require_once("engine/html/include/patterns/inc_form.php");
		?>
		
		<?php
		$AllPatterns = getarr(CAOP_DIAGNOSIS_PATTERNS, 1, 'ORDER BY pattern_updated_unix DESC');
		if (count($AllPatterns) > 0)
		{
			$patternCounter = 1;
			$isFirst = true;
			foreach ($AllPatterns as $patternItem)
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
				
				include("engine/html/include/patterns/inc_main.php");
				
				$patternCounter++;
				
			}
		}
		?>
	</div>
</div>

<script defer type="text/javascript" src="/engine/js/patterns.js?<?=rand(0, 99999);;?>"></script>