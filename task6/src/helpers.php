<?php

use App\System\App;

function app()
{
    return App::getInctance();
}

function loadfile($field, $user_dir = false, $allow = array('png', 'jpg', 'jpeg'))
{    
    // Запрещенные расширения файлов.
    $deny = array(
        'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
        'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
        'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi'
    );
    
    // Директория куда будут загружаться файлы.
    if(!$user_dir)
        $path = $_SERVER['DOCUMENT_ROOT'] . "/upload/userfiles/";
    else
        $path = $_SERVER['DOCUMENT_ROOT'] . "/upload/{$user_dir}/";
    
    if (isset($_FILES[$field])) {
        // Проверим директорию для загрузки.
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    
        // Преобразуем массив $_FILES в удобный вид для перебора в foreach.
        $files = array();
        $diff = count($_FILES[$field]) - count($_FILES[$field], COUNT_RECURSIVE);
        if ($diff == 0) {
            $files = array($_FILES[$field]);
        } else {
            foreach($_FILES[$field] as $k => $l) {
                foreach($l as $i => $v) {
                    $files[$i][$k] = $v;
                }
            }		
        }	

        $topath = [];
        
        foreach ($files as $file) {
            $error = $success = '';
    
            // Проверим на ошибки загрузки.
            if (!empty($file['error']) || empty($file['tmp_name'])) {
                switch (@$file['error']) {
                    case 1:
                    case 2: $error = 'Превышен размер загружаемого файла.'; break;
                    case 3: $error = 'Файл был получен только частично.'; break;
                    case 4: $error = 'Файл не был загружен.'; break;
                    case 6: $error = 'Файл не загружен - отсутствует временная директория.'; break;
                    case 7: $error = 'Не удалось записать файл на диск.'; break;
                    case 8: $error = 'PHP-расширение остановило загрузку файла.'; break;
                    case 9: $error = 'Файл не был загружен - директория не существует.'; break;
                    case 10: $error = 'Превышен максимально допустимый размер файла.'; break;
                    case 11: $error = 'Данный тип файла запрещен.'; break;
                    case 12: $error = 'Ошибка при копировании файла.'; break;
                    default: $error = 'Файл не был загружен - неизвестная ошибка.'; break;
                }
            } elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
                $error = 'Не удалось загрузить файл.';
            } else {
                $name = translitFileName($file['name']);
                $parts = pathinfo($name);
    
                if (empty($name) || empty($parts['extension'])) {
                    $error = 'Недопустимое тип файла';
                } elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
                    $error = 'Недопустимый тип файла';
                } elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
                    $error = 'Недопустимый тип файла';
                } else {
                    $i = 0;
                    $prefix = "__" . date("Y_m_d_H_i_s");

                    $name = $parts['filename'] . $prefix . '.' . $parts['extension'];

                    $topath[] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path . $name);
    
                    if (move_uploaded_file($file['tmp_name'], $path . $name)) {
                        $success = 'Файл «' . $name . '» успешно загружен.';
                    } else {
                        $error = 'Не удалось загрузить файл.';
                    }
                }
            }
            
            // Выводим сообщение о результате загрузки.
            if (!empty($error)) {
                throw new \Exception($error);
            }
        }

        return $topath;
    }
}

function translitFileName($str){
    $pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
    $name = mb_eregi_replace($pattern, '-',$str);
    $name = mb_ereg_replace('[-]+', '-', $name);

    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',    'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',    'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',    'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',    'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',  'ь' => '',    'ы' => 'y',   'ъ' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',   '(' => '',   ')' => '',
    
        'А' => 'A',   'Б' => 'B',   'В' => 'V',    'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',    'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',    'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',    'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',  'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );

    return strtr($name, $converter);
}