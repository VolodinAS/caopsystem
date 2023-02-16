<?php
if ( $page == "journalPrint" )
{
//	debug($request_params);
	require_once ( "engine/html/print/journal_print.php" );
}
if ( $page == "documentPrint" )
{
//	debug($request_params);
//    debug('IM HERE 1');
	require_once ( "engine/html/print/document_print.php" );
}
if ( $page == "citologyPrintDirection" )
{
	require_once ( "engine/html/print/citology_print.php" );
}
if ( $page == "routeSheetPrint" )
{
	require_once ( "engine/html/print/routeSheet_print.php" );
}
if ( $page == "noticeF1aPrint" )
{
	require_once ( "engine/html/print/notice_f1a_print.php" );
}
if ( $page == "dailysPrint" )
{
	require_once ( "engine/html/print/dailys_print.php" );
}
if ( $page == "msktmdcPrint" )
{
	require_once ( "engine/html/print/msktmdc_print.php" );
}
if ( $page == "anylpuPrint" )
{
	require_once ( "engine/html/print/anylpu_print.php" );
}
if ( $page == "blankDeclinedPrint" )
{
	require_once ( "engine/html/print/blank_decline_print.php" );
}
if ( $page == "morphologyPrint" )
{
	require_once ( "engine/html/print/morphology_print.php" );
}