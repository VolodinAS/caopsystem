<?php
$DIAGNOSIS = $RouteSheetPrint['rs_ds_text'];
if ( strlen($RouteSheetPrint['rs_ds_mkb']) > 0 )
{
	$DIAGNOSIS = '['.$RouteSheetPrint['rs_ds_mkb'].'] ' . $DIAGNOSIS;
}
?>

<table border="0" class="tbc" width="100%">
	<tr>
		<td width="100%">
			<table border="0" class="tbc" width="100%">
				<tr>
					<td width="50%">&nbsp;
					</td>
					<td align="center" class="size-8pt">Приложение 1<br>
						к приказу министерства<br>
						здравоохранения Самарской области<br>
						от 22.04.2021 №547<br>
						<br>
						Приложение 4<br>
						к Алгоритму организации оказания<br>
						онкологической помощи взрослому<br>
						населению в медицинских организациях,<br>
						подведомственных министерству<br>
						здравоохранения Самарской области,<br>
						утвержденному приказом министерства<br>
						здравоохранения  Самарской области<br>
						от 15.05.2021 №684
					</td>
				</tr>
			</table>
			<br>
		</td>
	</tr>
	<tr>
		<td width="100%" align="center">
			<b>Маршрутный лист</b><br>
			больного с впервые выявленным злокачественным новообразованием (ЗНО)<br>
			(заполняется на все впервые выявленные ЗНО)<br><br>
		</td>
	</tr>
	<tr>
		<td>

			<table width="100%" class="tbc" valign="top">
				<tr>
					<td width="1%">
						Ф.И.О.:
					</td>
					<td class="border-bottom">
						<?=mb_ucwords($PatientData['patid_name'])?>
					</td>
				</tr>
			</table>

			<table width="100%" class="tbc" valign="top">
				<tr>
					<td width="1%">
						Дата&nbsp;рождения:
					</td>
					<td class="border-bottom">
						<?=$PatientData['patid_birth']?>
					</td>
				</tr>
			</table>

			<table width="100%" class="tbc">
				<tr>
					<td width="1%" valign="top">
						Адрес&nbsp;места&nbsp;жительства:
					</td>
					<td class="border-bottom">
						г. Тольятти, <?=$PatientData['patid_address']?>
					</td>
				</tr>
			</table>

			<table width="100%" class="tbc">
				<tr>
					<td width="1%" valign="top">
						Диагноз:
					</td>
					<td>
						<p><span>
							<?=$DIAGNOSIS?>
						</span></p>
					</td>
				</tr>
			</table>

			<table width="100%" class="tbc">
				<tr>
					<td width="1%" valign="top">
						Стадия&nbsp;заболевания&nbsp;(TNM):
					</td>
					<td>
						<table class="tbc" width="100%">
							<tr>
								<td width="10%" class="border-bottom" align="right">
									<?=$RouteSheetPrint['rs_tnm_prefix']?>
								</td>
								<td width="1%" align="right">T</td>
								<td width="15%" class="border-bottom">
									<?=$RouteSheetPrint['rs_tnm_t']?>
								</td>
								<td width="3%" align="right">N</td>
								<td width="15%" class="border-bottom">
									<?=$RouteSheetPrint['rs_tnm_n']?>
								</td>
								<td width="3%" align="right">M</td>
								<td width="15%" class="border-bottom">
									<?=$RouteSheetPrint['rs_tnm_m']?>
								</td>
								<td width="3%" align="right">G</td>
								<td width="15%" class="border-bottom">
									<?=$RouteSheetPrint['rs_tnm_g']?>
								</td>
								<td width="3%" align="right">Стадия:</td>
								<td width="15%" class="border-bottom">
									<?=$RouteSheetPrint['rs_stadia']?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table width="100%" class="tbc">
				<tr>
					<td width="1%" valign="top">
						Морфология:
					</td>
					<td>
						<p><span>
							<?=$RouteSheetPrint['rs_morphology']?>
						</span></p>
					</td>
				</tr>
			</table>

			<table width="100%" class="tbc">
				<tr>
					<td width="1%" valign="top">
						Дата&nbsp;установки&nbsp;диагноза:
					</td>
					<td>
						<p><span>
							<?=$RouteSheetPrint['rs_ds_set_date']?>
						</span></p>
					</td>
					<td width="1%" valign="top">
						обстоятельства&nbsp;постановки&nbsp;на&nbsp;учет:
					</td>
					<td>
						<p><span>
							<?=$RouteSheetPrint['rs_ds_cond']?>
						</span></p>
					</td>
				</tr>
			</table>

			<table width="100%" class="tbc">
				<tr>
					<td width="1%" valign="top">
						Дата&nbsp;постановки&nbsp;на&nbsp;учет:
					</td>
					<td width="49%">
						<p><span>
							<?=$RouteSheetPrint['rs_ds_du_date']?>
						</span></p>
					</td>
					<td width="1%" valign="top">
						Дата&nbsp;смерти:
					</td>
					<td width="49%">
						<p><span>
							<?=$RouteSheetPrint['rs_ds_dead_date']?>
						</span></p>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td width="100%" align="center">
			<br>
			Сроки обращения на этапы маршрутизации
		</td>
	</tr>

	<tr>
		<td width="100%">
			<table width="100%" border="1" class="tbc" cellpadding="5" cellspacing="5">
				<tr>
					<td width="60%" align="center">Этап обращения</td>
					<td width="10%" align="center">Дата</td>
					<td width="30%" align="center">Комментарий</td>
				</tr>
				<tr>
					<td>Появление первых признаков ЗНО:</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_po_pe_pr_zno_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_po_pe_pr_zno_comm']?></td>
				</tr>
				<tr>
					<td>первичное обращение за медицинской помощью:</td>
					<td align="center" colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td><?=_nbsp(5)?>На фельдшерско-акушерский пункт (офис врача общей практики):</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_fap_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_fap_comm']?></td>
				</tr>
				<tr>
					<td><?=_nbsp(5)?>В смотровой кабинет:</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_sm_kab_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_sm_kab_comm']?></td>
				</tr>
				<tr>
					<td><?=_nbsp(5)?>К врачу участковой поликлиники (женской консультации, стоматологической поликлиники, кожно-венерологического диспансера):</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_vr_u4_pol_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_vr_u4_pol_comm']?></td>
				</tr>
				<tr>
					<td><?=_nbsp(5)?>В стационар общей лечебной сети:</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_stac_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_stac_comm']?></td>
				</tr>
				<tr>
					<td>обращение в первичный онкологический кабинет:</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_pe_onk_kab_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_pe_onk_kab_comm']?></td>
				</tr>
				<tr>
					<td>обращение в центр амбулаторной онкологической помощи:</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_caop_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_caop_comm']?></td>
				</tr>
				<tr>
					<td>обращение в консультативно-диагностическую поликлинику ГБУЗ «СОКОД» (ГБУЗ «ТГКБ №5»):</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_kdo_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_kdo_comm']?></td>
				</tr>
				<tr>
					<td>начало специализированного лечения ЗНО:</td>
					<td align="center"><?=$RouteSheetPrint['rs_stage_cure_date']?></td>
					<td><?=$RouteSheetPrint['rs_stage_cure_comm']?></td>
				</tr>
			</table>
			<br><br>
		</td>
	</tr>

	<tr>
		<td>
			Ф.И.О. должность специалиста: <?=$DoctorData['doctor_duty']?> <?=docNameShort($DoctorData, 'famimot')?>
		</td>
	</tr>

	<tr>
		<td align="right">
			Дата заполнения: <?=$RouteSheetPrint['rs_fill_date']?> г.
		</td>
	</tr>
</table>