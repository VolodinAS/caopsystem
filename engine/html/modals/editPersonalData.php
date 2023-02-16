<div class="modal fade" id="editPersonalDataCard" tabindex="-1" role="dialog" aria-labelledby="editPersonalDataCardModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonalDataCardModal">ПЕРСОНАЛЬНЫЕ ДАННЫЕ ПАЦИЕНТА</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body size-16pt" id="editPersonalDataCardBody">

                <div class="input-group input-group-sm">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <? if ( in_array("2", $Doctor_Access_Array) ): ?>
                    <button type="button" class="btn btn-danger" id="deletePatientData">УДАЛИТЬ</button>
                <? endif; ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>