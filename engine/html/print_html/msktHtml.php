<?php
$CompanyList = getarr(CAOP_INSURANCE, "insurance_enabled='1'", "ORDER BY insurance_title ASC");
$CompanyListId = getDoctorsById($CompanyList, 'insurance_id');
?>
    <table width="100%" class="size-10pt lh">
        <tr>
            <td align="center">
                Министерство здравоохранения<br>
                и социального развития Российской Федерации
            </td>
            <td>&nbsp;</td>
            <td>
                Медицинская документация<br>
                Форма № 057/у-04________
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                Самарская область, г. Тольятти, бульвар Татищева, 24
            </td>
            <td>&nbsp;</td>
            <td>
                утверждена приказом
            </td>
        </tr>
        <tr>
            <td align="center">
                <?=$LPU_DOCTOR['lpu_blank_057'];?>
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="size-8pt" align="center">(наименование медицинского учреждения)</td>
            <td>&nbsp;</td>
            <td>Минздравсоцразвития России</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>от 22 ноября 2004 г. № 255</td>
        </tr>
    </table>
    
    <table cellpadding="2" class="size-12pt tbc" border="1">
        <tr>
            <td>Код ОГРН</td>
            <?php
            echo strInTD($LPU_DOCTOR['lpu_ogrn'])
            ?>
            
        </tr>
    </table>
    <br>
    <table width="100%" class="size-16pt">
        <tr>
            <td align="center"><b>НАПРАВЛЕНИЕ</b></td>
        </tr>
    </table>
    
    <?php
    $dt1 = $dt2 = $dt3 = 'no';
    switch ($BlankPrint['mskt_dir_type']){
        case '1':
            $dt1 = 'yes';
        break;
        case '2':
            $dt2 = 'yes';
        break;
        case '3':
            $dt3 = 'yes';
        break;
    }
    ?>
    
    <table width="100%" class="size-12pt">
        <tr>
            <td width="33%" align="center">
                <table>
                    <tr>
                        <td class="vertical-b">на госпитализацию</td>
                        <td class="vertical-b"><img width="24" src="/engine/images/icons/dirtype_<?=$dt1;?>.png"
                                 alt=""></td>
                    </tr>
                </table>
            </td>
            <td width="34%" align="center">
                <table>
                    <tr>
                        <td class="vertical-b">на обследование</td>
                        <td class="vertical-b"><img width="24" src="/engine/images/icons/dirtype_<?=$dt2;?>.png"
                                 alt=""></td>
                    </tr>
                </table>
            
            </td>
            <td width="33%" align="center">
                <table>
                    <tr>
                        <td class="vertical-b">на консультацию</td>
                        <td class="vertical-b"><img width="24" src="/engine/images/icons/dirtype_<?=$dt3;?>.png"
                                 alt=""></td>
                    </tr>
                </table>
            
            </td>
        </tr>
    </table>
    
    <table width="100%" class="size-12pt">
        <tr>
            <td align="center">
                <b>Наименование медицинского учреждения, куда направлен пациент</b>
            </td>
        </tr>
        <tr>
            <td align="center">
                (630370) ООО «МДЦ Здоровье-Ульяновск»<br>
                443096, г. Самара, ул. Полевая, д. 80, ком. 64-67
            </td>
        </tr>
    </table>
    <br>
    <table width="100%" class="size-12pt" cellpadding="1">
        <tr>
            <td width="30%"><b>1. Номер страхового полиса ОМС:</b></td>
            <td>
                <table class="tbc" border="1" cellpadding="5">
                    <tr>
                        <?php
                        echo strInTD($PatientData['patid_insurance_number'])
                        ?>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><b>Название страховой компании:</b></td>
            <td><?=$CompanyListId['id' . $PatientData['patid_insurance_company']]['insurance_title'];?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="right">
                <table>
                    <tr>
                        <td><b>2. Код льготы:</b></td>
                        <td>
                            <table class="tbc" border="1">
                                <tr>
                                    <?php
                                    echo strInTD(trim($BlankPrint['mskt_benefit_code']))
                                    ?>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><b>3. Фамилия, имя, отчество:</b></td>
            <td>
                <table width="100%" class="tbc" border="1" cellpadding="10">
                    <tr>
                        <td>
                            <?=mb_ucwords($PatientData['patid_name']);?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr><td colspan="2"></td></tr>
        
        <tr>
            <td><b>4. Дата рождения:</b></td>
            <td>
                <table width="100%" class="tbc" border="1" cellpadding="10">
                    <tr>
                        <td>
                            <?=$PatientData['patid_birth'];?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr><td colspan="2"></td></tr>
        
        <tr>
            <td><b>5. Адрес:</b></td>
            <td>
                <table width="100%" class="tbc" border="1" cellpadding="10">
                    <tr>
                        <td>
                            <?=$PatientData['patid_address'];?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr><td colspan="2"></td></tr>
        
        <tr>
            <td><b>6. Место работы, должность:</b></td>
            <td>
                <table width="100%" class="tbc" border="1" cellpadding="10">
                    <tr>
                        <td>
                            <?=$BlankPrint['mskt_job_place'];?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?=$BlankPrint['mskt_job_duty'];?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr><td colspan="2"></td></tr>
        
        <tr>
            <td><b>7. Код диагноза по МКБ-10:</b></td>
            <td>
                <table class="tbc size-14pt boldy" border="1" cellpadding="7">
                    <tr>
                        <?php
                        echo strInTD($BlankPrint['mskt_diagnosis_mkb'])
                        ?>
                    </tr>
                </table>
            </td>
        </tr>
    
        <tr><td colspan="2"></td></tr>
    
        <tr>
            <td><b>8. Обоснование направления:</b></td>
            <td>
                <table width="100%" class="tbc" border="1" cellpadding="5">
                    <tr>
                        <td>
                            <?=str_replace("\n", "<br>", $BlankPrint['mskt_dir_reason']);?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
    </table>
    <br>
    <table width="100%" class="tbc size-12pt" border="1" cellpadding="5">
        <tr>
            <td><b>9. Диагноз:</b> <?=$BlankPrint['mskt_diagnosis_text'];?></td>
        </tr>
    </table>
    <br><br>
    <?php
    $HeadmanData = $DoctorsListId['id16'];
    ?>
    <table width="100%" class="tbc size-12pt">
        <tr>
            <td>
                Медицинский работник,<br>направивший больного
            </td>
            <td align="center"><b>Врач-онколог</b></td>
            <td align="center">(<?=$DoctorData['doctor_miac_login'];?>) <?=docNameShort($DoctorData, 'famimot');?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center" class="size-10pt my-valign-top bt">(должность)</td>
            <td>&nbsp;</td>
            <td align="center" class="size-10pt my-valign-top bt">(подпись)</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td align="center"><b>Заведущий ЦАОП</b></td>
            <td align="center">(<?=$HeadmanData['doctor_miac_login'];?>) <?=docNameShort($HeadmanData, 'famimot');?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="center" class="size-10pt my-valign-top bt">(должность)</td>
            <td>&nbsp;</td>
            <td align="center" class="size-10pt my-valign-top bt">(подпись)</td>
        </tr>
    
        <tr>
            <td colspan="4"></td>
        </tr>
    
        <tr>
            <td class="size-20pt" align="right"><b>м.п.</b></td>
            <td>&nbsp;</td>
            <td class="size-16pt" align="center">
                <?php
                echo processRussianDateReverse($BlankPrint['mskt_date'], '.', ' ') . ' г.'
                ?>
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>

<?php
//debug($BlankPrint);
//debug($PatientData);
//debug($DoctorData);
?>