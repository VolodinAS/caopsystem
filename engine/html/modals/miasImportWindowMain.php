<div class="modal fade" id="miasImportWindowMain" tabindex="-1" role="dialog" aria-labelledby="miasImportWindowMain"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miasImportWindowMain">ИМПОРТ СПИСКА ПАЦИЕНТОВ</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="miasImportWindowMainBody">

                <div class="text-muted">
                    <b>Поддержка импорта из Дневника и Журнала записи пациентов</b>
                </div>

                <div class="dropdown-divider"></div>

                <form name="miasImportWindowMainForm" id="miasImportWindowMainForm" action="php_processor.php?new"
                      method="post">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Копия таблицы из ЕМИАС:</label>
                        <textarea class="form-control" name="miasTextMain" id="miasTextMain"
                                  placeholder="Скопировать таблицу из распечатки (Ctrl+С) и вставить сюда (Ctrl+V)"
                                  rows="10"></textarea>
                    </div>

                    <div class="form-group">
                        <b>Правила импорта</b>
                        <div class="row">
                            <div class="col">
                                <input class="form-check-input" type="radio"
                                       name="importSettings" id="importSettingsRaw" value="raw" checked>
                                <label <?= super_bootstrap_tooltip('Импортируются все пациенты из списка, дублируя присутствующих в журнале приема') ?>
                                        class="form-check-label box-label"
                                        for="importSettingsRaw"><span></span>Как есть</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="radio"
                                       name="importSettings" id="importSettingsIgnore" value="ignore">
                                <label <?= super_bootstrap_tooltip('Импортируются пациенты из списка, кроме тех, кто уже принят. Непринятые пациенты дублируются') ?>
                                        class="form-check-label box-label"
                                        for="importSettingsIgnore"><span></span>Игнорировать принятых</label>
                            </div>
                            <div class="col">
                                <input class="form-check-input" type="radio"
                                       name="importSettings" id="importSettingsNew" value="new">
                                <label <?= super_bootstrap_tooltip('Импортируются только те пациенты, которых нет в текущем дне приёма') ?>
                                        class="form-check-label box-label"
                                        for="importSettingsNew"><span></span>Добавить только новых</label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row">
                        <div class="col text-right">Дата приёма:</div>
                        <div class="col-auto">
                            <input type="date" class="col-auto form-control form-control-sm" name="importDate"
                                   id="importDate" value="<?= date('Y-m-d', time()) ?>">
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row">
                        <div class="col text-right">Врач (по умолчанию):</div>
                        <div class="col-auto">
                            <?php
                            $zeroArray = array(
                                'key' => 0,
                                'value' => 'выберите врача'
                            );
                            $selectedArray = array(
                                'key' => 'doctor_id',
                                'value' =>  $USER_PROFILE['doctor_id']
                            );
                            $SelectDoctor = array2select($DoctorsListId, 'doctor_id', 'doctor_f', 'doctor_id', ' class="form-control form-control-sm" name="doctor_id"', $zeroArray, $selectedArray);
                            echo $SelectDoctor['result'];
                            ?>
                        </div>
                    </div>

                    <input type="submit" style="display: none"/>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="newMiasMain">Импорт</button>
            </div>
        </div>
    </div>
</div>