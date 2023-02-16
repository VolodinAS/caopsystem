<?php
$DivideSettings = array(
    'title' => 'НАЗВАНИЕ',
    'dom_title' => 'ПОСТФИКС',
	'updated_date' => '12.12.1992',
	'updated_time' => '12:12'
);
?>

<div id="accordion_<?=$DivideSettings['dom_title'];?>">
	<div class="card">
		<div class="card-header" id="<?=$DivideSettings['dom_title'];?>">
			<h5 class="mb-0">
				<button class="btn btn-link" data-toggle="collapse" data-target="#<?=$DivideSettings['dom_title'];?>_spoiler" aria-expanded="true" aria-controls="<?=$DivideSettings['dom_title'];?>_spoiler">
					<?=$DivideSettings['title'];?>
				</button>
			</h5>
		</div>
		<div id="<?=$DivideSettings['dom_title'];?>_spoiler" class="collapse" aria-labelledby="<?=$DivideSettings['dom_title'];?>" data-parent="#accordion_<?=$DivideSettings['dom_title'];?>">
			<div class="card-body">
				<h4>Обновлено: <?=$DivideSettings['updated_date'];?> <?=$DivideSettings['updated_time'];?> ТЛТ</h4>
				<ul>
					<li></li>
				</ul>
			</div>
		</div>
	</div>
</div>