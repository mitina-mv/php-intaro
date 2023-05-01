<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');
?>
<form action="/registration/user" method="post">
    <div class="errors">
        <?= isset($_GET['error']) ? $_GET['error'] : ''?>
    </div>

    <div class="field">
        <label for="field-firstname">Имя</label>
        <input type="text" name="firstname" id="field-firstname">
    </div>

    <div class="field">
        <label for="field-lastname">Фамилия</label>
        <input type="text" name="lastname" id="field-lastname">
    </div>

    <div class="field">
        <label for="field-patronymic">Отчество</label>
        <input type="text" name="patronymic" id="field-patronymic">
    </div>
    
    <div class="field">
        <label for="field-email">Email</label>
        <input type="email" name="email" id="field-email">
    </div>

    <div class="field">
        <label for="field-password">Пароль</label>
        <input type="password" name="password" id="field-password">
    </div>

    <input type="submit" value="Отправить">
</form>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');
