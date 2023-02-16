<?php
$s_order = false;

$s_index = searchArray($TNM_S, 'stnmg_code', $zno_tnm_s);
if ($s_index['status'] == RES_SUCCESS) $s_order = $s_index['data']['stnmg_order'];
if (!$s_order)
{
	$sorting_error = true;
	$sorting_errors_data[] = array(
		'data' => $ZNODatum,
		'field' => $zno_tnm_s
	);
}

$t_index = searchArray($TNM_T, 'stnmg_code', $zno_tnm_t);
if ($t_index['status'] == RES_SUCCESS) $t_order = $t_index['data']['stnmg_order'];
if (!$t_index)
{
	$sorting_error = true;
	$sorting_errors_data[] = array(
		'data' => $ZNODatum,
		'field' => $t_index
	);
}

$n_index = searchArray($TNM_N, 'stnmg_code', $zno_tnm_n);
if ($n_index['status'] == RES_SUCCESS) $n_order = $n_index['data']['stnmg_order'];
if (!$n_index)
{
	$sorting_error = true;
	$sorting_errors_data[] = array(
		'data' => $ZNODatum,
		'field' => $n_index
	);
}

$m_index = searchArray($TNM_M, 'stnmg_code', $zno_tnm_M);
if ($m_index['status'] == RES_SUCCESS) $m_order = $m_index['data']['stnmg_order'];
if (!$m_index)
{
	$sorting_error = true;
	$sorting_errors_data[] = array(
		'data' => $ZNODatum,
		'field' => $m_index
	);
}