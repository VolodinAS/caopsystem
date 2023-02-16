<?php

$SexArray = getarr(CAOP_SEX, "1", "ORDER BY sex_id ASC");
$SexTypesId = getDoctorsById($SexArray, 'sex_id');

$CountySideArea = getarr(CAOP_COUNTRYSIDE_AREA, 1, "ORDER BY area_id ASC");
$CountySideAreaTypesId = getDoctorsById($CountySideArea, 'area_id');

$MorphTypes = getarr(CAOP_MORPH_TYPE, 1, "ORDER BY morph_type_id ASC");
$MorphTypesId = getDoctorsById($MorphTypes, 'morph_type_id');

$MorphConfirm = getarr(CAOP_MORPH_YES_NO, 1, "ORDER BY confirm_id ASC");
$MorphConfirmId = getDoctorsById($MorphConfirm, 'confirm_id');

$sex = $BlankPrint['morph_sex'] . ' - ' . $SexTypesId['id' . $BlankPrint['morph_sex']]['sex_title'];
$area = $BlankPrint['morph_area'] . ' - ' . $CountySideAreaTypesId['id' . $BlankPrint['morph_area']]['area_title'];
$method = $BlankPrint['morph_method'] . ' - ' . $MorphTypesId['id' . $BlankPrint['morph_method']]['morph_type_title'];
$method = str_replace(' (рекомендуется)', '', $method);
$phormaline = $BlankPrint['morph_phormaline'] . ' - ' . $MorphConfirmId['id' . $BlankPrint['morph_phormaline']]['confirm_title'];
$birth_data = explode('.', $PatientData['patid_birth']);
?>
    <table class="tbc size-8pt"
           width="100%">
        <tr>
            <td width="33%"
                align="center">
                Наименование медицинской организации:<br>
                <?=$LPU_DOCTOR['lpu_short_name'];?><br>
                Адрес: <?=$LPU_DOCTOR['lpu_headquarter_address'];?>
            </td>
            <td>&nbsp;</td>
            <td width="33%">
                <table class="tbc"
                       width="100%">
                    <tr>
                        <td>
                            Код формы по ОКУД ____________________<br>
                            Код учреждения по ОКПО _______________
                            <br><br>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            Медицинская документация<br>
                            Учетная форма №014/у<br>
                            Утверждена приказом Минздрава России<br>
                            от 24.03.2016 г. №179н
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="tbc size-12pt"
           width="100%">
        <tr>
            <td align="center">
                <b>
                    НАПРАВЛЕНИЕ<br>
                    НА ПРИЖИЗНЕННОЕ ПАТОЛОГО-АНАТОМИЧЕСКОЕ ИССЛЕДОВАНИЕ<br>
                    БИОПСИЙНОГО (ОПЕРАЦИОННОГО) МАТЕРИАЛА<br>
                </b>
            </td>
        </tr>
    </table>

    <div class="size-10pt">

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>1. Отделение, направившее биопсийный</b> (операционный) <b>материал:</b>'); ?>
                </td>
                <td class="border-bottom"><?=$LPU_DOCTOR['lpu_blank_name'];?></td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>2. Фамилия, имя, отчество</b> (при наличии) <b>пациента:</b>'); ?>
                </td>
            </tr>
            <tr>
                <td class="border-bottom">
					<?= mb_ucwords($PatientData['patid_name']); ?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td>
                    <b>3. Пол:</b> <u><?= $sex; ?></u>
                </td>
                <td align="right">
                    <b>4. Дата рождения:</b>
                    число <u><?= str_nbsp($birth_data[0], 10); ?></u>
                    месяц <u><?= str_nbsp($birth_data[1], 10); ?></u>
                    год <u><?= str_nbsp($birth_data[2], 10); ?></u>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="50%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>5. Полис ОМС:</b>'); ?>
                            </td>
                            <td class="border-bottom">
								<?= mb_ucwords($PatientData['patid_insurance_number']); ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>6. СНИЛС:</b>'); ?>
                            </td>
                            <td class="border-bottom">
								<?= mb_ucwords($BlankPrint['morph_patient_snils']); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>7.1. Место регистрации:</b>'); ?>
                </td>
                <td class="border-bottom">
					<?= $PatientData['patid_address']; ?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>7.2. Телефон:</b>'); ?>
                </td>
                <td class="border-bottom">
					<?= $PatientData['patid_phone']; ?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>8. Местность:</b>'); ?>
                </td>
                <td class="border-bottom">
					<?= $area; ?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%"
                    valign="top">
					<?= nbsper('<b>9. Диагноз заболевания (состояния):</b>'); ?>
                </td>
                <td>
                    <p><span>
                    <?= $BlankPrint['morph_dg_text'] ?>
                </span></p>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>10. Код по МКБ-10*:</b>'); ?>
                </td>
                <td class="border-bottom">
					<?= $BlankPrint['morph_dg_mkb']; ?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
                    <b>11. Задача прижизненного патолого-анатомического исследования биопсийного (операционного)
                       материала:</b>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                    <span>
                        &nbsp;<?= $BlankPrint['morph_other_task']; ?>
                    </span>
                    </p>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
                    <b>12. Дополнительные клинические сведения (основные симптомы, оперативное или гормональное, или
                       лучевое
                       лечение, результаты инструментальных и лабораторных исследований):</b>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                    <span>
                        &nbsp;<?= $BlankPrint['morph_other_addon']; ?>
                    </span>
                    </p>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
                    <b>13. Результаты предыдущих прижизненных патолого-анатомических исследований (наименование
                       медицинской
                       организации, дата, регистрационный номер, заключение):</b>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                    <span>
                        &nbsp;<?= $BlankPrint['morph_other_prev']; ?>
                    </span>
                    </p>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
                    <b>14. Проведенное предоперационное лечение (вид лечения, его сроки, дозировка лекарственного
                       препарата):</b>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                    <span>
                        &nbsp;<?= $BlankPrint['morph_other_prev']; ?>
                    </span>
                    </p>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>15. Способ получения биопсийного (операционного материала):</b>'); ?>
                </td>
                <td class="border-bottom">
					<?= $method; ?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="50%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>16. Дата забора материала:</b>'); ?>
                            </td>
                            <td class="border-bottom">
								<?= $BlankPrint['morph_sampling_date']; ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>время:</b>'); ?>
                            </td>
                            <td class="border-bottom">
								<?= $BlankPrint['morph_sampling_time']; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="1%">
					<?= nbsper('<b>17. Материал помещен в 10%ный раствор нейтрального формалина:</b>'); ?>
                </td>
                <td class="border-bottom">
					<?= $phormaline; ?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td>
                    <b>18. Маркировка биопсийного (операционного) материала</b> (расшифровка маркировки флаконов):
                </td>
            </tr>
        </table>
        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td>
					<?php
					$MarkerData = getarr(CAOP_MORPHOLOGY_MARKER, "marker_morph_id='{$BlankPrint['morph_id']}'", "ORDER BY marker_id ASC");
					if (count($MarkerData) > 0)
					{
						?>
                        <table class="table table-sm tbc"
                               cellpadding="5"
                               border="1">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="text-center"
                                    data-title="npp"
                                    width="1%">Номер флакона
                                </th>
                                <th scope="col"
                                    class="text-center"
                                    data-title="local">Локализация патологического процесса<br>(орган, топография)
                                </th>
                                <th scope="col"
                                    class="text-center"
                                    data-title="character">Характер патологического процесса<br>(эрозия, язва, полип,
                                                           пятно, узел, внешне измененная ткань, отношение к окружающим
                                                           тканям)
                                </th>
                                <th scope="col"
                                    class="text-center"
                                    data-title="amount"
                                    width="1%">Количество объектов
                                </th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							$npp = 1;
							foreach ($MarkerData as $markerDatum)
							{
								?>
                                <tr>
                                    <td data-cell="npp"
                                        class="text-center"
                                        align="center"><?= $npp; ?></td>
                                    <td data-cell="local">
										<?= $markerDatum['marker_local']; ?>
                                    </td>
                                    <td data-cell="character">
										<?= $markerDatum['marker_description']; ?>
                                    </td>
                                    <td data-cell="amount"
                                        align="center">
										<?= $markerDatum['marker_amount']; ?>
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
						bt_notice('ВЫ НЕ ВВЕЛИ НИ ЕДИНОЙ МАРКИРОВКИ');
					}
					?>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="70%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>19. Фамилия, инициалы врача:</b>'); ?>
                            </td>
                            <td class="border-bottom">
								<?= docNameShort($DoctorData) ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>подпись:</b>'); ?>
                            </td>
                            <td class="border-bottom">
                                &nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%"
               class="tbc margin-5">
            <tr>
                <td width="70%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>20. Дата направления:</b>'); ?>
                            </td>
                            <td class="border-bottom">
								<?= $BlankPrint['morph_date'] ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="100%"
                           class="tbc">
                        <tr>
                            <td width="1%">
								<?= nbsper('<b>телефон:</b>'); ?>
                            </td>
                            <td class="border-bottom">
                                &nbsp;58-13-17
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

<?php
//debug($BlankPrint);
//debug($PatientData);
debug();
?>