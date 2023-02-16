<?php
//cna.needadd_planned DESC,
$queryPlan = "SELECT IF( cna.needadd_done = '0', 0, 1 ) AS isDone, cna . * FROM {$CAOP_NEEDADD} cna WHERE cna.needadd_hidden='0' ORDER BY isDone ASC, cna.needadd_priority DESC";
$resultPlan = mqc($queryPlan);
$Plan = mr2a($resultPlan);
//$Plan = getarr(CAOP_NEEDADD, "needadd_hidden='0'", "ORDER BY needadd_priority DESC");
//$Plan = array_orderby($Plan, 'needadd_done', SORT_ASC, 'needadd_text', SORT_ASC, 'needadd_priority', SORT_DESC);
//$Plan = array_orderby($Plan, 'needadd_done', SORT_ASC, 'needadd_text', SORT_ASC, 'needadd_priority', SORT_DESC);
?>
<form id="form_newTask"
      method="post"
      action="">
    <div class="row">
        <div class="col-auto">
            <b>Новая задача:</b>
        </div>
        <div class="col">
            <input class="form-control"
                   type="text"
                   name="task"
                   id="task">
        </div>
        <div class="col-auto">
            <button type="submit"
                    class="btn btn-success"
                    id="newTask">Добавить
            </button>
        </div>
    </div>
</form>

<div class="dropdown-divider"></div>
<button class="btn btn-sm btn-danger deleteTasks">Удалить отмеченные</button>
<div class="dropdown-divider"></div>

