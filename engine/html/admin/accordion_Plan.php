<div id="accordion_Plan">
	<div class="card">
		<div class="card-header" id="Plan">
			<h5 class="mb-0">
				<button class="btn btn-link" data-toggle="collapse" data-target="#Plan_spoiler" aria-expanded="true" aria-controls="Plan_spoiler">
					Ближайший план действий
				</button>
			</h5>
		</div>
		<div id="Plan_spoiler" class="collapse" aria-labelledby="Plan" data-parent="#accordion_Plan">
			<div class="card-body">
				<?php
				$Plan = getarr('caop_needadd', 1, "ORDER BY needadd_id DESC");
				$Plan = array_orderby($Plan, 'needadd_done', SORT_ASC, 'needadd_text', SORT_ASC);
				foreach ($Plan as $item) {
					$strike = ' style="text-decoration: line-through;"';
					if ($item['needadd_done'] == 0){
						$strike = '';
					}
					?>
					<span <?=$strike;?>>[<?=$item['needadd_id'];?>] => <?=$item['needadd_text'];?></span>
					<?php
					bt_divider();
				}
				?>
			</div>
		</div>
	</div>
</div>