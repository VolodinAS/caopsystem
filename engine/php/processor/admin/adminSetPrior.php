<?php
$response['stage'] = $action;
$response['debug']['$_POST'] = $_POST;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');

$TaskRM = RecordManipulation($item_id, CAOP_NEEDADD, 'needadd_id');
if ( $TaskRM['result'] )
{
    $TaskData = $TaskRM['data'];
    
//    $response['debug']['$TaskData'] = $TaskData;
    
    $SORT = '';
    $go_next = false;
    switch ($prior_type)
    {
	    case ">":
	    	$SORT = 'ASC';
	    	$go_next = true;
	    	break;
	    case "<":
	    	$SORT = 'DESC';
		    $go_next = true;
	    	break;
	    	
    }
    
    if ( $go_next )
    {
    	$response['debug']['$SORT'] = $SORT;
    	
	    $GetClosestTaskData = getarr(CAOP_NEEDADD, "needadd_priority {$prior_type} {$TaskData['needadd_priority']} AND needadd_done='0' AND needadd_hidden='0'", "ORDER BY needadd_priority {$SORT} LIMIT 1");
	    if ( count($GetClosestTaskData) > 0 )
	    {
		    $GetClosestTaskData = $GetClosestTaskData[0];
		    $response['debug']['$GetClosestTaskData'] = $GetClosestTaskData;
		    
		    $currentParamValues = array(
		    	'needadd_priority'  =>  $GetClosestTaskData['needadd_priority'],
			    'needadd_upd_unix'  =>  time()
		    );
		    $UpdateCurrentData = updateData(CAOP_NEEDADD, $currentParamValues, $TaskData, "needadd_id='{$TaskData['needadd_id']}'");
		    
		    $closestParamValues = array(
		    	'needadd_priority'  =>  $TaskData['needadd_priority'],
			    'needadd_upd_unix'  =>  time()
		    );
		    $UpdateClosestData = updateData(CAOP_NEEDADD, $closestParamValues, $GetClosestTaskData, "needadd_id='{$GetClosestTaskData['needadd_id']}'");
		    
		    if ( $UpdateCurrentData['stat'] == RES_SUCCESS && $UpdateClosestData['stat'] == RES_SUCCESS )
		    {
			    $response['result'] = true;
		    } else $response['msg'] = 'Не удалось изменить приоритет';
		    
		    
		    
		    
	    } else
	    {
		    $response['msg'] = 'У этого таска самый приоритетный приоритет';
	    }
    } else $response['msg'] = 'Неверный тип приоритирования';
    
    
    

} else $response['msg'] = $TaskRM['msg'];