<table class="table table-sm">
    <thead>
    <tr>
        <th class="text-center"
            scope="col"
            data-title="#" <?= super_bootstrap_tooltip('Выбрать все'); ?>>
            <input class="form-check-input selectAll"
                   type="checkbox"
                   name="selectAll"
                   id="selectAll"
                   data-target="todoItem"
                   value="1">
            <label class="form-check-label box-label"
                   for="selectAll"><span></span></label>
        </th>
        <th class="text-center"
            scope="col"
            data-title="№">№
        </th>
        <th class="text-center"
            scope="col"
            data-title="Описание">Описание
        </th>
        <th class="text-center"
            scope="col"
            data-title="Обновлено">Обновлено
        </th>
        <th class="text-center"
            scope="col"
            data-title="Приоритет">Приоритет
        </th>
        <th class="text-center"
            scope="col"
            data-title="Запланировано">Запланировано
        </th>
        <th class="text-center"
            scope="col"
            data-title="В процессе">В&nbsp;процессе
        </th>
        <th class="text-center"
            scope="col"
            data-title="Завершено">Завершено
        </th>
        <th class="text-center"
            scope="col"
            data-title="Завершить">Завершить
        </th>
        <th class="text-center"
            scope="col"
            data-title="Скрыть">Скрыть
        </th>
    </tr>
    </thead>
    <tbody>
	
	
	<?php
	$npp = 1;
	$MAX_ID = 0;
	foreach ($Plan as $item)
	{
		$strike = ' style="text-decoration: line-through;"';
		$done_date = date("d.m.Y H:i:s", $item['needadd_done_unix']);
		$done_checked = ' checked';
		$inprocess_checked = ' checked';
		$planned_checked = ' checked';
		$btn_prior_up = '';
		$btn_prior_down = '';
		$hideme_invisible = '';
//		$inprocess_invisible = ' invisible';
		$inprocess_date = date("d.m.Y H:i:s", $item['needadd_process_unix']);
		$planned_date = date("d.m.Y H:i:s", $item['needadd_planned_unix']);
		
		$in_process = '';
		$planned_done = ' disabled';
		
		if ($item['needadd_process'] == 0)
		{
			$planned_done = '';
			$inprocess_checked = '';
			$inprocess_tooltip = 'В процессе';
		}
		
		if ($item['needadd_planned'] == 0)
		{
			$planned_checked = '';
			$planned_tooltip = 'Запланировано';
		}
		
		if ($item['needadd_done'] == 0)
		{
			if ($item['needadd_planned'] == 1)
			{
				$planned_tooltip = date("d.m.Y H:i:s", $item['needadd_planned_unix']);
			}
			if ($item['needadd_process'] == 1)
			{
				$inprocess_tooltip = date("d.m.Y H:i:s", $item['needadd_process_unix']);
			}
			$inprocess_date = '
		<input class="form-check-input mysqleditor" type="checkbox" name="inprocess' . $item['needadd_id'] . '" id="inprocess' . $item['needadd_id'] . '" value="1" data-item="' . $item['needadd_id'] . '"
                    data-action="edit"
                    data-table="' . CAOP_NEEDADD . '"
                    data-assoc="0"
                    data-fieldid="needadd_id"
                    data-id="' . $item['needadd_id'] . '"
                    data-field="needadd_process"
                    data-unixfield="needadd_process_unix,needadd_upd_unix"
                    ' . $inprocess_checked . '>
                <label class="form-check-label box-label" for="inprocess' . $item['needadd_id'] . '"><span ' . super_bootstrap_tooltip($inprocess_tooltip) . '></span></label>
		';
			$hideme_invisible = ' invisible';
//			$inprocess_invisible = '';
			$in_process = ' invisible';
			$strike = '';
			$done_date = '';
			$done_checked = '';
			$btn_prior_up = '<div class="d-inline"><button class="btn btn-sm btn-primary prior" data-type=">" data-id="' . $item['needadd_id'] . '">' . BT_ICON_PRIOR_UP . '</button></div>';
			$btn_prior_down = '<div class="d-inline"><button class="btn btn-sm btn-secondary prior" data-type="<" data-id="' . $item['needadd_id'] . '">' . BT_ICON_PRIOR_DOWN . '</button></div>';
			
			$planned_date = '
		<input class="form-check-input mysqleditor ' . $planned_done . '" type="checkbox" name="planned' . $item['needadd_id'] . '" id="planned' . $item['needadd_id'] . '" value="1" data-item="' . $item['needadd_id'] . '"
                    data-action="edit"
                    data-table="' . CAOP_NEEDADD . '"
                    data-assoc="0"
                    data-fieldid="needadd_id"
                    data-id="' . $item['needadd_id'] . '"
                    data-field="needadd_planned"
                    data-unixfield="needadd_planned_unix,needadd_upd_unix"
                    ' . $planned_checked . '>
                <label class="form-check-label box-label" for="planned' . $item['needadd_id'] . '"><span ' . super_bootstrap_tooltip($planned_tooltip) . '></span></label>
		';
			
		}
		$inprocess_tooltip = 'В процессе: ' . date("d.m.Y H:i:s", $item['needadd_process_unix']);

		
		
		if ($item['needadd_priority'] == 0)
		{
			if ($MAX_ID == 0)
			{
				$queryMax = "SELECT MAX(needadd_id) AS max_id FROM {$CAOP_NEEDADD}";
				$resultMax = mqc($queryMax);
				$max_id_Data = mfa($resultMax);
				$MAX_ID = $max_id_Data['max_id'];
			}
			
			$updateParams = array(
				'needadd_priority' => ($MAX_ID + 10) - $item['needadd_id'],
				'needadd_upd_unix' => time()
			);
			$UpdateData = updateData(CAOP_NEEDADD, $updateParams, $item, "needadd_id='{$item['needadd_id']}'");
		}
		?>
        <tr>
            <td class="text-center"
                data-cell="#">
                <input class="form-check-input todoItem"
                       type="checkbox"
                       name="todoItem<?= $item['needadd_id']; ?>"
                       id="todoItem<?= $item['needadd_id']; ?>"
                       data-id="<?= $item['needadd_id']; ?>"
                       value="1">
                <label class="form-check-label box-label"
                       for="todoItem<?= $item['needadd_id']; ?>"><span></span></label>
            </td>
            <th class="text-center"
                data-cell="№"
                scope="row"><?= $npp; ?>.
            </th>
            <td id="needadd_<?= $item['needadd_id']; ?>"
                data-cell="Описание" <?= $strike; ?> <?= super_bootstrap_tooltip('Добавлено: ' . date("d.m.Y H:i:s", $item['needadd_add_unix'])); ?>
                class="mysqleditor-realtime"
                data-rttable="<?= CAOP_NEEDADD; ?>"
                data-rtaction="edit"
                data-rtfieldid="needadd_id"
                data-rtid="<?= $item['needadd_id']; ?>"
                data-rtfield="needadd_text"
                data-rtreturn="#needadd_<?= $item['needadd_id']; ?>"
                data-rtreturntype="html"
                data-defaultvalue="<?= $item['needadd_text']; ?>"><?= $item['needadd_text']; ?></td>
            <td class="text-center"
                data-cell="Обновлено"><?= date("d.m.Y H:i:s", $item['needadd_upd_unix']); ?></td>
            <td class="text-center"
                data-cell="Приоритет">
				<?= $btn_prior_up; ?>
                <!--<div class="d-inline">
                        <b><?/*=$item['needadd_priority'];*/
				?></b>
                    </div>-->
				<?= $btn_prior_down; ?>
            </td>
            <td class="text-center<?= $inprocess_invisible; ?>"
                data-cell="Запланировано">
				<?= $planned_date; ?>
            </td>
            <td class="text-center<?= $inprocess_invisible; ?>"
                data-cell="В процессе">
				<?= $inprocess_date; ?>
            </td>
            <td class="text-center"
                data-cell="Завершено"><?= $done_date; ?></td>
            <td class="text-center"
                data-cell="Завершить">
                <input class="form-check-input mysqleditor"
                       type="checkbox"
                       id="doneme<?= $item['needadd_id']; ?>"
                       value="1"
                       data-item="<?= $item['needadd_id']; ?>"
                       data-action="edit"
                       data-table="<?= CAOP_NEEDADD; ?>"
                       data-assoc="0"
                       data-fieldid="needadd_id"
                       data-id="<?= $item['needadd_id']; ?>"
                       data-field="needadd_done"
                       data-unixfield="needadd_done_unix,needadd_upd_unix"
					<?= $done_checked; ?>>
                <label class="form-check-label box-label"
                       for="doneme<?= $item['needadd_id']; ?>"><span <?= super_bootstrap_tooltip('Завершить'); ?>></span></label>
            </td>
            <td class="text-center<?= $hideme_invisible; ?>"
                data-cell="Скрыть">
                <input class="form-check-input mysqleditor"
                       type="checkbox"
                       id="hideme<?= $item['needadd_id']; ?>"
                       value="1"
                       data-item="<?= $item['needadd_id']; ?>"
                       data-action="edit"
                       data-table="<?= CAOP_NEEDADD; ?>"
                       data-assoc="0"
                       data-fieldid="needadd_id"
                       data-id="<?= $item['needadd_id']; ?>"
                       data-field="needadd_hidden">
                <label class="form-check-label box-label"
                       for="hideme<?= $item['needadd_id']; ?>"><span <?= super_bootstrap_tooltip('Скрыть'); ?>></span></label>
            </td>
        </tr>
		<?php
		$npp++;
	}
	/*
	 * [Влад] - 3       =>      [Влад] - 3      =>      [Влад] - 3
	 * [Саня] - 2       =>      [Саня] - 1      =>      [Даня] - 2
	 * [Даня] - 1 ▲     =>      [Даня] - 2      =>      [Саня] - 1
	*/
	?>
    </tbody>
</table>

<script defer
        src="/engine/js/admin/adminTODO.js?<?= rand(0, 999999); ?>"
        type="text/javascript"></script>