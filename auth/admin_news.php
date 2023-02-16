<?php
$News = getarr(CAOP_NEWS, 1, "ORDER BY news_unix DESC");
?>

<ul class="nav nav-tabs"
    id="news-control"
    role="tablist">
    <li class="nav-item"
        role="presentation">
        <a class="nav-link"
           id="news-create-id"
           data-toggle="tab"
           href="#news-create-href"
           role="tab"
           aria-controls="news-create-ac"
           aria-selected="true">Создать новость</a>
    </li>
    <li class="nav-item"
        role="presentation">
        <a class="nav-link active"
           id="news-list-id"
           data-toggle="tab"
           href="#news-list-href"
           role="tab"
           aria-controls="news-list-ac">Список новостей (<?= count($News); ?>)</a>
    </li>
</ul>

<div class="tab-content"
     id="news-control-content">
    <div class="tab-pane fade show active"
         id="news-list-href"
         role="tabpanel"
         aria-labelledby="news-list-id">
		
		<?php
		
		if (count($News) > 0)
		{
			$npp = 1;
			?>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col"
                        class="text-center">№
                    </th>
                    <th scope="col"
                        class="text-center">ID
                    </th>
                    <th scope="col"
                        class="text-center">Название
                    </th>
                    <th scope="col"
                        class="text-center">Подназвание
                    </th>
                    <th scope="col"
                        class="text-center">Создана
                    </th>
                    <th scope="col"
                        class="text-center">Breaking
                    </th>
                    <th scope="col"
                        class="text-center">Published
                    </th>
                    <th scope="col"
                        class="text-center">Версия
                    </th>
                    <th scope="col"
                        class="text-center">Действия
                    </th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ($News as $newsItem)
				{
					
					?>
                    <tr>
                        <th scope="row"><?= $npp; ?></th>
                        <td width="1%"
                            class="text-center"><?= $newsItem['news_id']; ?></td>
                        <td><?= $newsItem['news_title']; ?></td>
                        <td><?= $newsItem['news_subtitle']; ?></td>
                        <td width="1%"
                            class="text-center">
                            <?= date("d.m.Y H:i:s", $newsItem['news_unix']); ?>
                        </td>
                        <td width="1%"
                            class="text-center">
                            <?= $newsItem['news_breaking'] ?>
                        </td>
                        <td width="1%"
                            class="text-center">
                            <?= $newsItem['news_publish'] ?>
                        </td>
                        <td width="1%"
                            class="text-center">
                            <?= $newsItem['news_version'] ?>
                        </td>
                        <td class="text-center">
                            <div class="dropdown dropleft"
                                 data-title="Действия">
                                <button class="btn btn-secondary dropdown-toggle btn-sm"
                                        type="button"
                                        id="button-action-<?= $table['table_id']; ?>"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false">
									<?= BT_ICON_ACTIONS; ?>
                                </button>
                                <div class="dropdown-menu"
                                     aria-labelledby="button-action-<?= $newsItem['news_id']; ?>">
                                    <a class="dropdown-item"
                                       href="javascript:editNews(<?= $newsItem['news_id']; ?>)">Редактировать
                                                                                                 новость</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                       href="javascript:deleteNews(<?= $newsItem['news_id']; ?>)">Удалить новость</a>
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
			bt_notice('Новостей нет нет', BT_THEME_WARNING);
		}
		?>
    </div>
    <div class="tab-pane fade"
         id="news-create-href"
         role="tabpanel"
         aria-labelledby="news-create-id">
        <br>
        <form id="form-new-news">
            <input type="hidden"
                   name="news_id"
                   id="news_id"
                   value="-1">
            <div class="row">
                <div class="col">
                    <label class="sr-only"
                           for="news-title">Заголовок</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Заголовок</div>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="news-title"
                               name="news_title"
                               placeholder="Заголовок">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only"
                           for="news-subtitle">Подзаголовок</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Подзаголовок</div>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="news-subtitle"
                               name="news_subtitle"
                               placeholder="Подзаголовок">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only"
                           for="news-description">Текст новости</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Текст новости</div>
                        </div>
                        <textarea class="form-control"
                                  id="news-description"
                                  name="news_description"
                                  placeholder="Текст новости"
                                  rows="25"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only"
                           for="news-breaking">Breaking News</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Breaking News</div>
                        </div>
<!--                        <input type="text"-->
<!--                               class="form-control"-->
<!--                               id="news-breaking"-->
<!--                               name="news_breaking"-->
<!--                               placeholder="Подсветить новость, 1 или 0">-->
	                    <?php
	                    $os_options = array(
		                    'name' => 'news_breaking',
		                    'id' => 'news-breaking',
		                    'placeholder' => 'Подсветить новость?',
		                    'values' => '0;1'
	                    );
	                    $os_switcher = checkbox_switcher($os_options);
	                    echo $os_switcher;
	                    ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only"
                           for="news-breaking">Открытый спойлер</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Открытый спойлер</div>
                        </div>
	                    <?php
	                    $os_options = array(
	                        'name' => 'news_spoiler',
                            'id' => 'news-spoiler',
                            'placeholder' => 'Открыт ли спойлер?',
                            'values' => '0;1'
	                    );
	                    $os_switcher = checkbox_switcher($os_options);
	                    echo $os_switcher;
	                    ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only"
                           for="news-publish">Опубликовать?</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Опубликовать?</div>
                        </div>
<!--                        <input type="text"-->
<!--                               class="form-control"-->
<!--                               id="news-publish"-->
<!--                               name="news_publish"-->
<!--                               placeholder="Опубликовать новость?">-->
	                    <?php
	                    $os_options = array(
		                    'name' => 'news_publish',
		                    'id' => 'news-publish',
		                    'placeholder' => 'Опубликовать новость?',
		                    'values' => '0;1'
	                    );
	                    $os_switcher = checkbox_switcher($os_options);
	                    echo $os_switcher;
	                    ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label class="sr-only"
                           for="news-publish">Версия</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Версия</div>
                        </div>
                        <input type="text"
                               class="form-control"
                               id="news-version"
                               name="news_version"
                               placeholder="Версия программы на момент публикации новости">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button type="submit"
                            class="btn btn-lg btn-success col">Сохранить
                    </button>
                </div>
                <div class="col">
                    <button type="button"
                            class="btn btn-lg btn-warning col"
                            onclick="window.location.reload()">Отмена
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script defer
        type="text/javascript"
        src="/engine/js/admin/admin_news.js?<?= rand(0, 99999);; ?>"></script>