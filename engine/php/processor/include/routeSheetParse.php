<?php
$response['htmlData'] .= spoiler_begin_return($rs_ds_text, 'rs_' . $rs_id, '');

$col_width = '5';

$response['htmlData'] .= '<div class="container">';
{
	// Стадия заболевания
	$response['htmlData'] .= '<div class="row">';
	{
		$response['htmlData'] .= '<div class="col-'.$col_width.'">';
		$response['htmlData'] .= '<b>Стадия заболевания:</b>';
		$response['htmlData'] .= '</div>';
	}
	{
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= $rs_stadia;
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	// Стадия заболевания по TNM
	$response['htmlData'] .= '<div class="row">';
	{
		$response['htmlData'] .= '<div class="col-'.$col_width.'">';
		$response['htmlData'] .= '<b>Стадия заболевания по TNM:</b>';
		$response['htmlData'] .= '</div>';
	}
	{
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= $rs_tnm_prefix . ' '.wrapper('T').' ' . $rs_tnm_t . ' '.wrapper('N').' ' . $rs_tnm_n . ' '.wrapper('M').' ' . $rs_tnm_m . ' ('.wrapper('G').$rs_tnm_g.')';
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	// Морфология опухоли
	$response['htmlData'] .= '<div class="row">';
	{
		$response['htmlData'] .= '<div class="col-'.$col_width.'">';
		$response['htmlData'] .= '<b>Морфология опухоли:</b>';
		$response['htmlData'] .= '</div>';
	}
	{
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= $rs_morphology;
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	// Появление первых признаков ЗНО
	$response['htmlData'] .= '<div class="row">';
	{
		$response['htmlData'] .= '<div class="col-'.$col_width.'">';
		$response['htmlData'] .= '<b>Появление первых признаков ЗНО:</b>';
		$response['htmlData'] .= '</div>';
	}
	{
		$rs_stage_po_pe_pr_zno_comm_TEXT = ( strlen($rs_stage_po_pe_pr_zno_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_po_pe_pr_zno_comm . '</span>' : '';
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= $rs_stage_po_pe_pr_zno_date . $rs_stage_po_pe_pr_zno_comm_TEXT;
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	// Обращение к врачу участковой поликлиники
	if ( strlen($rs_stage_vr_u4_pol_date) > 0 && !ifound($rs_stage_vr_u4_pol_date, '__') )
	{
		$response['htmlData'] .= '<div class="row">';
		{
			$response['htmlData'] .= '<div class="col-'.$col_width.'">';
			$response['htmlData'] .= '<b>Обращение к врачу участковой поликлиники:</b>';
			$response['htmlData'] .= '</div>';
		}
		{
			$rs_stage_vr_u4_pol_comm_TEXT = ( strlen($rs_stage_vr_u4_pol_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_vr_u4_pol_comm . '</span>' : '';
			$response['htmlData'] .= '<div class="col">';
			$response['htmlData'] .= $rs_stage_vr_u4_pol_date . $rs_stage_vr_u4_pol_comm_TEXT;
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
		
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	}
	
	// Обращение на фельдшерско-акушерский пункт
	if ( strlen($rs_stage_fap_date) > 0 && !ifound($rs_stage_fap_date, '__') )
	{
		$response['htmlData'] .= '<div class="row">';
		{
			$response['htmlData'] .= '<div class="col-'.$col_width.'">';
			$response['htmlData'] .= '<b>Обращение на фельдшерско-акушерский пункт:</b>';
			$response['htmlData'] .= '</div>';
		}
		{
			$rs_stage_fap_comm_TEXT = ( strlen($rs_stage_fap_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_fap_comm . '</span>' : '';
			$response['htmlData'] .= '<div class="col">';
			$response['htmlData'] .= $rs_stage_fap_date . $rs_stage_fap_comm_TEXT;
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
		
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	}
	
	// Обращение в смотровой кабинет
	if ( strlen($rs_stage_sm_kab_date) > 0 && !ifound($rs_stage_sm_kab_date, '__') )
	{
		$response['htmlData'] .= '<div class="row">';
		{
			$response['htmlData'] .= '<div class="col-'.$col_width.'">';
			$response['htmlData'] .= '<b>Обращение в смотровой кабинет:</b>';
			$response['htmlData'] .= '</div>';
		}
		{
			$rs_stage_sm_kab_comm_TEXT = ( strlen($rs_stage_sm_kab_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_sm_kab_comm . '</span>' : '';
			$response['htmlData'] .= '<div class="col">';
			$response['htmlData'] .= $rs_stage_sm_kab_date . $rs_stage_sm_kab_comm_TEXT;
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
		
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	}
	
	// Обращение в стационар общей лечебной сети
	if ( (strlen($rs_stage_stac_date) > 0) && ( !ifound($rs_stage_stac_date, '__') ) )
	{
		$response['htmlData'] .= '<div class="row">';
		{
			$response['htmlData'] .= '<div class="col-'.$col_width.'">';
			$response['htmlData'] .= '<b>Обращение в стационар общей лечебной сети:</b>';
			$response['htmlData'] .= '</div>';
		}
		{
			$rs_stage_stac_comm_TEXT = ( strlen($rs_stage_stac_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_stac_comm . '</span>' : '';
			$response['htmlData'] .= '<div class="col">';
			$response['htmlData'] .= $rs_stage_stac_date . $rs_stage_stac_comm_TEXT;
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
		
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	}
	
	// Обращение в первичный онкологический кабинет
	if ( (strlen($rs_stage_pe_onk_kab_date) > 0) && ( !ifound($rs_stage_pe_onk_kab_date, '__') ) )
	{
		$response['htmlData'] .= '<div class="row">';
		{
			$response['htmlData'] .= '<div class="col-'.$col_width.'">';
			$response['htmlData'] .= '<b>Обращение в первичный онкологический кабинет:</b>';
			$response['htmlData'] .= '</div>';
		}
		{
			$rs_stage_pe_onk_kab_comm_TEXT = ( strlen($rs_stage_pe_onk_kab_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_pe_onk_kab_comm . '</span>' : '';
			$response['htmlData'] .= '<div class="col">';
			$response['htmlData'] .= $rs_stage_pe_onk_kab_date . $rs_stage_pe_onk_kab_comm_TEXT;
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
		
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	}
	
	// Обращение в ЦАОП
	$response['htmlData'] .= '<div class="row">';
	{
		$response['htmlData'] .= '<div class="col-'.$col_width.'">';
		$response['htmlData'] .= '<b>Обращение в ЦАОП:</b>';
		$response['htmlData'] .= '</div>';
	}
	{
		$rs_stage_caop_comm_TEXT = ( strlen($rs_stage_caop_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_caop_comm . '</span>' : '';
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= $rs_stage_caop_date . $rs_stage_caop_comm_TEXT;
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	// Дата установки диагноза
	$response['htmlData'] .= '<div class="row">';
	{
		$response['htmlData'] .= '<div class="col-'.$col_width.'">';
		$response['htmlData'] .= '<b>Дата установки диагноза:</b>';
		$response['htmlData'] .= '</div>';
	}
	{
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= $rs_ds_set_date;
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	// Дата постановки на учет
	$response['htmlData'] .= '<div class="row">';
	{
		$response['htmlData'] .= '<div class="col-'.$col_width.'">';
		$response['htmlData'] .= '<b>Дата постановки на учет:</b>';
		$response['htmlData'] .= '</div>';
	}
	{
		$response['htmlData'] .= '<div class="col">';
		$response['htmlData'] .= $rs_ds_du_date;
		$response['htmlData'] .= '</div>';
	}
	$response['htmlData'] .= '</div>';
	
	$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	
	// Обращение в консультативно-диагностическую поликлинику ГБУЗ «СОКОД» (ГБУЗ «ТГКБ №5»)
	if ( (strlen($rs_stage_kdo_date) > 0) && ( !ifound($rs_stage_kdo_date, '__') ) )
	{
		$response['htmlData'] .= '<div class="row">';
		{
			$response['htmlData'] .= '<div class="col-'.$col_width.'">';
			$response['htmlData'] .= '<b>Обращение в консультативно-диагностическую поликлинику ГБУЗ «СОКОД» (ГБУЗ «ТГКБ №5»):</b>';
			$response['htmlData'] .= '</div>';
		}
		{
			$rs_stage_kdo_comm_TEXT = ( strlen($rs_stage_kdo_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_kdo_comm . '</span>' : '';
			$response['htmlData'] .= '<div class="col">';
			$response['htmlData'] .= $rs_stage_kdo_date . $rs_stage_kdo_comm_TEXT;
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
		
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	}
	
	// Начало специализированного лечения ЗНО
	if ( (strlen($rs_stage_cure_date) > 0) && ( !ifound($rs_stage_cure_date, '__') ) )
	{
		$response['htmlData'] .= '<div class="row">';
		{
			$response['htmlData'] .= '<div class="col-'.$col_width.'">';
			$response['htmlData'] .= '<b>Начало специализированного лечения ЗНО:</b>';
			$response['htmlData'] .= '</div>';
		}
		{
			$rs_stage_cure_comm_TEXT = ( strlen($rs_stage_cure_comm) > 0 ) ? '<br><span class="text-muted font-weight-bold small">' . $rs_stage_cure_comm . '</span>' : '';
			$response['htmlData'] .= '<div class="col">';
			$response['htmlData'] .= $rs_stage_cure_date . $rs_stage_cure_comm_TEXT;
			$response['htmlData'] .= '</div>';
		}
		$response['htmlData'] .= '</div>';
		
		$response['htmlData'] .= '<div class="dropdown-divider"></div>';
	}
	
}
$response['htmlData'] .= '</div>';

$response['htmlData'] .= spoiler_end_return();