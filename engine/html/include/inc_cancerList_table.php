<thead>
<tr>
	<th scope="col" class="text-center" width="1%" data-title="npp">№</th>
	<!--        <th scope="col" class="text-center" width="1%" data-title="patid_id">ID</th>-->
	<!--        <th scope="col" class="text-center" width="1%" data-title="patid_ident">Карта</th>-->
	<th scope="col" class="text-center" data-title="patid_name">
		Фамилия И.О.
		<input type="text" class="form-control-sm form-control searchByTableField"
		       data-classname="patient-name" data-tablename="cancerList" disabled>
	</th>
	<th scope="col" class="text-center" data-title="patid_birth">Дата рождения</th>
	<th scope="col" class="text-center" width="1%" data-title="cancer_added_date">Добавлен</th>
	<th scope="col" class="text-center" width="1%" data-title="cancer_route_date">Дата обязательного МЛ</th>
	<th scope="col" class="text-center" width="1%" data-title="cancer_ds">МКБ</th>
	<th scope="col" class="text-center" data-title="cancer_ds_text">Диагноз</th>
	<th scope="col" class="text-center" width="1%" data-title="rs_stage_po_pe_pr_zno_date">Первичные признаки
	</th>
	<th scope="col" class="text-center" width="1%" data-title="rs_stage_fap_date_OR_rs_stage_vr_u4_pol_date">
		Первичное обращение
	</th>
	<th scope="col" class="text-center" width="1%"
	    data-title="rs_stage_caop_date"><?= nbsper('Обращение в ЦАОП'); ?></th>
	<th scope="col" class="text-center" width="1%" data-title="rs_ds_set_date" width="1%">Установлен</th>
	<th scope="col" class="text-center" width="1%"
	    data-title="rs_stage_cure_date"><?= nbsper('Начало спец. лечения'); ?></th>
	<th scope="col" class="text-center" width="1%" data-title="cancer_doctor_id" width="1%">Врач</th>
	<th scope="col" class="text-center" width="1%" data-title="journal_visits" width="1%">Действия</th>
	<!--        <th scope="col" class="text-center" width="1%" data-title="morphology">Морфология</th>-->
	<!--<th scope="col" class="text-center" width="1%" data-title="actions">Действия</th>-->
</tr>
</thead>