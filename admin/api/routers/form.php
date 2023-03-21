<?php

// Роутинг, основная функция
function route($data) {
    // POST 
    if ($data['method'] === 'POST' && count($data['urlData']) === 1) {      
        echo json_encode(addRecord($data['formData']));
    }
}

function addRecord($formData)
{
    try {
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    // TODO проверка на существование обращения по email менее чем через час
    try {
        $query = "SELECT record_id, create_at
            FROM form 
            WHERE email=:email
            AND (NOW() - create_at) <= '01:00:00'";

        $data = $pdo->prepare($query);
        $data->execute([
            'email' => $formData['email']
        ]);

        $row = $data->fetchAll();

        if(count($row) != 0)
        {
            $newDate = new DateTime($row[0]->create_at);
            $date = $newDate->modify('+1 hour')->format('H:i:s d.m.Y');

            \Helpers\query\throwHttpError('query error', "Сейчас мы не можем принять ваше сообщение. Следующее сообщение с email {$formData['email']} можно написать в {$date}", '403');
            exit;
        }
        
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }

    try {
        $query = 'INSERT INTO form (email, uname, phone, comment) 
        VALUES (:email, :uname, :phone, :comment)';

        $dateCreate = new DateTime();
        
        $data = $pdo->prepare($query);
        $data->execute([
            'email' => $formData['email'], 
            'uname' => $formData['uname'], 
            'phone' => $formData['phone'], 
            'comment' => htmlspecialchars($formData['comment']) 
        ]);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }

    if($newId = (int)$pdo->lastInsertId()){
        $timeAnswer = $dateCreate->modify('+1 hour 30 minutes');
        $nameArr = explode(' ', $formData['uname']);

        // разбиваем по 70 символов, чтобы ничего не умерло
        $comment = wordwrap(
            htmlspecialchars($formData['comment']), 
            70, 
            "\r\n"
        );

        // отправка формы менеджеру по email из константы
        mail(MANAGER_EMAIL, 
            'Обращение из формы обратной связи', 
            "Тут заявочка прилетета от \r\n {$formData['uname']} <{$formData['email']}>.\r\n Текст обращения:\r\n {$comment}\r\n. Ответьте до {$timeAnswer->format('H:i:s d.m.Y')}"
        );
        return [
            'message' => <<< EOT
            Оставлено сообщение из формы обратной связи.<br/>
            Имя: {$nameArr[1]}<br/>
            Фамилия: {$nameArr[0]}<br/>
            Отчество: {$nameArr[2]}<br/>
            E-mail: {$formData['email']}<br/>
            Телефон: {$formData['phone']}<br/>
            С Вами свяжутся после {$timeAnswer->format('H:i:s d.m.Y')}
            EOT,
            'id' => $newId
        ];
    } else {
        \Helpers\query\throwHttpError('query error', 'Ошибка добавления');
        exit;
    }
}