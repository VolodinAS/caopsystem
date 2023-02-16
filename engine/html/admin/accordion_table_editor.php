<?php
foreach ($SmallTables as $Table):
	?>
	<?php
	$TableStructure = tablestructure2(array($Table), 1);
	$TableStructure = $TableStructure[$Table];
	
	$FirstField = key($TableStructure);
	$TableData = getarr($Table, 1, "ORDER BY {$FirstField} ASC");
	
	?>
    <div id="accordion_<?=$Table;?>_editor">
        <div class="card">
            <div class="card-header p-0" id="<?=$Table;?>_editor">
                <h5 class="mb-0">
                    <div class="row">
                        <div class="col-1">
                            <div class="input-group input-group-sm invisible" id="fieldloader_<?=$Table;?>">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-primary btn-sm table_new_item" data-table="<?=$Table;?>">Добавить запись</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#<?=$Table;?>_spoiler_editor" aria-expanded="true" aria-controls="<?=$Table;?>_spoiler_editor"><?=$Table;?> (<?=count($TableData);?>)</button>
                        </div>


                    </div>
                </h5>
            </div>
            <div id="<?=$Table;?>_spoiler_editor" class="collapse" aria-labelledby="<?=$Table;?>" data-parent="#accordion_<?=$Table;?>_editor">
                <div class="card-body p-2">
					
					<? foreach ($TableData as $Data): ?>
						<? foreach( $TableStructure as $Structure=>$Desc ): ?>
							<?php
//		                                        debug($Desc);
							$disabled = '';
							if ( $Structure == $FirstField ) $disabled = ' disabled';
							?>

                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><?=$Structure;?></span>
                                </div>
								<? if ($Desc != 'text'): ?>
                                    <input<?=$disabled;?>
                                            type="text"
                                            class="mysqleditor form-control form-control-sm"
                                            data-action="edit"
                                            data-table="<?=$Table;?>"
                                            data-assoc="0"
                                            data-fieldid="<?=$FirstField;?>"
                                            data-id="<?=$Data[$FirstField];?>"
                                            data-field="<?=$Structure;?>"
                                            data-loader="fieldloader_<?=$Table;?>"
                                            placeholder="Значение"
                                            value="<?=htmlspecialchars($Data[$Structure]);?>">
								<? endif; ?>
								<? if ($Desc == 'text'): ?>
                                    <textarea<?=$disabled;?>
                                                                class="mysqleditor form-control form-control-sm"
                                                                data-action="edit"
                                                                data-table="<?=$Table;?>"
                                                                data-assoc="0"
                                                                data-fieldid="<?=$FirstField;?>"
                                                                data-id="<?=$Data[$FirstField];?>"
                                                                data-field="<?=$Structure;?>"
                                                                data-loader="fieldloader_<?=$Table;?>"
                                                                placeholder="Значение"
                                                                rows="20"><?=htmlspecialchars($Data[$Structure]);?></textarea>
								<? endif; ?>
                                <br/>
                            </div>
						
						<? endforeach; ?>
                        <button type="button" class="btn btn-danger btn-sm table_delete_item" data-table="<?=$Table;?>" data-id="<?=$Data[$FirstField];?>" data-fieldid="<?=$FirstField;?>">Удалить</button>
						<? bt_divider(); ?>
					<? endforeach; ?>
					
					<?php
					//                                        debug($TableData);
					?>
                </div>
            </div>
        </div>
    </div>
<?php
endforeach;
?>