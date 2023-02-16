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
			<td><h5>Настройка графика УЗИ для врача <?=docNameShort($DoctorData);?>:</h5></td>
		</tr>
	</table>
	<div class="dropdown-divider"></div>
	<button class="btn btn-sm btn-success btn-refresh">Обновить</button>
	<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#graphicAddDay">Добавить 1 день</button>
	<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#graphicAddWeek">Добавить 1 неделю</button>
	<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#graphicClear">Очистить график</button>
	
	<div class="dropdown-divider"></div>

    <br>
    <div id="calendar" data-unixstamp="0" data-uziid="<?=$UziDoctor['uzi_id'];?>">
        <div class="input-group input-group-sm" id="calendar_loader">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>

    <script defer type="text/javascript">
        
        $( document ).ready(function()
        {
            setTimeout(function ()
            {
                loadCalendar( <?=time();?> )
            }, 750);
        });
    </script>
	
	
    <?php
	
	
	
	echo $response['htmlData'];
    
    ?>
	
	
    <?php
	require_once ("engine/html/modals/uziSchedule/addDay.php");
	require_once ("engine/html/modals/uziSchedule/addWeek.php");
	require_once ("engine/html/modals/uziSchedule/addMonth.php");
	require_once ("engine/html/modals/uziSchedule/graphicClear.php");
} else
{
	bt_notice('Врача в списке не найдено', BT_THEME_DANGER);
}

/**
 * <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#graphicAddMonth">Добавить 1 месяц</button>
 */