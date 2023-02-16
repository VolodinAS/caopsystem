<?php
$CatArray = array(
	'cat_content'   =>  GetRandomCat(512),
	'cat_date'  =>  $CURRENT_DAY['format']['dd.mm.yyyy hh:mm:ss'],
	'cat_date_unix' =>  $CURRENT_DAY['full_unix']
);
debug($CatArray);
$NewCat = appendData(CAOP_CAT_SYSTEM, $CatArray);