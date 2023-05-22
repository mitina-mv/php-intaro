<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск ближайшего метро</title>
</head>
<body>    
    <form action="./getResult.php" method="post">
        <div class="field">
            <label for="toun_field">Город</label>
            <select name="toun" id="toun_field">
                <option value="Москва" selected>Москва</option>
                <option value="Санкт-Петербург">Санкт-Петербург</option>
                <option value="Нижний Новгород">Нижний Новгород</option>
                <option value="Новосибирск">Новосибирск</option>
                <option value="Самара">Самара</option>
                <option value="Казань">Казань</option>
                <option value="Екатеринбург">Екатеринбург</option>
            </select>
        </div>

        <div class="field">
            <label for="address_field">Введите свой адрес (без города)</label>
            <input type="text" name="address" id="address_field">
        </div>

        <input type="submit" value="Найти метро!">

        <div class="message-block"></div>
    </form>

    <script src="./script.js"></script>
    <script src="./../task4/main.js"></script>
</body>
</html>
