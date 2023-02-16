<?php
/**
 *
 * Удаляет из диагнозов поддиагнозы вместе с точкой
 *
 * @param $value
 * @return string
 */
function mkbNoSub($value)
{
    $value_data = explode('.', $value);
	return $value_data[0];
}

/**
 *
 * Добавляет к диагнозу поисковый процент (%) для включения поддиагнозов
 *
 * @param $value
 * @return string
 */
function statePercent($value)
{
	return $value . '%';
}

function onlyMainState($value)
{
	if ( $value == "is" ) return $value;
	return $value[0];
}

function getMorphTitle($value)
{
//	debug($value);
    global $ZNODUMorphMethodId;
    $data = $ZNODUMorphMethodId['id' . $value];
    return $data['morph_id'] . '~' . $data['morph_title'];
}

function getDoctorName($value)
{
    global $DoctorsListId;
    $doctor = $DoctorsListId['id' . $value];
    return $doctor['doctor_id'] . '~' . docNameShort($doctor);
}

function getSurveyName($value)
{
    global $AllSurveys;
	$AllSurveysTypes = getDoctorsById($AllSurveys, 'survey_id');
    $survey = $AllSurveysTypes['id' . $value];
    return $survey['survey_id'] . '~' . $survey['survey_title'];
}