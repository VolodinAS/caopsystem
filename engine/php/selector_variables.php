<?php
/**
 * USAGES:
 *
 * 1 - engine/php/processor/journal/showResearchData.php    - 1
 * 2 - engine/php/processor/research/researchCard.php       - 1
 * 3 - engine/html/research_patientlist.php                 - cycle
 * 4 - engine/php/processor/journal/journalCard.php         - 1
 */
//$ResearchTypes = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
//$ResearchTypesId = getDoctorsById($ResearchTypes, 'type_id');

//$ResearchStatuses = getarr(CAOP_RESEARCH_STATUS, "1", "ORDER BY status_id ASC");
//$ResearchCitos = getarr(CAOP_RESEARCH_CITO, "1", "ORDER BY cito_id ASC");
//$ResearchTypesHead = getarr(CAOP_RESEARCH_TYPES, "type_enabled='1' AND type_forresearch='1'", "ORDER BY type_order ASC");

$ResearchStatusesIcons = array(
    'id1' => BT_ICON_STOP,
	'id2' => BT_ICON_PEOPLE,
	'id3' => BT_ICON_PHONE,
	'id4' => BT_ICON_CASE_ALLGOOD
);


//$JournalInfirst = getarr(CAOP_INFIRST, 1, "ORDER BY infirst_id ASC");
//$JournalInfirstId = getDoctorsById($JournalInfirst, 'infirst_id');

//$CaopPersonal = getarr(CAOP_DOCTOR);
//$CaopPersonalId = getDoctorsById($CaopPersonal);

$DoctorsList = getarr(CAOP_DOCTOR, " doctor_isdoc='1' OR doctor_ds='1'", "ORDER BY doctor_f, doctor_i, doctor_o ASC");
$DoctorsListId = getDoctorsById($DoctorsList);
//$DoctorsListBirths = getarr(CAOP_DOCTOR, "doctor_isBirth='1'", "ORDER BY doctor_birth_unix ASC");

$DoctorsNurse = getarr(CAOP_DOCTOR, " doctor_isnurse='1' AND doctor_enabled='1'");
$DoctorsNurseId = getDoctorsById($DoctorsNurse);

//$DoctorsListUzi = getarr(CAOP_DOCTOR, "doctor_isUzi='1'");
//$DoctorsListUziId = getDoctorsById($DoctorsListUzi);

$UpdaterAccessModerator = array( '2', '3' );

//$SexArray = getarr(CAOP_SEX, "1", "ORDER BY sex_id ASC");
//$SexTypesId = getDoctorsById($SexArray, 'sex_id');

$Lpu = getarr(CAOP_LPU, "1", "ORDER BY lpu_id ASC");
$LpuId = getDoctorsById($Lpu, 'lpu_id');

//$CountySideArea = getarr(CAOP_COUNTRYSIDE_AREA, 1, "ORDER BY area_id ASC");
//$CountySideAreaTypesId = getDoctorsById($CountySideArea, 'area_id');

//$MorphTypes = getarr(CAOP_MORPH_TYPE, 1, "ORDER BY morph_type_id ASC");
//$MorphTypesId = getDoctorsById($MorphTypes, 'morph_type_id');

//$MorphConfirm = getarr(CAOP_MORPH_YES_NO, 1, "ORDER BY confirm_id ASC");
//$MorphConfirmId = getDoctorsById($MorphConfirm, 'confirm_id');

//$CitologyTypes = getarr(CAOP_CITOLOGY_TYPE, 1, "ORDER BY type_id ASC");
//$CitologyTypesId = getDoctorsById($CitologyTypes, 'type_id');

//$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
//$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');

//$CaseStatusesList = getarr(CAOP_CASESTATUS, "casestatus_enabled='1'", "ORDER BY casestatus_order ASC");
//$CaseStatusesListId = getDoctorsById($CaseStatusesList, 'casestatus_id');

//$DirStacList = getarr(CAOP_DIRSTAC, "dirstac_enabled='1'", "ORDER BY dirstac_order ASC");
//$DirStacListId = getDoctorsById($DirStacList, 'dirstac_id');
//$DirStacListId['id0'] = array('dirstac_title'=>'Никуда');

