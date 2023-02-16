


<?php
spoiler_begin('Добавить новый параметр', 'add_parameter');
{
    $adminTypesDefault = array(
        'key' => '',
        'value' => 'Тип параметра'
    );
    $adminTypesSelector = array2select($AdminParamsTypes, 'param_type_id', 'param_type_desc', 'param_type',
    'class="form-control" id="param_type" required', $adminTypesDefault);
?>
    <form id="form_add_admin_param">
        <div class="form-group">
            <label for="param_name">Название параметра:</label>
            <input type="text" class="form-control" id="param_name" name="param_name" placeholder="Название" required>
        </div>
        <div class="form-group">
            <label for="param_desc">Описание:</label>
            <input type="text" class="form-control" id="param_desc" name="param_desc" placeholder="Описание" required>
        </div>
        <div class="form-group">
            <label for="param_placeholder">Placeholder:</label>
            <input type="text" class="form-control" id="param_placeholder" name="param_placeholder">
        </div>
        <div class="form-group">
            <label for="param_type">Тип параметра:</label>
            <?=$adminTypesSelector['result'];?>
        </div>
        <div class="form-group">
            <button class="btn btn-primary col btn-addAdminParam">Добавить</button>
        </div>
    </form>
<?php
}
spoiler_end();
?>
<div class="dropdown-divider"></div>
<?php
//$Params = getarr(CAOP_PARAMS, null, "ORDER BY param_name ASC");
//debug($AdminParams);
$Params = $AdminParams['data'];

if (count($Params) > 0)
{
	foreach ($Params as $param)
	{
		?>
        <div class="row">
            <div class="col">
                <h4><?=$param['param_desc'];?></h4>
                <div class="dropdown-divider"></div>
                <b><?= $param['param_name']; ?></b>
            </div>
            <div class="col-auto d-flex justify-content-center">
                <div class="m-auto">
                    <?php
                    $PARAM_DATA = array(
                        'id' => $param['param_id'],
                        'fieldid' => 'param_id',
                        'field' => 'param_value',
                        'unix' => 'param_unix_update',
                        'value' => $param['param_value'],
                        'placeholder' => $param['param_placeholder']
                    );
//                    debug($PARAM_DATA);
                    include ("engine/php/tools/admin-param-types-processor/param-type-{$param['param_type']}.php");
                    ?>
                </div>
            </div>
        </div>
        <br>
		<?php
	}
}
?>
<script defer type="text/javascript" src="/engine/js/checkbox-switcher/index.js"></script>
<script defer type="text/javascript" src="/engine/js/admin/admin_params.js?<?=rand(0, 9999);?>"></script>
