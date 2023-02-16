<?php
//debug($_SESSION);

$mefb_patid_name = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'patid_name',
	'table' => CAOP_ZNO_DU,
	'field' => 'patid_name',
	'relatedfield' => 1,
	'type' => MEF_TEXT,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По Ф.И.О.',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_diagnosis_mkb = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_diagnosis_mkb',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_diagnosis_mkb',
	'relatedfield' => 0,
	'type' => MEF_CHECKBOX,
	'use_equal' => 1,
	'preprocessor' => 'mkbNoSub',
	'postprocessor' => 'statePercent',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По МКБ',
	'queryset' => $mysql_filters_queryset
));

$mefb_patid_birth_unix = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'patid_birth_unix',
	'table' => CAOP_ZNO_DU,
	'field' => 'patid_birth_unix',
	'relatedfield' => 1,
	'type' => MEF_DATE,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По дате рождения',
	'queryset' => $mysql_filters_queryset
));

$mefb_zno_apk = mysqleditor_filters_button(array(
    'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_apk',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_apk',
	'relatedfield' => 0,
	'type' => MEF_CHECKBOX,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По АПК',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_date_first_visit_caop = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_date_first_visit_caop',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_date_first_visit_caop',
	'relatedfield' => 0,
	'type' => MEF_DATE,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По первому посещению ЦАОП',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_tnm_t = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_tnm_t',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_tnm_t',
	'relatedfield' => 0,
	'type' => MEF_CHECKBOX,
	'use_equal' => 1,
	'preprocessor' => 'onlyMainState',
	'postprocessor' => 'statePercent',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По T-стадии',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_tnm_n = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_tnm_n',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_tnm_n',
	'relatedfield' => 0,
	'type' => MEF_CHECKBOX,
	'use_equal' => 1,
	'preprocessor' => 'onlyMainState',
	'postprocessor' => 'statePercent',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По N-стадии',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_tnm_m = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_tnm_m',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_tnm_m',
	'relatedfield' => 0,
	'type' => MEF_CHECKBOX,
	'use_equal' => 1,
	'preprocessor' => 'onlyMainState',
	'postprocessor' => 'statePercent',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По M-стадии',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_tnm_s = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_tnm_s',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_tnm_s',
	'relatedfield' => 0,
	'type' => MEF_CHECKBOX,
	'use_equal' => 1,
	'preprocessor' => 'onlyMainState',
	'postprocessor' => 'statePercent',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По стадии',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_method_type = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_method_type',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_method_type',
	'relatedfield' => 1,
	'type' => MEF_CHECKBOX,
	'use_equal' => 0,
	'preprocessor' => 'getMorphTitle',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По методу верификации',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_method_date = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_method_date',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_method_date',
	'relatedfield' => 0,
	'type' => MEF_DATE,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По дате верификации',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_date_dir_in_gop = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_date_dir_in_gop',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_date_dir_in_gop',
	'relatedfield' => 0,
	'type' => MEF_DATE,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По дате направления в ГОП',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_date_issue_notice = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_date_issue_notice',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_date_issue_notice',
	'relatedfield' => 0,
	'type' => MEF_DATE,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По дате подачи извещения',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_doctor_id = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_doctor_id',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_doctor_id',
	'relatedfield' => 1,
	'type' => MEF_CHECKBOX,
	'use_equal' => 0,
	'preprocessor' => 'getDoctorName',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По врачу',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_survey_type = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_survey_type',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_survey_type',
	'relatedfield' => 1,
	'type' => MEF_CHECKBOX,
	'use_equal' => 0,
	'preprocessor' => 'getSurveyName',
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По типу обследования',
	'queryset' => $mysql_filters_queryset
));

$mef_zno_date_set_zno = mysqleditor_filters_button(array(
	'class' => 'btn btn-secondary btn-sm',
	'filter' => 'zno_date_set_zno',
	'table' => CAOP_ZNO_DU,
	'field' => 'zno_date_set_zno',
	'relatedfield' => 0,
	'type' => MEF_DATE,
	'use_equal' => 1,
	'default_icon' => BT_ICON_REFRESH,
	'default_icon_ok' => BT_ICON_OK,
	'default_icon_close' => BT_ICON_CLOSE_LG,
	'default_icon_empty' => BT_ICON_COPY,
	'title' => '',
	'header' => 'По установке ЗНО',
	'queryset' => $mysql_filters_queryset
));