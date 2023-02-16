<?php
$UziDoctor = getarr(CAOP_SCHEDULE_UZI_DOCTORS, "uzi_doctor_id='$ParamPage'");
if ( count($UziDoctor) == 1 )
{
	$UziDoctor = $UziDoctor[0];
	$DoctorData = $DoctorsListId['id' . $UziDoctor['uzi_doctor_id']];
	?>
	<table>
		<tr>
			<td width="1%">
				<button class="btn btn-secondary btn-sm" onclick="location.href='/uziSchedule'">Вернуться</button>
			</td>
			<td><h5>Настройка расписания УЗИ для врача <?=docNameShort($DoctorData);?>:</h5></td>
		</tr>
	</table>
 
    
    <button class="btn btn-sm btn-primary btn-addShift" data-uziid="<?=$UziDoctor['uzi_id'];?>" data-doctorid="<?=$UziDoctor['uzi_doctor_id'];?>">Создать смену</button>
    <button class="btn btn-sm btn-warning btn-copyFullShift" data-uziid="<?=$UziDoctor['uzi_id'];?>" data-doctorid="<?=$UziDoctor['uzi_doctor_id'];?>">Скопировать все смены другому врачу</button>
    <div class="dropdown-divider"></div>
    <h6>Доступные смены:</h6>
    <div class="dropdown-divider"></div>
    <?php
    $ShiftsData = getarr(CAOP_SCHEDULE_UZI_SHIFTS, "shift_uzi_id='{$UziDoctor['uzi_id']}' AND shift_doctor_id='{$UziDoctor['uzi_doctor_id']}'", "ORDER BY shift_title ASC");
    if ( count($ShiftsData) > 0 )
    {
        ?>
        <table>
        <?php
	    foreach ($ShiftsData as $shiftsDatum)
	    {
            ?>
            <tr>
                <td width="1%">
                    <button class="btn btn-warning btn-removeShift" data-shiftid="<?=$shiftsDatum['shift_id'];?>">
                        <?=BT_ICON_CLOSE;?>
                    </button>
                </td>
                <td>
                    <input type="text" class="form-control mysqleditor" placeholder="Введите название смены. Например, 'I смена'" value="<?=$shiftsDatum['shift_title'];?>" data-action="edit"
                    data-table="<?=CAOP_SCHEDULE_UZI_SHIFTS;?>"
                    data-assoc="0"
                    data-fieldid="shift_id"
                    data-id="<?=$shiftsDatum['shift_id'];?>"
                    data-field="shift_title">
                </td>
                <td width="10%">
                    <button class="btn btn-warning btn-copyShift" data-shiftid="<?=$shiftsDatum['shift_id'];?>" <?=super_bootstrap_tooltip('Дублировать смену');?>>
                        <?=BT_ICON_COPY;?>
                    </button>

                    <button class="btn btn-warning btn-copyOtherShift" data-shiftid="<?=$shiftsDatum['shift_id'];?>" <?=super_bootstrap_tooltip('Скопировать другому врачу');?>>
		                <?=BT_ICON_PEOPLE;?>
                    </button>
                </td>
            </tr>
            <?php
            $TimesData = getarr(CAOP_SCHEDULE_UZI_TIMES, "time_uzi_id='{$shiftsDatum['shift_uzi_id']}' AND time_shift_id='{$shiftsDatum['shift_id']}'", "ORDER BY time_order ASC");
            if ( count($TimesData) > 0 )
            {
	            foreach ($TimesData as $timesDatum)
	            {
                ?>
                    <tr>
                        <td width="1%">
                            <button class="btn btn-danger btn-removeTime" data-timeid="<?=$timesDatum['time_id'];?>">
			                    <?=BT_ICON_CLOSE;?>
                            </button>
                        </td>
                        <td colspan="2">
                            <label class="sr-only" for="time"><?=$timesDatum['time_hour'];?>:<?=$timesDatum['time_min'];?></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <!--ICON HERE-->
	                                    <?=$timesDatum['time_hour'];?>:<?=$timesDatum['time_min'];?>:
                                    </div>
                                </div>
                                <input type="text" class="form-control russianTime col-1 mysqleditor" placeholder="__:__" value="<?=$timesDatum['time_hour'];?>:<?=$timesDatum['time_min'];?>" data-action="edit"
                                data-table="<?=CAOP_SCHEDULE_UZI_TIMES;?>"
                                data-assoc="0"
                                data-fieldid="time_id"
                                data-id="<?=$timesDatum['time_id'];?>"
                                data-field="SEPARATOR"
                                data-adequate="HOURMIN"
                                data-separator=";|||time_hour;time_min">
                            </div>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <label class="sr-only" for="time">Добавить время:</label>
                    <div class="input-group mb-2">
                    	<div class="input-group-prepend">
                    		<div class="input-group-text">
                    			<!--ICON HERE-->
                                Добавить время:
                    		</div>
                    	</div>
                        <input type="text" class="form-control russianTime col-1 enter-shift-new" id="shift_<?=$shiftsDatum['shift_id'];?>_value" placeholder="__:__" data-shiftid="<?=$shiftsDatum['shift_id'];?>">
                    </div>
                </td>
                <td width="1%">
                    <button class="btn btn-success btn-addTime" data-shiftid="<?=$shiftsDatum['shift_id'];?>">
		                <?=BT_ICON_OK;?>
                    </button>
                </td>
            </tr>
            <?php
        }
	    ?>
        </table>
	    <?php
    } else
    {
        bt_notice('Пока еще не создано ни одной смены', BT_THEME_WARNING);
    }
    ?>
 
	<?php
    
    require_once ("engine/html/modals/uziSchedule/modal_shift.php");
} else
{
	bt_notice('Врача в списке не найдено', BT_THEME_DANGER);
}