//$DOSE_MEASURE_TYPES = getarr(CAOP_DS_DOSE_MEASURE_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
//$DOSE_MEASURE_TYPES_ID = getDoctorsById($DOSE_MEASURE_TYPES, 'type_id');

//$DOSE_PERIOD_TYPES = getarr(CAOP_DS_DOSE_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
//$DOSE_PERIOD_TYPES_ID = getDoctorsById($DOSE_PERIOD_TYPES, 'type_id');

//$DOSE_FREQ_PERIOD_TYPES = getarr(CAOP_DS_FREQ_PERIOD_TYPE, "type_enabled='1'", "ORDER BY type_order ASC");
//$DOSE_FREQ_PERIOD_TYPES_ID = getDoctorsById($DOSE_FREQ_PERIOD_TYPES, 'type_id');

//$DS_RESEARCH_TYPES = getarr(CAOP_DS_RESEARCH_TYPES, "type_enabled='1'", "ORDER BY type_order ASC");
//$DS_RESEARCH_TYPES_ID = getDoctorsById($DS_RESEARCH_TYPES, 'type_id');
//$DS_RESEARCH_TYPES_LINEAR = getLinearIds($DS_RESEARCH_TYPES, 'type_id');

//$DispLPU = getarr(CAOP_DISP_LPU, 1, "ORDER BY lpu_order ASC");
//$DispLPUId = getDoctorsById($DispLPU, 'lpu_id');

$MKBDisp = getarr(CAOP_MKB_DISP, 1);
$MKBDispId = getDoctorsById($MKBDisp, 'mkbdisp_id');
$MKBDispLinear = getLinkFields($MKBDisp, 'mkbdisp_id', 'mkbdisp_code');

//$SPOReasonTypes = getarr(CAOP_SPO_REASON_TYPES, "reason_enabled='1'", "ORDER BY reason_type_order ASC");
//$SPOReasonTypesId = getDoctorsById($SPOReasonTypes, 'reason_type_id');

//$AreasUZI = getarr(CAOP_SCHEDULE_UZI_RESEARCH_AREA, "1", "ORDER BY area_title ASC");
//$AreasUZIId = getDoctorsById($AreasUZI, 'area_id');

//$MsktTypes = getarr(CAOP_MSKT_DIR_TYPES, 1, "ORDER BY type_order ASC");
//$MsktTypesId = getDoctorsById($MsktTypes, 'type_id');

//$CartridgeTypes = getarr(CAOP_CARTRIDGE_ACTION_TYPES, 1, "ORDER BY type_id ASC");
//$CartridgeTypesId = getDoctorsById($CartridgeTypes, 'type_id');

//$CaopSPOReasons = getarr(CAOP_SPO_REASON_TYPES, 1, "ORDER BY reason_type_order ASC");
//$CaopSPOReasonsId = getDoctorsById($CaopSPOReasons, 'reason_type_id');

//$CaopSPOUnsetReasons = getarr(CAOP_SPO_ACCOUNTING_UNSET_REASON_TYPES, 1, "ORDER BY type_order ASC");
//$CaopSPOUnsetReasonsIds = getDoctorsById($CaopSPOUnsetReasons, 'type_id');

$STNMG = getarr(CAOP_STNMG, 1, "ORDER BY stnmg_order ASC");
$ZNODUMorphMethod = getarr(CAOP_ZNO_MORPH_TYPE, "1", "ORDER BY morph_order ASC");
$ZNODUMorphMethodId = getDoctorsById($ZNODUMorphMethod, 'morph_id');

$AdminParams = admin_param();
$AdminParamsTypes = getarr(CAOP_PARAMS_TYPES, 1, "ORDER BY {$PK[CAOP_PARAMS_TYPES]} ASC");

