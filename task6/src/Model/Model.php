<?php

namespace App\Model;

use PDO;
use Exception;

class Model
{
    /**
     * @var PDO connection
     */
    protected $connection;
    /**
     * @var string table name
     */
    protected $table;
    /**
     * @var string column name primary key
     */
    protected $primaryKey = 'id';
    /**
     * @var string column type primary key
     */
    protected $keyType = 'int';
    /**
     * @var array columns allowed to change
     */
    protected $fillable = [];


    /**
     * создает подключение БД
     */
    public function __construct()
    {
        try 
        {
            $this->connection = new PDO("pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};user={$_ENV['DB_USER']};password={$_ENV['DB_PASS']};dbname={$_ENV['DB_NAME']}");

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());   
        }
    }

    /**
     * функция создает запись в таблице
     * возвращает id элемента в случае успеха
     * Exception в случае неудачи
     */
    public function create($values)
    {
        $namesField = [];

        foreach($values as $key => $value)
        {
            if(!in_array($key, $this->fillable))
                throw new Exception("Недопустимый параметр {$key}. Проверьте fillable");

            $namesField[] = ":{$key}";
        }

        if(!$this->table)
            throw new Exception("Не указано имя таблицы");

        $fillableFields = implode(", ", array_keys($values));
        $namesFields = implode(", ", $namesField);

        $query = <<< QUERY
            INSERT INTO {$this->table} {$fillableFields}
            VALUES ($namesFields)
        QUERY;

        $data = $this->connection->prepare($query);
        $data->execute($values);

        return (int)$this->connection->lastInsertId();
    }

    /**
     * select без условий
     */
    public function select($fields = "*")
    {
        if(!$this->table)
            throw new Exception("Не указано имя таблицы");

        if(is_array($fields))
            $str = implode(", ", $fields);
        else 
            $str = $fields;

        $query = <<< QUERY
            SELECT $str FROM {$this->table}
        QUERY;
        $data = $this->connection->prepare($query);
        $data->execute();

        return $data->fetchAll();
    }

    /**
     * select с where
     * TODO where оптимизировать под подготавливаемые запросы
     */
    public function where($conditions = [], $fields = "*")
    {
        if(!$this->table)
            throw new Exception("Не указано имя таблицы");

        if(is_array($fields))
            $str = implode(", ", $fields);
        else 
            $str = $fields;

        $strConditions = implode(" AND ", $conditions);

        $query = <<< QUERY
            SELECT $str FROM {$this->table} WHERE $strConditions
        QUERY;
        $data = $this->connection->prepare($query);
        $data->execute();

        return $data->fetchAll();
    }

    /**
     * удаляет записи из таблицы по условиям
     * @return true в случае успеха
     */
    public function delete($where = [])
    {
        if(!$this->table)
            throw new Exception("Не указано имя таблицы");

        $strConditions = implode(" AND ", $where);

        $query = <<< QUERY
            DELETE FROM {$this->table} WHERE $strConditions
        QUERY;

        $data = $this->connection->prepare($query);
        $data->execute();

        return true;
    }

    /**
     * обновляет данные по условию
     */
    public function update($values, $where = [])
    {
        if(!$this->table)
            throw new Exception("Не указано имя таблицы");

        $setValues = [];
        foreach($values as $key => $value)
        {
            if(!in_array($key, $this->fillable))
                throw new Exception("Недопустимый параметр {$key}. Проверьте fillable");

            $setValues[] = "{$key} = {$value}";
        }

        $strSet = implode(", ", $setValues);
        $strConditions = implode(" AND ", $where);

        $query = <<< QUERY
            UPDATE {$this->table} SET $strSet WHERE $strConditions
        QUERY;

        $data = $this->connection->prepare($query);
        $data->execute();
         
        return true;
    }

    /**
     * проверяет существование записи по условию
     * @return bool 
     */
    public function isExists($where)
    {
        if(!$this->table)
            throw new Exception("Не указано имя таблицы");

        return count($this->where($where, [$this->primaryKey])) === 1;
    }

}
