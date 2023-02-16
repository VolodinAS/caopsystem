<?php

$AreasUZI = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "1", "ORDER BY area_title ASC");

if ( strlen($request_params) > 0 )
{
	$RequestParamsData = explode('/', $request_params);
//	debug($RequestParamsData);
	$SubPage = $RequestParamsData[0];
	$ParamPage = $RequestParamsData[1];
	$go_next = false;
	switch ($SubPage)
    {
		case "schedule":
		case "shifts":
		case "graphic":
		case "temps":
		case "areas":
            $go_next = true;
        break;
		default:
            bt_notice('Указанный раздел не найден. <a href="/uziSchedule"><b>Настройка графика УЗИ</b></a>', BT_THEME_WARNING);
        break;
    }
    
    if ( $go_next )
    {
        $go_next = false;
        
        require_once ("engine/html/include/uzi_schedule/".$SubPage.".php");
        
    } else
    {
	    bt_notice('Проблема с навигацией по разделу. <a href="/uziSchedule"><b>Настройка графика УЗИ</b></a>', BT_THEME_WARNING);
    }
    
} else
{
?>
    <h5>Список врачей, проводящих УЗИ:</h5>
    <div class="dropdown-divider"></div>
    <button class="btn btn-primary">Просмотреть общее расписание</button>
    <div class="dropdown-divider"></div>
	<?php
//$UziDoctors = getarr(CAOP_SCHEDULE_UZI_DOCTORS, 1, "ORDER BY");
	$doctors_free_list = array(
		'tables' => array(
			array(
				'table_name' => CAOP_SCHEDULE_UZI_DOCTORS,
			),
			array(
				'table_name' => CAOP_DOCTOR,
				'table_join' => 'LEFT JOIN',
				'table_main_field' => 'uzi_doctor_id',
				'table_field_id' => 'doctor_id'
			)
		),
		'fields' => '*',
		'where' => '1',
		'addon' => 'ORDER BY doctor_f, doctor_i, doctor_o ASC'
	);
	
	$uzi_query = table_joiner($doctors_free_list);
//debug($uzi_query);
	$uzi_result = mqc($uzi_query);
	$uzi_amount = mnr($uzi_result);
	if ($uzi_amount > 0)
	{
		$UziDoctors = mr2a($uzi_result);
		?>
        <table cellpadding="5">
			<?php
			
			$AREA_TOTAL = count($AreasUZI);
			foreach ($UziDoctors as $doctorUzi)
			{
				$AREA_SET = 0;
			    $AreasDoctor = array2KeyValueArray( json_decode( stripslashes($doctorUzi['uzi_research_area_ids']) , 1 ) );
//			    debug($AreasDoctor);
				foreach ($AreasDoctor as $area=>$isSet)
				{
                    if ($isSet) $AREA_SET++;
			    }
			 
				?>
                <tr class="uzi-doctor" id="tr_uzilist_<?=$doctorUzi['doctor_id'];?>" data-doctorid="<?=$doctorUzi['doctor_id'];?>">
                    <td width="1%"><img src="/engine/images/icons/uzi.png"
                                        alt="УЗИ"
                                        width="32"></td>
                    <td>
                        <span <?=super_bootstrap_tooltip('Доступные области исследования');?>>
                            <? badge($AREA_SET . '/' . $AREA_TOTAL, BT_THEME_PRIMARY); ?>
                        </span>
                        <?=docNameShort($doctorUzi, 'famimot');?></td>
                    <td width="1%">
                        <button <?=super_bootstrap_tooltip('Настроить врачу области исследования');?> class="btn btn-sm btn-info" onclick="location.href='/uziSchedule/areas/<?=$doctorUzi['uzi_doctor_id'];?>'">
                            <?=BT_ICON_UZI_RESEARCH_AREA;?>
                        </button>
                    </td>
                    
                    <td width="1%">
                        <button <?=super_bootstrap_tooltip('Настройка времени расписания смен');?> class="btn btn-sm btn-info" onclick="location.href='/uziSchedule/schedule/<?=$doctorUzi['uzi_doctor_id'];?>'">
                            <?=BT_ICON_DRUG_PERIOD;?>
                        </button>
                    </td>

                    <td width="1%">
                        <button <?=super_bootstrap_tooltip('Настройка недельных шаблонов');?> class="btn btn-sm btn-info" onclick="location.href='/uziSchedule/temps/<?=$doctorUzi['uzi_doctor_id'];?>'">
                            <?=BT_ICON_UZI_TEMP;?>
                        </button>
                    </td>
                    
                    <td width="1%">
                        <button <?=super_bootstrap_tooltip('Настройка графика смен');?> class="btn btn-sm btn-info" onclick="location.href='/uziSchedule/graphic/<?=$doctorUzi['uzi_doctor_id'];?>'">
                            <?=BT_ICON_PROCEDURE_AMOUNT;?>
                        </button>
                    </td>
                    
                    <td width="1%">
                        <button class="btn btn-sm btn-primary"><?=nbsper('Просмотр графика');?></button>
                    </td>
                    <td width="1%">
                        <button class="btn btn-sm btn-warning btn-addRemoveFromList" data-doctorid="<?=$doctorUzi['doctor_id'];?>">Удалить</button>
                    </td>
                </tr>
				<?php
				
			}
			?>
        </table>
		<?php
	} else
	{
		bt_notice('В списке еще нет врачей, проводящих УЗИ. Для того, чтобы добавить врача в список, выберите его из списка ниже и нажмите добавить');
	}
	?>
    <hr>
    <h5>Список доступных врачей:</h5>
    <div class="small">Щелкните на враче, чтобы добавить его в список выше</div><br>
    <table cellpadding="5">
		<?php
		foreach ($DoctorsList as $doctorItem)
		{
			if ( $doctorItem['doctor_isUzi'] == 0 )
			{
				?>
                <tr class="choose-doctor" id="tr_freelist_<?=$doctorItem['doctor_id'];?>" data-doctorid="<?=$doctorItem['doctor_id'];?>">
                    <td width="1%"><?=BT_ICON_PLUS_CALENDAR;?></td>
                    <td><?=docNameShort($doctorItem, 'famimot');?></td>
                    <td width="1%">
                        <button class="btn btn-success btn-addInList" data-doctorid="<?=$doctorItem['doctor_id'];?>"><?=nbsper('Внести в список');?></button>
                    </td>
                </tr>
				<?php
			}
			
		}
		?>
    </table>
    
<?php
}

/**
 *
 */

include ("engine/html/modals/uziSchedule/modal_uziList.php");
include ("engine/html/modals/uziSchedule/modal_editDay.php");
include ("engine/html/modals/uziSchedule/modal_uziTimeEditor.php");
?>
<script defer type="text/javascript" src="/engine/js/uziSchedule.js?<?=rand(0, 99999);?>"></script>
