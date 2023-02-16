<?php

if ( $USER_PROFILE['access_level'] == 2 )
{
?>
<!--    <button class="btn btn-danger"-->
<!--        onclick="MySQLEditorAction(this, true)"-->
<!--        data-action="add"-->
<!--        data-table="--><?//=CAOP_CAT_SYSTEM;?><!--"-->
<!--        data-assoc="0"-->
<!--        data-fieldid="--><?//=$PK[CAOP_CAT_SYSTEM];?><!--"-->
<!--        data-field="cat_content"-->
<!--        data-value="test"-->
<!--    >ADD TEST</button>-->
    <button
            class="btn btn-primary mysqleditor-modal-form"
            id="myemf_button_add"
            type="button"
            data-action="add"
            data-table="<?=CAOP_CAT_SYSTEM;?>"
            data-fieldid="<?=$PK[CAOP_CAT_SYSTEM];?>"
            data-title="Добавление CAT"
    >
        Добавить CAT
    </button>
    <div class="dropdown-divider"></div>
    <?php
    $Cats = getarr(CAOP_CAT_SYSTEM, 1, "ORDER BY ".$PK[CAOP_CAT_SYSTEM]." ASC");
    if ( count($Cats) > 0 )
    {
        ?>
        <table class="table" id="cats_sorter">
            <thead>
                <tr>
                    <th scope="col" class="text-center" width="1%" data-title="ID">ID</th>
                    <th scope="col" class="text-center" data-title="desc">Описание</th>
                    <th scope="col" class="text-center" data-title="content">Содер-жимое</th>
                    <th scope="col" class="text-center" data-title="compare">Соответ-ствие</th>
                    <th scope="col" class="text-center" data-title="created">Создан</th>
                    <th scope="col" class="text-center" data-title="remain">Осталось, дни</th>
                    <th scope="col" class="text-center" data-title="approved">Одобрен</th>
                    <th scope="col" class="text-center" data-title="last_use">Последнее использование</th>
                    <th scope="col" class="text-center" data-title="auto_renewal">Авто-обновление</th>
                    <th scope="col" class="text-center sorter-false" data-title="actions">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($Cats as $cat)
                {
                    $content = $cat['cat_content'];
                    $content1 = substr($cat['cat_content'], 0, 5);
                    $content2 = substr($cat['cat_content'], -5);
                    
                    $content = $content1 . ' ... ' . $content2;
                    
                    $last_use = 'неизвестно';
                    $last_use_ip = '';
                    if ( $cat['cat_last_use_unix'] > 0 )
                    {
                        $last_use = date(DMYHIS, $cat['cat_last_use_unix']);
                        $last_use_ip = super_bootstrap_tooltip($cat['cat_last_use_ip']);
                    }
                    
                    $auto_renewal_icon = BT_ICON_CLOSE_LG;
                    $auto_renewal_button = 'вкл.';
                    if ($cat['cat_auto_renewal'] > 0)
                    {
                        $auto_renewal_icon = BT_ICON_OK;
                        $auto_renewal_button = 'выкл.';
                    }
                    
                    $approve_button = 'Запретить';
                    $approve_icon = BT_ICON_CASE_ALLGOOD;
                    $approve_date = super_bootstrap_tooltip($cat['cat_approved_date']);
                    if ($cat['cat_approved'] == 0)
                    {
                        $approve_button = 'Одобрить';
                        $approve_icon = BT_ICON_CLOSE_CIRCLE;
                        $approve_date = '';
                    }
                    
                    $compare_vanilla = substr_compare(CAT, $cat['cat_content'], 0);
                    if ($compare_vanilla == 0)
                    {
                        $compare_icon = BT_ICON_CASE_ALLGOOD;
                    } else
                    {
                        $compare_icon = BT_ICON_CLOSE_CIRCLE;
                    }
                    
                    $future_unix = strtotime("+1 year", $cat['cat_approved_date_unix']);
                    $remain_unix = $future_unix - time();
                    $remain = floor($remain_unix / TIME_DAY);
                    ?>
                    <tr>
                        <td data-cell="ID" class="text-center">
                            <?=$cat['cat_id'];?>
                        </td>
                        <td data-cell="desc">
                            <?=$cat['cat_desc'];?>
                        </td>
                        <td data-cell="content">
                            <?=$content;?>
                        </td>
                        <td data-cell="compare" class="text-center" id="compare_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>">
                            <span class="nondisplay"><?=$compare_vanilla;?></span>
                            <span <?=super_bootstrap_tooltip($compare_vanilla);?>>
                                <?=$compare_icon;?>
                            </span>
                        </td>
                        <td data-cell="created" class="text-center">
                            <span class="nondisplay"><?=$cat['cat_date_unix'];?></span>
                            <?=$cat['cat_date'];?>
                        </td>
                        <td data-cell="remain" class="text-center">
                            <?=$remain;?>
                        </td>
                        <td data-cell="approved" class="text-center" id="approved_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>">
                            <span class="nondisplay"><?=$cat['cat_approved_date_unix'];?></span>
                            <span <?=$approve_date;?>>
                                <?=$approve_icon;?>
                            </span>
                        </td>
                        <td data-cell="last_use" class="text-center" id="last_use_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>">
                            <span class="nondisplay">
                                <?=$cat['cat_last_use_unix'];?>
                            </span>
                            <span <?=$last_use_ip;?>>
                                <?=$last_use;?>
                            </span>
                        </td>
                        <td data-cell="auto_renewal" class="text-center" id="auto_renewal_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>">
                            <span class="nondisplay"><?=$cat['auto_renewal'];?></span>
                            <?=$auto_renewal_icon;?>
                        </td>
                        <td data-cell="actions" class="text-center">
                            <!-- Кнопка выпадающая влево по умолчанию -->
                            <div class="dropdown dropleft">
                                <button
                                        class="btn btn-sm btn-secondary dropdown-toggle"
                                        type="button"
                                        id="ddm_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
                                    <?=BT_ICON_REGIMEN_TEMPLATE;?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="ddm_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>">
                                    <button
                                            class="dropdown-item mysqleditor-modal-form"
                                            id="myemf_button_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                            type="button"
                                            data-action="edit"
                                            data-table="<?=CAOP_CAT_SYSTEM;?>"
                                            data-fieldid="<?=$PK[CAOP_CAT_SYSTEM];?>"
                                            data-id="<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                            data-title="Редактирование CAT"
                                            data-fields="cat_desc"
                                    >
                                        Редактировать
                                    </button>
                                    
                                    <div class="dropdown-divider"></div>

                                    <button
                                            class="dropdown-item font-weight-bolder cat-assign"
                                            type="button"
                                            data-catid="<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                    >
                                        Присвоить этому компьютеру
                                    </button>
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <button
                                            data-id="<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                            id="approve_button_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                            class="dropdown-item btn-approve"
                                            type="button"
                                    >
                                        Одобрение: <?=$approve_button;?>
                                    </button>
                                    <button
                                            data-id="<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                            id="auto_renewal_button_<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                            class="dropdown-item btn-autorenewal"
                                            type="button"
                                    >
                                        Автопродление: <?=$auto_renewal_button;?>
                                    </button>
                                    
                                    <div class="dropdown-divider"></div>
                                    
                                    <button
                                            class="btn btn-danger btn-sm col font-weight-bolder"
                                            onclick="if (confirm('Вы действительно хотите удалить данную запись?')){MySQLEditorAction(this, true); window.location.reload()}"
                                            data-action="remove"
                                            data-table="<?=CAOP_CAT_SYSTEM;?>"
                                            data-assoc="0"
                                            data-fieldid="<?=$PK[CAOP_CAT_SYSTEM];?>"
                                            data-id="<?=$cat[$PK[CAOP_CAT_SYSTEM]];?>"
                                    >Удалить запись</button>
                                </div>
                            </div>
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
        bt_notice('Нет CAT');
    }
//	foreach ($Cats as $cat) {
//		debug($cat);
//		//if ( $gey['gey_approved'] == 0 )
//		//{
//		?>
<!--        <button class="btn btn-lg btn-primary catapproved" data-catid="--><?//=$cat['cat_id'];?><!--">ПРИСВОИТЬ ДАННОМУ КОМПЬЮТЕРУ</button>-->
<!--		--><?php
//		//}
//		debug( substr_compare(CAT, $cat['cat_content'], 0) );
//		echo '<hr/>';
//	}

} else
{
    bt_notice('У Вас нет доступа к данному разделу',BT_THEME_DANGER);
}

?>

<script defer src="/engine/js/admin/admin.js?<?=rand(0,999999);?>" type="text/javascript"></script>
<script defer src="/engine/js/admin/admin_cats.js?<?=rand(0,999999);?>" type="text/javascript"></script>
