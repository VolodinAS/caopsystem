<div class="dropdown-divider"></div>
<button class="btn btn-primary"
        data-toggle="collapse"
        data-target="#newCartridge"
        aria-expanded="false"
        aria-controls="newCartridge">
    Добавить новый картридж
</button>
<div class="dropdown-divider"></div>

<div class="collapse"
     id="newCartridge">
    <div class="card card-body">
		
		<?php
		require_once("engine/html/include/cartridge/newCartridge_form.php");
		?>

    </div>
</div>

<?php
$CartridgeTypes = getarr(CAOP_CARTRIDGE_ACTION_TYPES, 1, "ORDER BY type_id ASC");
$CartridgeTypesId = getDoctorsById($CartridgeTypes, 'type_id');

$CartridgesData = getarr(CAOP_CARTRIDGE, 1, "ORDER BY cartridge_update_unix DESC");
if (count($CartridgesData) > 0)
{
	?>
    <table class="tbc"
           border="1"
           cellpadding="5">
        <thead>
        <tr>
            <th scope="col"
                class="text-center font-weight-bolder"
                data-title="ID"
                width="1%">ID
            </th>
            <th scope="col"
                class="text-center font-weight-bolder"
                data-title="ident"
                width="1%">Идентификатор
            </th>
            <th scope="col"
                class="text-center font-weight-bolder"
                data-title="barcode"
                width="1%">Штрихкод
            </th>
            <th scope="col"
                class="text-center font-weight-bolder"
                data-title="description">Примечание
            </th>
            <th scope="col"
                class="text-center font-weight-bolder"
                data-title="state"
                width="1%"><?= nbsper('Текущий статус'); ?>
            </th>
            <th scope="col"
                class="text-center font-weight-bolder"
                data-title="actions"
                width="1%"><?= _nbsp(7); ?>Действия<?= _nbsp(7); ?>
            </th>
        </tr>
        </thead>
        <tbody>
		<?php
		foreach ($CartridgesData as $Cartridge)
		{
			$CartridgeActionData = getarr(CAOP_CARTRIDGE_ACTION, "action_cartridge_id='{$Cartridge['cartridge_id']}'", "ORDER BY action_date_add_unix DESC");
			$current_status = nbsper('действия отсутствуют');
			if (count($CartridgeActionData) > 0)
			{
				$CartridgeActionLast = $CartridgeActionData[0];
				$current_status = $CartridgeTypesId['id' . $CartridgeActionLast['action_type_id']]['type_title'];
			}
			
			?>
            <tr data-cartridge="<?= $Cartridge['cartridge_id']; ?>"
                id="cartridge_<?= $Cartridge['cartridge_id']; ?>">
                <td data-cell="ID"
                    align="center"><?= $Cartridge['cartridge_id']; ?></td>
                <td data-cell="ident"
                    align="center"><?= $Cartridge['cartridge_ident']; ?></td>
                <td data-cell="barcode"
                    align="center"><?= $Cartridge['cartridge_barcode']; ?></td>
                <td data-cell="description"><?= $Cartridge['cartridge_desc']; ?></td>
                <td data-cell="state"
                    align="center"><?= nbsper($current_status); ?></td>
                <td data-cell="actions"
                    align="center">
                    <button class="btn btn-sm btn-secondary"
                            data-toggle="collapse"
                            data-target="#cartridge_collapse_<?= $Cartridge['cartridge_id']; ?>"
                            aria-expanded="false"
                            aria-controls="cartridge_collapse_<?= $Cartridge['cartridge_id']; ?>">
                        <span <?= super_bootstrap_tooltip('Показать передвижения картриджа'); ?>><?= BT_ICON_CARD; ?></span>
                    </button>
                    <button <?= super_bootstrap_tooltip('Добавить действие'); ?> class="btn btn-sm btn-warning btn-addAction"
                                                                                 data-cartridge="<?= $Cartridge['cartridge_id']; ?>">
						<?= BT_ICON_PLUS_LG; ?>
                    </button>
                    <button <?= super_bootstrap_tooltip('Удалить картридж'); ?> class="btn btn-sm btn-danger">
						<?= BT_ICON_MINUS_LG; ?>
                    </button>
                </td>
            </tr>
            <tr class="collapse"
                id="cartridge_collapse_<?= $Cartridge['cartridge_id']; ?>">
                <td colspan="6">
					<?php
					
					if (count($CartridgeActionData) > 0)
					{
						?>
                        <table class="tbc"
                               border="1"
                               cellpadding="3">
							<?php
							foreach ($CartridgeActionData as $action)
							{
								$state = $CartridgeTypesId['id' . $action['action_type_id']]['type_title'];
								$action_date_responsed_format = (strlen($action['action_date_responsed']) > 0) ? $action['action_date_responsed'] : '---';
								$action_date_caop_get_format = (strlen($action['action_date_caop_get']) > 0) ? $action['action_date_caop_get'] : '---';
								$action_date_printer_format = (strlen($action['action_date_printer']) > 0) ? $action['action_date_printer'] : '---';
								$action_date_bad_begin_format = (strlen($action['action_date_bad_begin']) > 0) ? $action['action_date_bad_begin'] : '---';
								$action_date_courier_format = (strlen($action['action_date_courier']) > 0) ? $action['action_date_courier'] : '---';
								?>
                                
                                <tr>
                                    <td width="1%">
                                        <b>Добавлено:</b> <?= nbsper(date(DMYHI, $action['action_date_add_unix'])); ?>
                                    </td>
                                    <td width="1%">
                                        <b>Обновлено:</b> <?= nbsper(date(DMYHI, $action['action_date_update_unix'])); ?>
                                    </td>
                                    <td><b>Статус:</b> <?= $state; ?></td>
                                    <td><b>Получено от курьера:</b> <?= $action_date_responsed_format; ?></td>
                                    <td><b>Получено ЦАОПом:</b> <?= $action_date_caop_get_format; ?></td>
                                    <td><b>В принтере с:</b> <?= $action_date_printer_format; ?></td>
                                    <td>
                                        <button <?=super_bootstrap_tooltip('Редактировать действие');?> class="btn btn-sm btn-success">
                                            <?=BT_ICON_PEN_FILL;?>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Кабинет:</b> <?= $action['action_cabinet']; ?></td>
                                    <td><b>Плохо печатает с:</b> <?= $action_date_bad_begin_format; ?></td>
                                    <td><b>Передано хозяйке:</b> <?= $action_date_courier_format; ?></td>
                                    <td><b>Штрихкод упаковки:</b> <?= $action['action_response_pack_barcode']; ?></td>
                                    <td><b>Примечание к упаковке:</b> <?= $action['action_response_pack_description']; ?></td>
                                    <td><b>Примечание:</b> <?= $action['action_description']; ?></td>
                                    <td>
                                        <button <?=super_bootstrap_tooltip('Удалить действие');?> class="btn btn-sm btn-danger btn-actionRemove" data-cartridge="<?= $action['action_id']; ?>">
			                                <?=BT_ICON_CLOSE_LG;?>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="100">
                                        <div class="dropdown-divider"></div>
                                    </td>
                                </tr>
								<?php
							}
							?>
                        </table>
						<?php
					} else
					{
						bt_notice('С данным картриджем действий не производилось');
					}
					?>
                </td>
            </tr>
			<?php
		}
		?>
        </tbody>
    </table>
	<?php
} else
{
	bt_notice('Список картриджей пуст', BT_THEME_WARNING);
}

require_once("engine/html/modals/cartridge_modals/new_action.php");

?>

<script defer
        type="text/javascript"
        src="/engine/js/cartridges.js?<?= rand(0, 9999); ?>"></script>