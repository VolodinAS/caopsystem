<?php
spoiler_begin(wrapper('Праздничные дни') . ' (исключаются из списка)', 'holidays', '');
?>
<form id="form_newHoliday">
    <div class="row">
        <div class="col-auto"><?= wrapper('Добавить праздник (только число и месяц):'); ?></div>
        <div class="col"><input type="text"
                                name="holiday_title"
                                class="form-control form-control-sm"
                                placeholder="Название праздника"
                                value=""
                                required></div>
        <div class="col-1"><input type="text"
                                name="holiday_from"
                                class="form-control form-control-sm russianBirthDayMonth"
                                placeholder="Начало"
                                value=""
                                required></div>
        <div class="col-1"><input type="text"
                                name="holiday_to"
                                class="form-control form-control-sm russianBirthDayMonth"
                                placeholder="Конец"
                                value=""
                                required></div>
        <div class="col-auto">
            <button type="submit"
                    class="btn btn-sm btn-primary btn-addHoliday">Добавить праздник
            </button>
        </div>
    </div>
</form>
<div class="dropdown-divider"></div>
<?php
$Holidays = getarr(CAOP_HOLIDAYS, 1, "ORDER BY holiday_sbboy_from ASC");
if (count($Holidays) > 0)
{
	?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col"
                class="text-center"
                data-title="npp" width="1%">№</th>
            <th scope="col"
                class="text-center"
                data-title="title">Название</th>
            <th scope="col"
                class="text-center"
                data-title="begin" width="1%"><?=nbsper('Начало праздника');?></th>
            <th scope="col"
                class="text-center"
                data-title="end" width="1%"><?=nbsper('Окончание праздника');?></th>
            <th scope="col"
                class="text-center"
                data-title="enabled" width="1%">Учитывать при печати?</th>
            <th scope="col"
                class="text-center"
                data-title="days" width="1%"><?=nbsper('Дней');?></th>
            <th scope="col"
                class="text-center"
                data-title="remove" width="1%">Удалить</th>
        </tr>
        </thead>
        <tbody>
		<?php
        $npp = 1;
		foreach ($Holidays as $holiday)
		{
			$begin_of_holiday = ssboy($holiday['holiday_sbboy_from'], false, false);
			$end_of_holiday = ssboy($holiday['holiday_sbboy_to'], false, false);
			
			$days_sec = $end_of_holiday - $begin_of_holiday;
			$days = floor($days_sec / TIME_DAY) + 1;
			$date_begin = date(DM, $begin_of_holiday);
			$date_end = date(DM, $end_of_holiday);
			
			$checked = ($holiday['holiday_enabled'] == 1) ? ' checked' : '';
			?>
			<tr>
                <td data-cell="npp" align="center"><?=$npp;?></td>
                <td data-cell="title">
                    <input type="text" class="form-control form-control-sm mysqleditor" data-action="edit"
                    data-table="<?=CAOP_HOLIDAYS;?>"
                    data-assoc="0"
                    data-fieldid="holiday_id"
                    data-id="<?=$holiday['holiday_id'];?>"
                    data-field="holiday_title"
                    value="<?=$holiday['holiday_title'];?>">
                </td>
                <td data-cell="begin" align="center">
                    <input type="text" class="form-control form-control-sm mysqleditor russianBirthDayMonth" data-action="edit"
                           data-table="<?=CAOP_HOLIDAYS;?>"
                           data-assoc="0"
                           data-fieldid="holiday_id"
                           data-id="<?=$holiday['holiday_id'];?>"
                           data-field="holiday_sbboy_from"
                           data-adequate="HOLIDAYS_BEGIN"
                           value="<?=$date_begin;?>">
                </td>
                <td data-cell="end" align="center">
                    <input type="text" class="form-control form-control-sm mysqleditor russianBirthDayMonth" data-action="edit"
                           data-table="<?=CAOP_HOLIDAYS;?>"
                           data-assoc="0"
                           data-fieldid="holiday_id"
                           data-id="<?=$holiday['holiday_id'];?>"
                           data-field="holiday_sbboy_to"
                           data-adequate="HOLIDAYS_END"
                           value="<?=$date_end;?>">
                </td>
                <td data-cell="enabled">
                    <input class="form-check-input mysqleditor" type="checkbox" id="holiday_enabled_<?=$holiday['holiday_id'];?>" value="1" data-action="edit"
                    data-table="<?=CAOP_HOLIDAYS;?>"
                    data-assoc="0"
                    data-fieldid="holiday_id"
                    data-id="<?=$holiday['holiday_id'];?>"
                    data-field="holiday_enabled" <?=$checked;?>>
                    <label class="form-check-label box-label" for="holiday_enabled_<?=$holiday['holiday_id'];?>"><span></span></label>
                </td>
                <td data-cell="days" align="center"><?=$days;?></td>
                <td data-cell="remove" align="center">
                    <button class="btn btn-sm btn-success"
                            onclick="if (confirm('Вы действительно хотите удалить данный праздник?')){MySQLEditorAction(this, true); window.location.reload()}"
                            data-action="remove"
                            data-table="<?= CAOP_HOLIDAYS; ?>"
                            data-assoc="0"
                            data-fieldid="holiday_id"
                            data-id="<?= $holiday['holiday_id']; ?>">
		                <?= BT_ICON_CLOSE_LG; ?>
                    </button>
                </td>
            </tr>
			<?php
            $npp++;
		}
		?>
        </tbody>
    </table>
	<?php
} else
{
	bt_notice('В списке еще нет праздников');
}
?>

<?php
spoiler_end();
?>
<div class="dropdown-divider"></div>

<script defer
        type="text/javascript"
        src="/engine/js/holidays.js?<?= rand(0, 99999); ?>"></script>