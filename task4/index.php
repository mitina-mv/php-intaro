<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма</title>
</head>
<body>
    <form action="./admin/api/form" method="post">
        <div class="form-field">
            <label for="field-email">Email</label>
            <input type="email" name="email" id="field-email">
        </div>

        <div class="form-field">
            <label for="field-name">ФИО</label>
            <input type="text" name="uname" id="field-name">
        </div>

        <div class="form-field">
            <label for="field-phone">Телефон</label>
            <input type="text" name="phone" id="field-phone">
        </div>

        <div class="form-field">
            <label for="field-comment">Комментарий</label>
            <textarea name="comment" id="field-comment"></textarea>
        </div>

        <button type="submit">Отправить</button>
    </form>
</body>
</html>