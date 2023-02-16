<?php
//debug($request_params);
if (strlen($request_params) > 0)
{
	$RequestData = explode("/", $request_params);
	$PRE_BASE = $RequestData[0];
	$PRE_TARGET = $RequestData[1];
}
?>
<form id="form_mergeSettings">
    <div class="row">
        <div class="col">
            <input type="text"
                   id="base"
                   class="form-control form-control-lg"
                   placeholder="BASE | ID, куда надо влить"
                   value="<?= $PRE_BASE; ?>">
            <div id="baseData">

            </div>
        </div>
        <div class="col-auto">
            <button class="btn btn-lg btn-primary swapIDs text-center"><?= BT_ICON_SWAP; ?></button>
        </div>
        <div class="col">
            <input type="text"
                   id="target"
                   class="form-control form-control-lg"
                   placeholder="TARGET | ID, которую надо влить в BASE"
                   value="<?= $PRE_TARGET; ?>">
            <div id="targetData">

            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col">
        <button type="button"
                class="btn btn-lg btn-success col"
                id="equalizer">СРАВНИТЬ
        </button>
    </div>
</div>

<div class="row">

    <div class="col-6">
        <div class="dropdown-divider"></div>
        <div id="baseHtml">

        </div>
    </div>
    <div class="col-6">
        <div class="dropdown-divider"></div>
        <div id="targetHtml">

        </div>
    </div>

</div>

<div class="row"
     id="button-hide">
    <div class="col">
        <button type="button"
                class="btn btn-warning btn-lg col"
                id="button-merge"><============ ПРОВЕСТИ СЛИЯНИЕ <============
        </button>
    </div>
</div>

<script defer
        type="text/javascript"
        src="/engine/js/admin/admin_merge.js?<?= rand(0, 999999); ?>"></script>