<?php
$response['result'] = true;

$response['htmlData'] .= '
<input type="file" multiple="multiple" accept=".xls,.xlsx">
<input type="hidden" id="table_import_id" value="' . $TableData['table_id'] . '">
<a href="#" class="upload_files button">Загрузить файлы</a>
<div class="ajax-reply"></div>
';