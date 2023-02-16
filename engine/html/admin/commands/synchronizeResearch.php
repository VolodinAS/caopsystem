<?php
$JournalAll = getarr(CAOP_RESEARCH, "research_patid='0'", "ORDER BY research_id ASC LIMIT 1");
$field_patient_name = 'research_patient_name';
$field_patient_birth = 'research_patient_birth';
$field_patient_phone = 'research_phone';
$field_patient_address = '';
$querySearch = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_birth='[QUERY_BIRTH]' AND patid_name LIKE '[QUERY_NAME]'";
$UPDATED_TABLE =CAOP_RESEARCH;
$UPDATED_TABLE_PATID = 'research_patid';
$UPDATED_TABLE_FID = 'research_id';