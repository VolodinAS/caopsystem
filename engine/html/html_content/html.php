<!doctype html>
<html lang="ru-RU">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible"
	      content="ie=edge">
	<meta http-equiv="Cache-Control"
	      content="private">
	<title>ЦАОП | <?= $params['title']; ?></title>
	
	<!--        <script src="/engine/js/jquery/jquery-3.5.1-min.js"></script>-->
	<script src="/engine/js/plugins/jquery/jquery-3.6.0-min.js"></script>
	<script src="/engine/js/plugins/multyselect/jquery-multiselect-checkbox.min.js"></script>
	
	<!--		<script src="/engine/js/popper/popper.min.js"></script>-->
	<script src="/engine/js/plugins/popper/popper-1.14.7-min.js"></script>
	<!--		<script src="/engine/js/bootstrap/bootstrap.min.js"></script>-->
	<script src="/engine/js/plugins/bootstrap/bootstrap-4.3.1-min.js"></script>
	<script src="/engine/js/plugins/cookie/cookie.js"></script>
	<script src="/engine/js/plugins/tablesorter/tablesorter.js?1"></script>
	
	<script defer
	        src="/engine/js/plugins/uitop/easing.js"
	        type="text/javascript"></script>
	<script defer
	        src="/engine/js/plugins/uitop/jquery.ui.totop.js"
	        type="text/javascript"></script>
	<script src="/engine/js/plugins/maskedinput/maskedinput.js"></script>
	<script src="/engine/js/plugins/inputmask/inputmask.js?1"></script>
<!--	<script src="/engine/js/plugins/validate/validate.js?1"></script>-->
	<script src="/engine/js/plugins/validate/jquery.validate.min.js"></script>
	<script src="/engine/js/plugins/mark/jquery.mark.min.js"></script>
	<script src="/engine/js/plugins/autosize/autosize.js"></script>
	
	<script src="/engine/js/fixtable.js?<?= rand(0, 1000000) ?>"></script>
	<script src="/engine/js/plugins/selectize/selectize.min.js"></script>
	<!--        <script src="/engine/js/highlight/highlight.js"></script>-->
	
	<!--        LIGHTBOX-->
	<link rel="stylesheet"
	      href="/engine/module/lightbox/css/lightbox.min.css">
	<script defer
	        language="JavaScript"
	        type="text/javascript"
	        src="/engine/module/lightbox/js/lightbox.min.js"></script>


    <link rel="stylesheet"
          href="/engine/css/bootstrap/bootstrap.css">
    <link rel="stylesheet"
          href="/engine/bootstrap-icons-1.5.0/bootstrap-icons.css">

    <link rel="stylesheet"
          href="/engine/css/uitop/ui.totop.css">
    <link rel="stylesheet"
          href="/engine/css/table-fixed-dead.css?<?= rand(0, 1000000) ?>">
    <link rel="stylesheet"
          href="/engine/css/checkbox-switcher.css?<?= rand(0, 1000000) ?>">
    <link rel="stylesheet"
          href="/engine/js/plugins/selectize/selectize.css">
	
	<script src="/engine/js/mysqleditor/MySQLEditor.js?<?= rand(0, 999999); ?>"></script>
	<script src="/engine/js/mysqleditor/MySQLEditor.ModalEditor.js?<?= rand(0, 999999); ?>"></script>
	<script src="/engine/js/mysqleditor/MySQLEditor.Filters.js?<?= rand(0, 999999); ?>"></script>
	
	
	<script src="/engine/js/main.js?<?= rand(0, 999999); ?>"></script>
	<!--		<script src="/engine/js/mkbInput.js?--><? //=rand(0,999999);?><!--"></script>-->
	
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	
	<link rel="stylesheet"
	      href="/engine/css/main.css?<?= rand(0, 999999); ?>">
	<link rel="stylesheet"
	      href="/engine/css/css-table.css?<?= rand(0, 999999); ?>">
	<link rel="icon"
	      type="image/png"
	      href="/engine/images/logo/main_logo.png?<?= rand(0, 999999); ?>"/>
</head>
<body>
    <?php
    if ( $params['profile']['doctor_id'] > 0 )
    {
        global $AdminParams;
//        debug($AdminParams);
        $HeaderWarning = $AdminParams['data']['header_warning']['param_value'];
	    if ($HeaderWarning)
	    {
		    $HeaderWarningText = $AdminParams['data']['header_warning_text']['param_value'];
		    $HeaderWarningTheme = $AdminParams['data']['header_warning_theme']['param_value'];
		    bt_notice($HeaderWarningText, $HeaderWarningTheme, false, 'mb-0');
	    }
	    
	    $DoctorHome = getHomeVisits(null, $params['profile']['doctor_id'], 1);
	    $DoctorHome_count = count($DoctorHome);
	    if ( $DoctorHome_count > 0 )
	    {
	        $docIO = mb_ucwords($params['profile']['doctor_i'] . ' ' . $params['profile']['doctor_o']);
	        $hv_ender = wordEnd($DoctorHome_count, 'ВЫЗОВ', 'ВЫЗОВА', 'ВЫЗОВОВ');
	        $hvprep_ender = wordEnd($DoctorHome_count, 'ПОДГОТОВЛЕН', 'ПОДГОТОВЛЕНО', 'ПОДГОТОВЛЕНО');
	        bt_notice('Уважаемый ' . wrapper($docIO) . '! ДЛЯ ВАС '.$hvprep_ender.' '.wrapper($DoctorHome_count. ' ' . $hv_ender).' НА ДОМ! Его нужно ОБРАБОТАТЬ. Нажмите <a href="/doctor_home" target="_blank"><b>здесь</b></a>, чтобы это сделать...', BT_THEME_PRIMARY);
	    }
    }
    ?>