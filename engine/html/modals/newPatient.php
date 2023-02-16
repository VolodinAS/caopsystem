<?php
$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');
?>
<div class="modal fade" id="newPatient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Добавить пациента в прием</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

                <div class="modal-body" id="patientNameModal">
                    <div id="patientStatus">
                        <? bt_notice('<b>ПРОВЕРЬТЕ ПАЦИЕНТА ПО НОМЕРУ КАРТЫ</b>', BT_THEME_WARNING);?>
                    </div>

                    <form name="newPat" id="newPat" action="php_journal.php?new" method="post">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="namePat" class="col-form-label">Ф.И.О.:</label>
                                    <div class="ui-widget">
                                        <input required type="text" class="form-control form-control-lg default-disabled required-field" id="namePat" name="name" placeholder="В формате: иванов и и" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="birth" class="col-form-label">Дата рождения:</label>
                                    <input required type="text" class="form-control form-control-lg russianBirth default-disabled required-field" id="birth" name="birth" placeholder="В формате: 01.01.1999" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="phone" class="col-form-label">Телефон:</label>
                                    <input required type="text" class="form-control form-control-lg default-disabled required-field" id="phone" name="phone" placeholder="Телефон" disabled>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="address" class="col-form-label">Адрес:</label>
                                    <input required type="text" class="form-control form-control-lg default-disabled required-field indiraaccessable" id="address" name="address" placeholder="Адрес" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cardid" class="col-form-label">ID карты:</label>
                                    <input required type="text" class="form-control form-control-lg required-field" id="cardid" name="cardid" placeholder="Номер карты">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="timer" class="col-form-label">Время:</label>
                                    <input required type="text" class="form-control form-control-lg russianTime default-disabled required-field" id="timer" name="timer" placeholder="В формате: 00:00" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="insurance_company" class="col-form-label">Страховая компания:</label>
                                    <?php
                                    $mep = '';
                                    $defaultSelect = array(
                                        'key'   =>   'patid_insurance_company',
                                        'value'   =>  $PatientPersonalData['patid_insurance_number']
                                    );
                                    $defaultArr = array(
                                        'key'   =>   '0',
                                        'value'   =>  'ВЫБЕРИТЕ'
                                    );
                                    $CompanyListSelector = array2select($CompanyList, 'insurance_id', 'insurance_title', 'insurance_company', ' id="insurance_company" class="form-control form-control-lg"', $defaultArr, $defaultSelect);
                                    if ( $CompanyListSelector['stat'] == RES_SUCCESS )
                                    {
                                        echo $CompanyListSelector['result'];
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="insurance_number" class="col-form-label">Номер страхового полиса:</label>
                                    <input required type="text" class="form-control form-control-lg default-disabled required-field" id="insurance_number" name="insurance_number" placeholder="Номер полиса" disabled>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary invisible" id="addNew">Добавить</button>
                    <button type="button" class="btn btn-danger" id="checkCard">ПРОВЕРИТЬ</button>
                </div>

		</div>
	</div>
</div>