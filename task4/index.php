<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="message-form"></div>
    
    <form action="./admin/api/form" method="post">

        <div class="form-field">
            <label for="field-email">Email</label>
            <input type="email" name="email" id="field-email" placeholder="mail@mail.ru">
            <span class="error-message">Введите почту! Мы очень хотим Вам ответить!<br /> Шаблон: mail@mail.ru</span>
        </div>

        <div class="form-field">
            <label for="field-name">ФИО</label>
            <input type="text" name="uname" id="field-name" placeholder="Фамилия Имя Отчество">
            <span class="error-message">Не оставляйте пустым! Хотим знать как к Вам обращаться ) <br /> Шаблон: ФИО</span>
        </div>

        <div class="form-field">
            <label for="field-phone">Телефон</label>
            <input type="text" name="phone" id="field-phone" placeholder="+7 999 123-45-67">
            <span class="error-message">Ошибка! Это поле обязательное. <br /> Шаблон: +7 999 123-45-67</span>
        </div>

        <div class="form-field">
            <label for="field-comment">Комментарий</label>
            <textarea name="comment" id="field-comment" placeholder="Не забудьте добавить комментарий..."></textarea>
            <span class="error-message">Ошибка! Обращение не может быть пустым</span>
        </div>

        <button type="submit">Отправить</button>
    </form>

    <script src="./main.js"></script>
    <script src="./script.js"></script>
</body>
</html>