<?php
spoiler_begin('Табличные переменные', 'tables_variables', 'collapse', '', 'admin-spoiler');
?>
<button class="btn btn-lg btn-warning btn-restructMySQLiTables">ПЕРЕОПРЕДЕЛИТЬ MYSQLI-TABLES.PHP</button>
<div class="dropdown-divider"></div>
<?php
/*
				foreach ($AllTables as $allTable) {
					echo '
                            <label class="sr-only" for="labby_' . $allTable . '">$' . strtoupper($allTable) . '</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text clickForCopy" id="forCopyVar_' . $allTable . '" data-target="forCopyVar_' . $allTable . '">$' . strtoupper($allTable) . '</div>
                            </div>
                            <input type="text" class="form-control clickForCopy" id="labby_'.$allTable.'" data-target="forCopyTable_'.$allTable.'" placeholder="таблица" value="'.$allTable.'">
                            <div style="display: none" id="forCopyVar_'.$allTable.'">$'.strtoupper($allTable).'</div>
                            <div style="display: none" id="forCopyTable_'.$allTable.'">'.$allTable.'</div>
                          </div>';
				}
				*/

//foreach ($AllTables as $allTable) {
//	echo '$'.strtoupper($allTable).' = \''.$allTable.'\'; define("'.strtoupper($allTable).'", "'.$allTable.'");<br/>';
//}

spoiler_end();

?>