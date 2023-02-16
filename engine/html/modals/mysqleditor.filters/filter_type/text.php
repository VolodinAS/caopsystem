<?php
//$response['htmlData'] .= debug_ret($_SESSION);
$default_value = $_SESSION['mef_filters'][$filter]['data'];
$default_value = implode(';', $default_value);
$response['htmlData'] .= '
<label for="text_filter">
	<b>Введите текст для фильтра</b>
</label>
<input
	type="text"
	name="filter_data"
	id="filter_data"
	class="form-control form-control-lg"
	placeholder="Введите текст для фильтрации"
	value="'.$default_value.'"
	required
>
<ul class="text-muted">
	<li>Для относительного поиска используйте знак "%": "иван% пет% ник%"</li>
	<li>Если необходимо учесть несколько критериев фильтрации, используйте знак ";": "иван% пет% ник%;шувал%" - останутся ивановы и шуваловы</li>
</ul>
';