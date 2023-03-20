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
        $query = 'INSERT INTO "form-handling" (email, uname, phone, comment, create_at) 
        VALUES (:email, :uname, :phone, :comment, :create_at)';

        $dateCreate = date('Y/m/d H:i');
        
        $data = $pdo->prepare($query);
        $data->execute([
            'email' => $formData['email'], 
            'uname' => $formData['uname'], 
            'phone' => $formData['phone'], 
            'comment' => $formData['comment'], 
            'create_at' => $dateCreate, 
        ]);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }

    if($newId = (int)$pdo->lastInsertId()){
        $timeAnswer = $dateCreate ;
        $nameArr = explode(' ', $formData['uname']);

        return [
            'message' => <<< EOT
            Оставлено сообщение из формы обратной связи.
            Имя: {$nameArr[1]}
            Фамилия: {$nameArr[0]}
            Отчество: {$nameArr[2]}
            E-mail: {$formData['uname']}
            Телефон: {$formData['phone']}
            С Вами свяжутся после $timeAnswer
            EOT,
        ];
    } else {
        \Helpers\query\throwHttpError('query error', 'Ошибка добавления');
        exit;
    }
}