/**
 * $CAOP_BLANK_DECLINE - бланки отказа
 * $CAOP_CANCER - информация о раках пациента
 * $CAOP_CITOLOGY - информация о цитологии пациента
 * $CAOP_DAILY - дневники
 * $CAOP_DIR_057 - направления 057У
 * $CAOP_JOURNAL - информация о визитах пациента
 * $CAOP_MSKT_MDC - направления на МСКТ в МДЦ
 * $CAOP_NOTICE_F1A - информация об 1А кл. гр.
 * $CAOP_RESEARCH - информация об обследованиях пациентов
 * $CAOP_ROUTE_SHEET - информация о маршрутных листах пациента
 * $CAOP_SCHEDULE_UZI_PATIENTS - информация об УЗИ пациентах
 * $CAOP_SPO - информация об СПО пациентов
 */

/**
 * ТАБЛИЦЫ, ГДЕ ФИГУРИРУЕТ PATID_ID - НЕОБХОДИМО ДЛЯ ПРАВИЛЬНОГО СЛИЯНИЯ ДАННЫХ ДУБЛЕЙ ПАЦИЕНТОВ
 */
$PATIENTS_DATAS = array(
	'spo' => array(
		'title' => 'СПО',
		'field_id' => 'spo_id',
		'field_patid' => 'spo_patient_id',
		'table' => CAOP_SPO,
		'is_doc' => true
	),
	'blank_decline' => array(
		'title' => 'Бланки отказа',
		'field_id' => 'decline_id',
		'field_patid' => 'decline_patid',
		'table' => CAOP_BLANK_DECLINE,
		'is_doc' => true
	),
	'cancer' => array(
		'title' => 'Установленные ЗНО',
		'field_id' => 'cancer_id',
		'field_patid' => 'cancer_patid',
		'table' => CAOP_CANCER,
		'is_doc' => false
	),
	'citology' => array(
		'title' => 'Цитология',
		'field_id' => 'citology_id',
		'field_patid' => 'citology_patid',
		'table' => CAOP_CITOLOGY,
		'is_doc' => true
	),
	'dailys' => array(
		'title' => 'Дневники',
		'field_id' => 'daily_id',
		'field_patid' => 'daily_patid',
		'table' => CAOP_DAILY,
		'is_doc' => true
	),
	'dir_057' => array(
		'title' => 'Направления 057У',
		'field_id' => 'dir_id',
		'field_patid' => 'dir_patient_id',
		'table' => CAOP_DIR_057,
		'is_doc' => true
	),
	'journal' => array(
		'title' => 'Журнал визитов',
		'field_id' => 'journal_id',
		'field_patid' => 'journal_patid',
		'table' => CAOP_JOURNAL,
		'is_doc' => true
	),
	'morphology' => array(
		'title' => 'Направления на гистологию',
		'field_id' => 'morph_id',
		'field_patid' => 'morph_patient_id',
		'table' => CAOP_MORPHOLOGY,
		'is_doc' => true
	),
	'mskt_mdc' => array(
		'title' => 'Направление на МСКТ в МДЦ',
		'field_id' => 'mskt_id',
		'field_patid' => 'mskt_patient_id',
		'table' => CAOP_MSKT_MDC,
		'is_doc' => true
	),
	'notice_f1a' => array(
		'title' => '1А извещение',
		'field_id' => 'f1a_id',
		'field_patid' => 'f1a_patid',
		'table' => CAOP_NOTICE_F1A,
		'is_doc' => true
	),
	'research' => array(
		'title' => 'Журнал исследований',
		'field_id' => 'research_id',
		'field_patid' => 'research_patid',
		'table' => CAOP_RESEARCH,
		'is_doc' => true
	),
	'route' => array(
		'title' => 'Маршрутные листы',
		'field_id' => 'rs_id',
		'field_patid' => 'rs_patid',
		'table' => CAOP_ROUTE_SHEET,
		'is_doc' => true
	),
	'uzi_schedule' => array(
		'title' => 'УЗИ ЦАОП ТАЛОНЫ',
		'field_id' => 'patient_id',
		'field_patid' => 'patient_pat_id',
		'table' => CAOP_SCHEDULE_UZI_PATIENTS,
		'is_doc' => true
	)
);