<?php
$response = array();
$response['stage'] = 'init';
$response['result'] = false;
$response['msg'] = '';
$response['htmlData'] = '';

$response['$_POST'] = $_POST;
$response['$request_params'] = $request_params;

if ( isset($_POST) )
{

	if ( isset($_POST['action']) || isset($request_params) )
	{
		if ( !isset($_POST['action']) && isset($request_params) ) $action = $request_params;
		else $action = $_POST['action'];
		
//		$response['debug']['$action'] = $action;
		switch ($action)
		{
			case "reg": // reg  +
				require_once ( "engine/php/processor/authenticate/reg.php" );
				break;

			case "auth": // auth  +
				require_once ( "engine/php/processor/authenticate/auth.php" );
				break;

			case "pattern_new": // GENERAL  +
				require_once ( "engine/php/processor/patterns/pattern_new.php" );
				break;

			case "pattern_delete": // GENERAL  +
				require_once ( "engine/php/processor/patterns/pattern_delete.php" );
				break;

			case "pattern_list": // GENERAL  +
				require_once ( "engine/php/processor/patterns/pattern_list.php" );
				break;

			case "newday": // journalCurrent  +
				require_once ( "engine/php/processor/journal/newday.php" );
				break;

			case "newpat": // journalCurrent  +
				require_once ( "engine/php/processor/journal/newpat.php" );
				break;

			case "mysqleditor": // SYSTEM  +
				require_once ( "engine/php/processor/system/mysqleditor.php" );
				break;

			case "MySQLEditorModalForm": // SYSTEM  +
				require_once("engine/php/processor/system/mysqleditor.modalform.php");
				break;

			case "MySQLEditorFilters": // SYSTEM  +
				require_once("engine/php/processor/system/mysqleditor.filters.php");
				break;

			case "datemover": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/datemover.php" );
				break;

			case "datemoverAll": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/datemoverAll.php" );
				break;

			case "clear": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/clear.php" );
				break;

			case "deleteDay": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/deleteDay.php" );
				break;

			case "deletePatients": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/deletePatients.php" );
				break;

			case "remove": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/remove.php" );
				break;

			case "research": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/research.php" );
				break;

			case "journalCard": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalCard.php" );
				break;

			case "miasImport": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/miasImport.php" );
				break;

			case "miasImport2": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/miasImport2.php" );
				break;

			case "miasImportMain": // journalCurrent || GENERAL  + ССЫЛАЕМЫЙ РЕСУРС
				require_once ( "engine/php/processor/journal/miasImportMain.php" );
				break;

			case "journalCheckBlockchain": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalCheckBlockchain.php" );
				break;

			case "journalSignature": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalSignature.php" );
				break;

			case "journalCitology": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalCitology.php" );
				break;

			case "journalCitology2": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalCitology2.php" );
				break;

			case "journalResearch2": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalResearch2.php" );
				break;

			case "journalCheckCard": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalCheckCard.php" );
				break;

			case "journalWaiting": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalWaiting.php" );
				break;

			case "journalZNO": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalZNO.php" );
				break;

			case "journalCitologyNew": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalCitologyNew.php" );
				break;

			case "journalResearchNew": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalResearchNew.php" );
				break;

			case "showCitologyData": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/showCitologyData.php" );
				break;

			case "showResearchData": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/showResearchData.php" );
				break;

			case "journalGoDay": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalGoDay.php" );
				break;

			case "checkDispancerMKB": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/checkDispancerMKB.php" );
				break;

			case "journalCardSPO": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journalCardSPO.php" );
				break;

			case "newSPO": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/newSPO.php" );
				break;

			case "setSPO": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/setSPO.php" );
				break;

			case "journal_SPO_addNew": // journalCurrent || SPO  +
				require_once ( "engine/php/processor/journal/SPO/new.php" );
				break;

			case "journal_SPO_getForm": // journalCurrent || SPO  +
				require_once ( "engine/php/processor/journal/SPO/getForm.php" );
				break;

			case "journal_SPO_remove": // journalCurrent || SPO  +
				require_once ( "engine/php/processor/journal/SPO/remove.php" );
				break;

			case "journal_SPO_set": // journalCurrent || SPO  +
				require_once ( "engine/php/processor/journal/SPO/set.php" );
				break;

			case "journal_SPO_view": // journalCurrent || SPO  +
				require_once ( "engine/php/processor/journal/SPO/view.php" );
				break;

			case "suspicio": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/suspicio.php" );
				break;

			case "journal_calendarFastMove": // journalCurrent || GENERAL  +
				require_once ( "engine/php/processor/journal/journal_calendarFastMove.php" );
				break;

			case "journal_uzicaop_graphic": // journalCurrent  +
				require_once ( "engine/php/processor/journal/modals/uzicaop_graphic.php" );
				break;

			case "journal_uzicaop_schedule": // journalCurrent  +
				require_once ( "engine/php/processor/journal/modals/uzicaop_schedule.php" );
				break;

			case "journal_uzicaop_record": // journalCurrent  +
				require_once ( "engine/php/processor/journal/modals/uzicaop_record.php" );
				break;

			case "journal_uzicaop_newrecord": // journalCurrent  +
				require_once ( "engine/php/processor/journal/modals/uzicaop_newrecord.php" );
				break;

			case "journal_uzicaop_removerecord": // journalCurrent  +
				require_once ( "engine/php/processor/journal/modals/uzicaop_removerecord.php" );
				break;

			case "journalCheckData": // journalCurrent  +
				require_once ( "engine/php/processor/journal/checks/data.php" );
				break;

			case "journalCheckMoves": // journalCurrent  +
				require_once ( "engine/php/processor/journal/checks/moves.php" );
				break;

			case "newDaily": // journalCurrent  +
				require_once ( "engine/php/processor/journal/newDaily.php" );
				break;

			case "removeDaily": // journalCurrent  +
				require_once ( "engine/php/processor/journal/removeDaily.php" );
				break;

			case "journal_addDispancer": // journalCurrent || Dispancer  +
				require_once ( "engine/php/processor/journal/dispancer/new.php" );
				break;

			case "journal_removeDispancer": // journalCurrent || Dispancer  +
				require_once ( "engine/php/processor/journal/dispancer/remove.php" );
				break;

			case "journal_doctorDays": // journalCurrent  +
				require_once ( "engine/php/processor/journal/doctorDays.php" );
				break;

			case "visitToType": // journalCurrent  +
				require_once ( "engine/php/processor/journal/visitToType.php" );
				break;

			case "researchDelete": // research  +
				require_once ( "engine/php/processor/research/researchDelete.php" );
				break;

			case "researchCard": // research  +
				require_once ( "engine/php/processor/research/researchCard.php" );
				break;

			case "adminNewRecord": // admin  +
				require_once ( "engine/php/processor/admin/adminNewRecord.php" );
				break;

			case "adminAddRecord": // admin  +
				require_once ( "engine/php/processor/admin/adminAddRecord.php" );
				break;

			case "adminDeleteRecord": // admin  +
				require_once ( "engine/php/processor/admin/adminDeleteRecord.php" );
				break;

			case "adminCatAssign": // admin  +
				require_once("engine/php/processor/admin/cats/adminCatAssign.php");
				break;

			case "adminCatApprove": // admin  +
				require_once("engine/php/processor/admin/cats/adminCatApprove.php");
				break;

			case "adminCatAutorenewal": // admin  +
				require_once("engine/php/processor/admin/cats/adminCatAutorenewal.php");
				break;

			case "adminNewTask": // admin  +
				require_once ( "engine/php/processor/admin/adminNewTask.php" );
				break;

			case "adminSetPrior": // admin  +
				require_once ( "engine/php/processor/admin/adminSetPrior.php" );
				break;

			case "adminRemoveTasks": // admin  +
				require_once ( "engine/php/processor/admin/adminRemoveTasks.php" );
				break;

			case "admin_mergeEquals": // admin  +
				require_once ( "engine/php/processor/admin/merge/admin_mergeEquals.php" );
				break;
				
			case "admin_mergeProcessNew": // admin  +
				require_once ( "engine/php/processor/admin/merge/admin_mergeProcessNew.php" );
				break;

			case "admin_searchTables": // admin  +
				require_once ( "engine/php/processor/admin/admin_searchTables.php" );
				break;

			case "admin_mainTablesRestruct": // admin  +
				require_once ( "engine/php/processor/admin/mainTablesRestruct.php" );
				break;

			case "admin_tables_new": // admin || tables +
				require_once ( "engine/php/processor/admin/tables/admin_tables_new.php" );
				break;

			case "admin_tables_edit": // admin || tables  +
				require_once ( "engine/php/processor/admin/tables/admin_tables_edit.php" );
				break;

			case "admin_tables_fields": // admin || tables  +
				require_once ( "engine/php/processor/admin/tables/admin_tables_fields.php" );
				break;

			case "admin_tablesCreateProfile": // admin || tables  +
				require_once ( "engine/php/processor/admin/tables/admin_tablesCreateProfile.php" );
				break;
			
			case "admin_tablesNewProfile": // admin || tables  +
				require_once ( "engine/php/processor/admin/tables/admin_tablesNewProfile.php" );
				break;
			
			case "admin_tablesRemoveProfile": // admin || tables +
				require_once ( "engine/php/processor/admin/tables/admin_tablesRemoveProfile.php" );
				break;
			
			case "admin_tablesImport": // admin || tables +
				require_once ( "engine/php/processor/admin/tables/admin_tablesImport.php" );
				break;

			case "admin_news_edit": // admin  +
				require_once ( "engine/php/processor/admin/news/admin_news_edit.php" );
				break;

			case "admin_news_new": // admin  +
				require_once ( "engine/php/processor/admin/news/admin_news_new.php" );
				break;

			case "admin_menu_new": // admin  +
				require_once ( "engine/php/processor/admin/menu/admin_menu_new.php" );
				break;

			case "admin_menu_delete": // admin  +
				require_once ( "engine/php/processor/admin/menu/admin_menu_delete.php" );
				break;

			case "admin_page_new": // admin  +
				require_once ( "engine/php/processor/admin/page/admin_page_new.php" );
				break;

			case "admin_page_delete": // admin  +
				require_once ( "engine/php/processor/admin/page/admin_page_delete.php" );
				break;

			case "admin_doubleComplete": // admin  +
				require_once ( "engine/php/processor/admin/admin_doubleComplete.php" );
				break;

			case "admin_spoiler": // admin  +
				require_once ( "engine/php/processor/admin/spoiler.php" );
				break;

			case "admin_params_addparam": // admin  +
				require_once ( "engine/php/processor/admin/params/add.php" );
				break;
			
			case "tableViewer": // tableViewer +
				require_once ( "engine/php/processor/tableViewer/main.php" );
				break;

			case "newUzi": // useUZI  +
				require_once ( "engine/php/processor/useUZI/newUzi.php" );
				break;

			case "deleteUzi": // useUZI  +
				require_once ( "engine/php/processor/useUZI/deleteUzi.php" );
				break;

			case "journalCitologyDelete": // citology  +
				require_once ( "engine/php/processor/citology/journalCitologyDelete.php" );
				break;

			case "citologyMarkerEdit": // citology  +
				require_once ( "engine/php/processor/citology/citologyMarkerEdit.php" );
				break;

			case "citology_checkCancer": // citology  +
				require_once ( "engine/php/processor/citology/checkCancer.php" );
				break;

			case "personalDataCheck": // GENERAL  +
				require_once ( "engine/php/processor/system/personalDataCheck.php" );
				break;

			case "visitsPatient": // allpatients  +
				require_once ( "engine/php/processor/allpatients/visitsPatient.php" );
				break;

			case "visitsPatientRemove": // allpatients  +
				require_once ( "engine/php/processor/allpatients/visitsPatientRemove.php" );
				break;

			case "visitsPatientCase": // allpatients  +
				require_once ( "engine/php/processor/allpatients/visitsPatientCase.php" );
				break;

			case "cancerMorph": // cancer  +
				require_once ( "engine/php/processor/cancer/cancerMorph.php" );
				break;

			case "cancerRemove": // cancer  +
				require_once ( "engine/php/processor/cancer/cancerRemove.php" );
				break;

			case "checkRegCards": // checkRegCards  +
				require_once ( "engine/php/processor/system/checkRegCards.php" );
				break;

			case "setNurseDefault": // profile  +
				require_once ( "engine/php/processor/profile/setNurseDefault.php" );
				break;

			case "setPeriod": // profile  +
				require_once ( "engine/php/processor/profile/setPeriod.php" );
				break;

			case "setNurseToday": // profile  +
				require_once ( "engine/php/processor/profile/setNurseToday.php" );
				break;

			case "dayStac_saveFields": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_saveFields.php" );
				break;

			case "dayStac_importPatient": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_importPatient.php" );
				break;

			case "dayStac_savePatient": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_savePatient.php" );
				break;

			case "dayStac_deletePatient": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_deletePatient.php" );
				break;

			case "dayStac_addDirectlist": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_addDirectlist.php" );
				break;

			case "dayStac_saveDirlist": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_saveDirlist.php" );
				break;

			case "dayStac_deleteDirlist": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_deleteDirlist.php" );
				break;

			case "dayStac_addVisitRegimen": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_addVisitRegimen.php" );
				break;

			case "dayStac_createRegimen": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_createRegimen.php" );
				break;

			case "dayStac_saveVisreg": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_saveVisreg.php" );
				break;

			case "dayStac_deleteVisreg": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_deleteVisreg.php" );
				break;

			case "dayStac_calendar": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_calendar.php" );
				break;

			case "dayStac_deleteRegimen": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_deleteRegimen.php" );
				break;

			case "dayStac_saveRegimen": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_saveRegimen.php" );
				break;

			case "dayStac_openDayCalendar": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_openDayCalendar.php" );
				break;

			case "dayStac_openDayPatient": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_openDayPatient.php" );
				break;

			case "dayStac_createVisitRegimen": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_createVisitRegimen.php" );
				break;

			case "dayStac_openVisitPatient": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_openVisitPatient.php" );
				break;

			case "dayStac_saveNotes": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_saveNotes.php" );
				break;

			case "dayStac_deleteVisit": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_deleteVisit.php" );
				break;

			case "dayStac_saveResearch": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_saveResearch.php" );
				break;

			case "dayStac_saveVisitAmount": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_saveVisitAmount.php" );
				break;

			case "dayStac_deleteResearch": // dayStac  +
				require_once ( "engine/php/processor/dayStac/dayStac_deleteResearch.php" );
				break;

			case "cancerList_openRouteSheet": // cancerList  +
				require_once ( "engine/php/processor/cancer/cancerList_openRouteSheet.php" );
				break;

			case "routeSheet_oncoInfo": // routeSheet  +
				require_once ( "engine/php/processor/cancer/routeSheet_oncoInfo.php" );
				break;

			case "search4add_create": // search4add  +
				require_once ( "engine/php/processor/search4add/search4add_create.php" );
				break;

			case "search4add_add": // search4add  +
				require_once ( "engine/php/processor/search4add/search4add_add.php" );
				break;

			case "search4add_find": // search4add  +
				require_once ( "engine/php/processor/search4add/search4add_find.php" );
				break;

			case "search4add_addVisit": // search4add  +
				require_once ( "engine/php/processor/search4add/search4add_addVisit.php" );
				break;

			case "sessionPrint": // sessionPrint  +
				require_once ( "engine/php/processor/system/sessionPrint.php" );
			break;

			case "sessions": // work with sessions  +
				require_once ( "engine/php/processor/system/sessions.php" );
			break;

			case "getPreparedFiles": // chat  +
				require_once ( "engine/php/processor/chat/getPreparedFiles.php" );
			break;

			case "getMessages": // chat  +
				require_once ( "engine/php/processor/chat/getMessages.php" );
			break;

			case "sendMessage": // chat  +
				require_once ( "engine/php/processor/chat/sendMessage.php" );
			break;

			case "disp_checkDispanser": // DISPANCER  +
				require_once ( "engine/php/processor/system/disp_checkDispanser.php" );
			break;

			case "scheduleUzi_toList": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/toList.php" );
			break;

			case "scheduleUzi_addDay": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/addDay.php" );
			break;

			case "scheduleUzi_calendar": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/calendar.php" );
			break;

			case "scheduleUzi_addShift": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/addShift.php" );
			break;

			case "scheduleUzi_addTime": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/addTime.php" );
			break;

			case "scheduleUzi_removeTime": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/removeTime.php" );
			break;

			case "scheduleUzi_removeShift": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/removeShift.php" );
			break;

			case "scheduleUzi_addTemp": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/addTemp.php" );
			break;

			case "scheduleUzi_removeTemp": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/removeTemp.php" );
			break;

			case "scheduleUzi_addWeek": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/addWeek.php" );
			break;

			case "scheduleUzi_clearGraphic": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/clearGraphic.php" );
			break;

			case "scheduleUzi_duplicateShift": // schedule UZI modal  +
				require_once ( "engine/php/processor/scheduleUzi/modals/shift.php" );
			break;

			case "scheduleUzi_editDay": // schedule UZI modal  +
				require_once ( "engine/php/processor/scheduleUzi/modals/editday.php" );
			break;

			case "scheduleUzi_removeShiftDay": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/removeShiftDay.php" );
			break;

			case "scheduleUzi_copyShift": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/copyShift.php" );
			break;

			case "scheduleUzi_search": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/search.php" );
			break;

			case "scheduleUzi_list": // schedule UZI  +
				require_once ( "engine/php/processor/scheduleUzi/list.php" );
			break;

			case "cartridge_add": // cartridge +
				require_once ( "engine/php/processor/cartridge/add.php" );
			break;

			case "cartridge_addAction": // cartridge +
				require_once ( "engine/php/processor/cartridge/addAction.php" );
			break;

			case "cartridge_removeAction": // cartridge +
				require_once ( "engine/php/processor/cartridge/removeAction.php" );
			break;

			case "newAction_modal": // cartridge +
				require_once ( "engine/php/processor/cartridge/modals/newAction_form.php" );
			break;

			case "morphology_addMarker": // documents || morphology +
				require_once ( "engine/php/processor/documents/morphology/add_marker.php" );
			break;

			case "newHoliday": // nurseJournals
				require_once ( "engine/php/processor/nurseJournals/newHoliday.php" );
			break;

			case "zno_du_add": // ЗНО ДУ - Добавить
				require_once ( "engine/php/processor/zno_du/add.php" );
			break;

			case "zno_du_edit": // ЗНО ДУ - Редактировать
				require_once ( "engine/php/processor/zno_du/edit.php" );
			break;

			case "zno_du_remove": // ЗНО ДУ - Редактировать
				require_once ( "engine/php/processor/zno_du/remove.php" );
			break;

			case "zno_du_import_rs": // ЗНО ДУ - Редактировать
				require_once ( "engine/php/processor/zno_du/import.php" );
			break;

			case "zno_du_search": // ЗНО ДУ - Поиск пациентов
				require_once ( "engine/php/processor/zno_du/search.php" );
			break;

			case "zno_du_rs": // ЗНО ДУ - Маршрутные листы
				require_once ( "engine/php/processor/zno_du/rs.php" );
			break;

			case "zno_du_reset": // ЗНО ДУ - Сброс данных
				require_once ( "engine/php/processor/zno_du/reset.php" );
			break;

			case "homeVisitFirstAction": // home visit first action
				require_once ( "engine/php/processor/home_visit/first_action.php" );
			break;

			case "homeVisitProcess": // home visit first action
				require_once ( "engine/php/processor/home_visit/processor.php" );
			break;

			default:
				$response['msg'] = 'Неизвестный Action-параметр';
			break;
		}

	} else
	{
		$response['msg'] = 'Action-параметр не установлен';
	}

} else
{
	$response['msg'] = 'POST-параметры не установлены';
}

echo json_encode($response);