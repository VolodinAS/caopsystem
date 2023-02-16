<?php
$response['stage'] = $action;

$TABLE = $_POST['table'];
//$TABLE = '123';

$ResultCheckTable = mqc("SHOW TABLES LIKE '{$TABLE}'");
$ResultData = mfa($ResultCheckTable);
$ResultValue = array_values($ResultData);
if ( isset($ResultValue[0]) && $ResultValue[0] == $TABLE )
{
	$TABLE = $ResultValue[0];
	$TableResult = mqc( "SHOW TABLE STATUS LIKE '{$TABLE}'" );
	$TableData = mfa($TableResult);
	$NewIndex = intval( intval($TableData['Auto_increment']) + 1 );

	$TableStructure = tablestructure2(array($TABLE), 1);
	$TableStructure = $TableStructure[$TABLE];

	$FirstField = key($TableStructure);
	$arr['htmlData'] = '';
	$arr['htmlData'] .= '<form id="formNewItem">';
	$arr['htmlData'] .= '<input type="hidden" name="table" value="'.$TABLE.'">';
	foreach( $TableStructure as $Structure=>$Desc )
	{
		$disabled = '';
		$value = '';
		if ( $Structure == $FirstField )
		{
			$disabled = ' disabled';
			$value = $NewIndex;
		}

		$html_input = '';
		if ($Desc != 'text')
		{
			$html_input .= '<input' . $disabled . ' type="text" class="form-control form-control-sm" name="'.$Structure.'" placeholder="Значение" value="'.htmlspecialchars($value).'">';
		}
		if ($Desc == 'text')
		{
			$html_input .= '<textarea' . $disabled . ' class="form-control form-control-sm" name="'.$Structure.'" placeholder="Значение">'.htmlspecialchars($value).'</textarea>';
		}



		$arr['htmlData'] .= '<div class="input-group input-group-sm">';
		$arr['htmlData'] .= '<div class="input-group-prepend">';
		$arr['htmlData'] .= '<span class="input-group-text" id="basic-addon1">'.$Structure.'</span>';
		$arr['htmlData'] .= '</div>';
		$arr['htmlData'] .= $html_input;
		$arr['htmlData'] .= '<br/>';
		$arr['htmlData'] .= '</div>';
	}
	$arr['htmlData'] .= '</form>';

	$response['htmlData'] = $arr['htmlData'];
	$response['result'] = true;

//	$response['debug']['$TABLE'] = $TABLE;
//	$response['debug']['$TableData'] = $TableData;
//	$response['debug']['AUTO_INCREMENT']['BEFORE'] = $TableData['Auto_increment'];
//	$response['debug']['AUTO_INCREMENT']['AFTER'] = $NewIndex;
	$response['debug']['$TableStructure'] = $TableStructure;
	$response['debug']['$FirstField'] = $FirstField;
} else
{
	$response['msg'] = 'Такой таблицы не существует';
}

