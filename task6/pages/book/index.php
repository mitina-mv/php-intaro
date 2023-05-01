<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/header.php');
?>
<?php if(isset($_SESSION['user'])):?>
<form action="/book/create" method="post">

</form>
<?php else:?>
<div class="error">
    Вы не авторизованы, добавление или редактирование книги недоступно :(
</div>
<?php endif?>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php');