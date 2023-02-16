<br>
<div class="h4">ВНИМАНИЕ! Если после регистрации еще не обращались к Володину Александру Сергеевичу, то скорее всего Ваша учетная запись не активирована!</div>
<br>
<br>

<form id="form_auth">
    <input type="hidden" name="action" value="auth">

    <div class="form-group">
        <label for="doctor_miac_login">Логин (из ЕМИАС)</label>
        <input type="text" class="form-control form-control-lg" name="doctor_miac_login" id="doctor_miac_login" placeholder="Логин" required>
    </div>

    <div class="form-group">
        <label for="doctor_miac_pass">Пароль (из ЕМИАС)</label>
        <input type="password" class="form-control form-control-lg" name="doctor_miac_pass" id="doctor_miac_pass" placeholder="Пароль" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block" id="button_auth">Войти</button>
    </div>

</form>

<?php
include ( "engine/html/modals/authMessageBox.php" );
?>

<script defer src="/engine/js/auth.js?<?=rand(0,999999);?>" type="text/javascript"></script>