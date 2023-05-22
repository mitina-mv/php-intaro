<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');
?>
<form action="/auth/user" method="post" class='card'>
    <div class="errors">
        <?= isset($_GET['error']) ? $_GET['error'] : ''?>
    </div>
    
    <div class="field field-text">
        <label for="field-email">Email</label>
        <input type="email" name="email" id="field-email">
    </div>

    <div class="field field-text">
        <label for="field-password">Пароль</label>
        <input type="text" name="password" id="field-password">
    </div>

    <input type="submit" value="Отправить" class='btn btn_primary'>
</form>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');
