## ЛР 2. Форма обратной связи
Организовать на странице форму обратной связи
Форма должна содержать 4 поля: ФИО, почта, телефон, комментарий.
После отправки скрывать форму и выводить сообщение об отправке формы.
Отправка формы должна происходить без перезагрузки страницы (аяксом)
Поля валидировать средствами JS (на пустоту и формат почты и телефона). Если валидация не прошла, подсвечивать поля с ошибками красным
Если заявка успешно оформлена, то сохранить ее в БД вместе с временем создания и отправить письмо с информацией из заявки на email менеджера.
Запретить создание повторной заявки на тот же email в течение часа. Вывести соответствующее сообщение и написать время, через которое можно создать повторную заявку.

