<?php
$response['stage'] = $action;
$response['msg'] = 'begin';

$HTTP = $_POST;
extract($HTTP, EXTR_PREFIX_SAME, '_caop');



if ( strlen($news_description) > 0 )
{
	if ( strlen($news_title) > 0 )
	{
		$NEWS_ID = (int)$news_id;
		
		if ( $NEWS_ID > 0 )
		{
			// обновление данных о новости
			
			$NewsRM = RecordManipulation($NEWS_ID,CAOP_NEWS, 'news_id');
			if ( $NewsRM['result'] )
			{
				$NewsData = $NewsRM['data'];
				
				$param_values = $HTTP;
				$param_values['news_body'] = $param_values['news_description'];
				$param_values['news_isOpened'] = $param_values['news_spoiler'];
				unset($param_values['news_id']);
				unset($param_values['news_description']);
				unset($param_values['news_spoiler']);
				
				$UpdateNews = updateData(CAOP_NEWS, $param_values, $NewsData, "news_id='{$NEWS_ID}'");
				if ( $UpdateNews['stat'] == RES_SUCCESS )
				{
					$response['result'] = true;
					$response['msg'] = 'Новость успешно обновлена!';
				}
				
			} else $response['msg'] = $NewsRM['msg'];
			
		} else
		{
			$param_values = $HTTP;
			$param_values['news_author'] = docNameShort($USER_PROFILE, "famimot");
			$param_values['news_unix'] = time();
			$param_values['news_body'] = $param_values['news_description'];
			$param_values['news_isOpened'] = $param_values['news_spoiler'];
			unset($param_values['news_id']);
			unset($param_values['news_description']);
			unset($param_values['news_spoiler']);
			
			$NewNews = appendData(CAOP_NEWS, $param_values);
			
			if (  $NewNews[ID] > 0 )
			{
				
				$response['result'] = true;
				$response['msg'] = 'Новость успешно создана!';
				
			} else
			{
				$response['msg'] = 'Проблема с добавлением новости';
				$response['debug']['$NewNews'] = $NewNews;
			}
		}
	} else $response['msg'] = 'Не указан заголовок новости';
} else $response['msg'] = 'Не указано описание новости';
