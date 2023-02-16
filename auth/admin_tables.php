<?php
$Tables = getarr(CAOP_TABLES, 1, "ORDER BY table_title ASC");
?>
<ul class="nav nav-tabs" id="tables-control" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="table-create-id" data-toggle="tab" href="#table-create-href" role="tab"
           aria-controls="table-create-ac" aria-selected="true">Создать таблицу</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="tables-list-id" data-toggle="tab" href="#tables-list-href" role="tab"
           aria-controls="tables-list-ac">Список таблиц (<?= count($Tables); ?>)</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="edit-fields-id" data-toggle="tab" href="#edit-fields-href" role="tab"
           aria-controls="edit-fields-ac">Редактировать поля</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="import-table-id" data-toggle="tab" href="#import-table-href" role="tab"
           aria-controls="import-table-ac">Загрузить файл</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="files-table-id" data-toggle="tab" href="#files-table-href" role="tab"
           aria-controls="files-table-ac">Файлы</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="insert-table-id" data-toggle="tab" href="#insert-table-href" role="tab"
           aria-controls="insert-table-ac">Импорт</a>
    </li>
</ul>
<div class="tab-content" id="tables-control-content">
    <div class="tab-pane fade show active" id="tables-list-href" role="tabpanel" aria-labelledby="tables-list-id">
		
		<?php
		
		if (count($Tables) > 0)
		{
			$npp = 1;
			?>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col" class="text-center">№</th>
                    <th scope="col" class="text-center">ID</th>
                    <th scope="col" class="text-center">Название</th>
                    <th scope="col" class="text-center">Подназвание</th>
                    <th scope="col" class="text-center">Описание</th>
                    <th scope="col" class="text-center">Файлы</th>
                    <th scope="col" class="text-center">Создана</th>
                    <th scope="col" class="text-center">Обновлена</th>
                    <th scope="col" class="text-center">Действия</th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ($Tables as $table)
				{
					$Files = getarr(CAOP_TABLE_FILES, "file_table_id='{$table['table_id']}'", "ORDER BY file_upload_unix DESC");
					?>
                    <tr>
                        <th scope="row"><?= $npp; ?></th>
                        <td width="1%" class="text-center"><?= $table['table_id']; ?></td>
                        <td><?= $table['table_title']; ?></td>
                        <td><?= $table['table_subtitle']; ?></td>
                        <td><?= $table['table_description']; ?></td>
                        <td align="center" width="1%"><?= count($Files); ?></td>
                        <td width="1%" class="text-center"><?= date("d.m.Y H:i:s", $table['table_unix_created']); ?></td>
                        <td width="1%" class="text-center"><?= date("d.m.Y H:i:s", $table['table_unix_updated']); ?></td>
                        <td class="text-center">
                            <div class="dropdown dropleft" data-title="Действия">
                                <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                        id="button-action-<?= $table['table_id']; ?>" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-tools" viewBox="0 0 16 16">
                                        <path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.356 3.356a1 1 0 0 0 1.414 0l1.586-1.586a1 1 0 0 0 0-1.414l-3.356-3.356a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0zm9.646 10.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708zM3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026L3 11z"/>
                                    </svg>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="button-action-<?= $table['table_id']; ?>">
                                    <a class="dropdown-item" target="_blank" href="/table_viewer/<?=$table['table_id'];?>">Просмотр таблицы</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" target="_blank" href="/table_export/<?=$table['table_id'];?>">Экспорт таблицы</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:editTable(<?= $table['table_id']; ?>)">Редактировать таблицу</a>
                                    <a class="dropdown-item" href="javascript:editFields(<?= $table['table_id']; ?>)">Редактировать поля</a>
                                    <a class="dropdown-item" href="javascript:importTable(<?= $table['table_id']; ?>)">Загрузить Excel-файл</a>
                                    <a class="dropdown-item" href="javascript:filesTable(<?= $table['table_id']; ?>)">Файлы (<?=count($Files);?>)</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:deleteTable(<?= $table['table_id']; ?>)">Удалить таблицу</a>
                                </div>
                            </div>
                        </td>
                    </tr>
					<?php
					
					$npp++;
				}
				?>
                </tbody>
            </table>
			<?php
		} else
		{
			bt_notice('Таблиц нет', BT_THEME_WARNING);
		}
		?>
    </div>
    <div class="tab-pane fade" id="table-create-href" role="tabpanel" aria-labelledby="table-create-id">
        <br>
        <form id="form-new-table">
            <input type="hidden" name="table_id" id="table_id" value="-1">
            <div class="row">
                <div class="col">
                    <label class="sr-only" for="table-title">Название таблицы</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Название таблицы</div>
                        </div>
                        <input type="text" class="form-control" id="table-title" name="table_title"
                               placeholder="Название таблицы">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only" for="table-subtitle">Подназвание таблицы</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Подназвание таблицы</div>
                        </div>
                        <input type="text" class="form-control" id="table-subtitle" name="table_subtitle"
                               placeholder="Подназвание таблицы">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only" for="table-description">Описание таблицы</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Описание таблицы</div>
                        </div>
                        <!--						<input type="text" class="form-control" id="table-description" name="table-description" placeholder="Описание таблицы">-->
                        <textarea class="form-control" id="table-description" name="table_description"
                                  placeholder="Описание таблицы" rows="10"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-lg btn-success col">Сохранить</button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-lg btn-warning col" onclick="window.location.reload()">Отмена</button>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane fade" id="edit-fields-href" role="tabpanel" aria-labelledby="edit-fields-id">
		<? bt_notice('Сначала надо выбрать таблицу из списка', BT_THEME_WARNING); ?>
    </div>
    <div class="tab-pane fade" id="import-table-href" role="tabpanel" aria-labelledby="import-table-id">
	    <? bt_notice('Сначала надо выбрать таблицу из списка', BT_THEME_WARNING); ?>
    </div>
    <div class="tab-pane fade" id="files-table-href" role="tabpanel" aria-labelledby="files-table-id">
	    <? bt_notice('Сначала надо выбрать таблицу из списка', BT_THEME_WARNING); ?>
    </div>
    <div class="tab-pane fade" id="insert-table-href" role="tabpanel" aria-labelledby="insert-table-id">
	    <? bt_notice('Сначала надо выбрать таблицу из списка', BT_THEME_WARNING); ?>
    </div>

</div>

<script defer type="text/javascript" src="/engine/js/admin/admin_tables.js?<?= rand(0, 99999);; ?>"></script>

<?php
require_once ("engine/html/modals/admin/tables/importProfile.php");
?>