<?php
$JournalAll = getarr(CAOP_JOURNAL, "journal_patid='0'", "ORDER BY journal_id ASC LIMIT 1");
$field_patient_name = 'journal_patient_name';
$field_patient_ident = 'journal_patient_ident';
$field_patient_birth = 'journal_patient_birth';
$field_patient_phone = 'journal_patient_phone';
$field_patient_address = 'journal_patient_address';
$querySearch = "SELECT * FROM {$CAOP_PATIENTS} WHERE patid_ident='[QUERY_IDENT]' AND patid_birth='[QUERY_BIRTH]' AND patid_name LIKE '[QUERY_NAME]'";
$UPDATED_TABLE =CAOP_JOURNAL;
$UPDATED_TABLE_PATID = 'journal_patid';
$UPDATED_TABLE_FID = 'journal_id';