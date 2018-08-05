<?php
/**
 * User: Script
 * Date: 04.08.2018
 * Time: 20:51
 */

namespace Geega\SimpleOrm;

/**
 * Class Model
 * @package Geega\SimpleOrm
 */
class Model
{
    /**
     * @var string
     */
    public $table;

    /**
     * @var \PDO
     */
    public $connect;

    /**
     * Model constructor.
     */
    public function __construct(Config $config)
    {
        $host = $config->getHost();
        $database = $config->getDatabase();
        $user = $config->getUser();
        $password = $config->getPassword();

        $connect = "mysql:dbname={$database};host={$host}";

        $this->connect = new \PDO($connect, $user, $password);
        $this->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connect->exec("SET CHARACTER SET utf8");
    }


    /**
     *
     * @return mixed
     */
    static public function all()
    {
        $model = new static;
        $result = $model->execute('SELECT * FROM '.$model->table);
        return $result;
    }

    /**
     * Find record by pk
     * @param $id
     * @return array|mixed
     */
    static public function find($id)
    {
        $model = new static;
        $result = $model->execute('SELECT * FROM '.$model->table.' WHERE '.$model->key.' = ?', [$id]);
        if (is_array($result)) {
            $result = current($result);
        }
        return $result;
    }

    /**
     * @param $data
     * @return mixed
     */
    static public function findBy($data)
    {
        $model = new static;
        $sql = 'SELECT * FROM '.$model->table.' WHERE ';
        $values = [];
        foreach ($data as $key => $value) {
            $sql .= $key.' = ? AND ';
            $values[] = $value;
        }
        $sql = trim($sql, ' AND ');
        $result = $model->execute($sql, $values);
        return $result;
    }


    /**
     * Find by query
     * @param $sqlQuery
     * @param array $params
     * @param int $fetchMode
     * @return mixed
     */
    static public function findByQuery($sqlQuery, array $params, $fetchMode = \PDO::FETCH_OBJ)
    {
        $model = new static;
        if($model->table) {
            $sqlQuery = str_replace(':table', $model->table, $sqlQuery);
        }

        $result = $model->execute($sqlQuery);
        return $result;
    }

    /**
     * ModelName::findAll()
     * @param array|null $whereParams
     * @return static|array
     */
    static public function findAll(array $whereParams = null)
    {
        $model = new static;

        if(!$whereParams) {
            return $model;
        }

        $sqlQuery = 'SELECT * FROM '.$model->table.' WHERE  1=1 ';
        $placeHolders = [];
        foreach ($whereParams as $param) {
            $key = $keyPlaceholder = $param[0];
            $value = $param[1];
            $sign  = '=';
            if(isset($param[2])) {
                $sign = $param[2];
                if($sign == '>=' || $sign == '>') {
                    $keyPlaceholder .= '_more';
                } elseif($sign == '<=' || $sign == '<') {
                    $keyPlaceholder .= '_less';
                }
            }

            $sqlQuery .= " AND `{$key}` {$sign} :{$keyPlaceholder} ";
            $placeHolders[$keyPlaceholder] = $value;
        }

        $statement = $model->connect->prepare($sqlQuery);
        foreach ($placeHolders as $column => $value) {
            $statement->bindValue(':'.$column, $value);
        }
        try {
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch(\PDOExecption $e) {
            return "Error" . $e->getMessage();
        }
    }

    /**
     * Add  data to table
     * @param $data
     * @return string
     */
    static public function add($data)
    {
        try {
            $model = new static;
            $attributes = array_flip($model->attributes);
            $data = array_filter($data, function ($data) use ($attributes) {
                return isset($attributes[$data]);
            }, ARRAY_FILTER_USE_KEY);
            $sql = 'INSERT INTO '.$model->table.' (';
            foreach ($data as $column => $value) {
                $sql .= '`'.$column.'`, ';
            }
            $sql = trim($sql, ', ');
            $sql .= ') VALUES (';
            foreach ($data as $column => $value) {
                $sql .= ':'.$column.', ';
            }
            $sql = trim($sql, ', ');
            $sql .= ')';
            $statement = $model->connect->prepare($sql);
            foreach ($data as $column => $value) {
                $statement->bindValue(':'.$column, $value);
            }
            try {
                $statement->execute();
                return $model->connect->lastInsertId();
            } catch(\PDOExecption $e) {
                $model->connect->rollback();
                return "Error" . $e->getMessage();
            }
        } catch( \PDOExecption $e ) {
            return "Error" . $e->getMessage();
        }
    }

    /**
     * @param $sql
     * @param array $params
     * @return array
     */
    public function execute($sql, $params = []) {
        $attributes = array_flip($this->attributes);
        $result = [];
        $statement = $this->connect->prepare($sql);
        $statement->execute($params);
        foreach($statement as $row) {
            $result[] = array_filter($row, function ($data) use ($attributes) {
                return isset($attributes[$data]);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $result;
    }
}