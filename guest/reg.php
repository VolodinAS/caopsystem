

<br>
<div class="h4">ВНИМАНИЕ! После регистрации, Вам необходимо обратиться к Володину Александру Сергеевичу для получения полноценного доступа к системе!</div>
<br>
<br>

<form id="form_reg">
    <input type="hidden" name="action" value="reg">

    <div class="form-group">
        <label for="doctor_miac_login">Логин (из ЕМИАС)</label>
        <input type="text" class="form-control form-control-lg" name="doctor_miac_login" id="doctor_miac_login" placeholder="Логин">
    </div>

    <div class="form-group">
        <label for="doctor_miac_pass">Пароль (из ЕМИАС)</label>
        <input type="password" class="form-control form-control-lg" name="doctor_miac_pass" id="doctor_miac_pass" placeholder="Пароль">
    </div>

    <div class="form-group">
        <label for="doctor_f">Ваша фамилия</label>
        <input type="text" class="form-control form-control-lg " name="doctor_f" id="doctor_f" placeholder="Фамилия" value="">
    </div>

    <div class="form-group">
        <label for="doctor_i">Ваше имя</label>
        <input type="text" class="form-control form-control-lg" name="doctor_i" id="doctor_i" placeholder="Имя" value="">
    </div>

    <div class="form-group">
        <label for="doctor_o">Ваше отчество</label>
        <input type="text" class="form-control form-control-lg" name="doctor_o" id="doctor_o" placeholder="Отчество" value="">
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-primary btn-lg btn-block" id="button_reg">Регистрация</button>
    </div>

</form>

<?php
include ("engine/html/modals/regMessageBox.php");
?>

<script defer src="/engine/js/reg.js" type="text/javascript"></script>