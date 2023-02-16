<?php
include ( "engine/php/configs/mkb.classificator.php" );

$MKB_STRING = str_replace("\t", "[TAB1]", $MKB_STRING);
$MKB_array = explode("\n", $MKB_STRING);

//debug($MKB_array);


$MKB = array();
$Parents = array();
foreach ($MKB_array as $MKB_String) {
	$MKB_Data = explode("[TAB1]", $MKB_String);
	$MKB[] = $MKB_Data;

	if ( ifound($MKB_Data[0], '-') )
	{
		$Parents[] = $MKB_Data;
	}
}

foreach ($Parents as &$parent) {
	$Childs = array();
	foreach ($MKB as $Element) {
		$d = array(
			'p0' =>  $parent[0],
			'e2' =>  $Element[2],
			'$parent'   =>  $parent,
			'$Element'  =>  $Element
		);
//		debug($d);
		if ( strtoupper(trim($Element[2])) == strtoupper(trim($parent[0])) )
		{
			$Childs[] = $Element;
		}
		$parent['childs'] = $Childs;
//		debug($d);
	}
}



foreach ($Parents as &$parent) {
//	$Childs = $parent['childs'];

	foreach ($parent['childs'] as &$child) {
		$Nodes = array();
		foreach ($MKB as $Element) {
			/*$n = array(
				'c0' =>  $child[0],
				'e2' =>  $Element[2],
				'$Element' => $Element
			);
			debug($n);*/
			if ( strtoupper(trim($Element[2])) == strtoupper(trim($child[0])) )
			{
				$Nodes[] = $Element;
			}
			$child['nodes'] = $Nodes;
		}

	}
}
unset($parent);
unset($child);
//debug($Parents);

//foreach ($Parents as $items)
//{
//	$CODE = $items[0];
//	$TITLE = $items[1];
//	$PARENT = $items[2];
//
//	$parentValues = array(
//		'mkb_code'  =>  trim($CODE),
//		'mkb_title' =>  trim($TITLE),
//		'mkb_parent'    =>  trim($PARENT)
//	);
//	$NewParent = appendData(CAOP_MKB, $parentValues);
//	if ( $NewParent[ID] > 0 )
//	{
//		debug('['.$CODE.']' . $TITLE . ' IS ADD');
//	} else
//	{
//		debug('['.$CODE.']' . $TITLE . ' ADD ERROR');
//	}
//
//}

//foreach ($Parents as $item)
//{
//	$CHILDS = $item['childs'];
//	foreach ($CHILDS as $childItem)
//	{
//		$CODE = trim($childItem[0]);
//		$TITLE = trim($childItem[1]);
//		$PARENT = trim($childItem[2]);
//
//		$GetParentOfChild = getarr(CAOP_MKB, "mkb_code='{$PARENT}'");
//		if ( count($GetParentOfChild) == 1 )
//		{
//			$GetParentOfChild = $GetParentOfChild[0];
//			$childValues = array(
//				'mkb_code'  =>  trim($CODE),
//				'mkb_subid'  =>  trim($GetParentOfChild['mkb_id']),
//				'mkb_title' =>  trim($TITLE),
//				'mkb_parent'    =>  trim($PARENT)
//			);
//			$NewChild = appendData(CAOP_MKB, $childValues);
//			if ( $NewChild[ID] > 0 )
//			{
//				debug('['.$CODE.'] {CHILD} ' . $TITLE . ' IS ADD');
//			} else
//			{
//				debug('['.$CODE.'] {CHILD} ' . $TITLE . ' ADD ERROR');
//			}
//		} else
//		{
//			debug('MEGA ERROR');
//			debug($GetParentOfChild);
//		}
//
//	}
//}


//foreach ($Parents as $item)
//{
//	$CHILDS = $item['childs'];
//	foreach ($CHILDS as $childItem)
//	{
//		$NODES = $childItem['nodes'];
//
//		if ( count($NODES) > 0 )
//		{
//			foreach ($NODES as $nodeItem)
//			{
////				debug($nodeItem);
//				$CODE = trim($nodeItem[0]);
//				$TITLE = trim($nodeItem[1]);
//				$PARENT = trim($nodeItem[2]);
//
//				$GetParentOfNode = getarr(CAOP_MKB, "mkb_code='{$PARENT}'");
//				if ( count($GetParentOfNode) == 1 )
//				{
//					$GetParentOfNode = $GetParentOfNode[0];
//					$nodeValues = array(
//						'mkb_code'  =>  trim($CODE),
//						'mkb_subid'  =>  trim($GetParentOfNode['mkb_id']),
//						'mkb_title' =>  trim($TITLE),
//						'mkb_parent'    =>  trim($PARENT)
//					);
//					$NewNode = appendData(CAOP_MKB, $nodeValues);
//					if ( $NewNode[ID] > 0 )
//					{
//						debug('['.$CODE.'] {CHILD} {NODE} ' . $TITLE . ' IS ADD');
//					} else
//					{
//						debug('['.$CODE.'] {CHILD} {NODE} ' . $TITLE . ' ADD ERROR');
//					}
//				} else
//				{
//					debug('MEGA ERROR');
//					debug($GetParentOfNode);
//				}
//			}
//		}
//	}
//}

//
//$MKB_GENERAL = array();
//
//foreach ($Parents as $parent) {
//	if ( count($parent['childs']) > 0 )
//	{
//		foreach ($parent['childs'] as $child) {
//			if ( count($child['nodes']) > 0 )
//			{
//				foreach ($child['nodes'] as $node) {
////					debug($node);
//					$MKB_GENERAL[] = $node;
//				}
//			} else
//			{
////				debug($child);
//				$MKB_GENERAL[] = $child;
//			}
//		}
//	} else
//	{
////		debug($parent);
//		$MKB_GENERAL[] = $parent;
//	}
//}
//
////debug($MKB_GENERAL);
//
//foreach ($MKB_GENERAL as $MKB) {
//	$paramValues = array(
//		'mkb_code'  =>  $MKB[0],
//		'mkb_title' =>  $MKB[1],
//		'mkb_parent'    =>  $MKB[2]
//	);
//	$ifNotExists = array(
//		'index' =>  'mkb_id',
//		'query' =>  "mkb_code='{$MKB[0]}'"
//	);
//	$MKBAdd = appendData(CAOP_MKB, $paramValues, $ifNotExists);
//	debug($MKBAdd);
//}