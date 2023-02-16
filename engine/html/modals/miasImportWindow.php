<div class="modal fade" id="miasImportWindow" tabindex="-1" role="dialog" aria-labelledby="miasImportWindow"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miasImportWindow">Импорт списка из МИАС</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="miasImport">

                <form name="miasImportForm" id="miasImportForm" action="php_processor.php?new" method="post">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Копия текста из экспорт-панели МИАС:</label>
                        <textarea class="form-control" name="miasText" id="miasText"
                                  placeholder="Копировать - Вставить, поля: Время, Пациент, Дата рождения, Ссылка"
                                  rows="15"></textarea>
                    </div>

                    <input type="submit" style="display: none"/>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="newMias">Импорт</button>
            </div>
        </div>
    </div>
</div>