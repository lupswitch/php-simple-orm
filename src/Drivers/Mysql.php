<?php
/**
 * Created by PhpStorm.
 * User: chastener
 * Date: 17.06.2019
 * Time: 14:38
 */

namespace Geega\SimpleOrm\Drivers;


class Mysql extends PDODriver
{
    /**
     * @return \PDO
     */
    public function getPdo()
    {
        $host = $this->config->getHost();
        $database = $this->config->getDatabase();
        $user = $this->config->getUser();
        $password = $this->config->getPassword();

        $connect = "mysql:dbname={$database};host={$host}";

        $pdo = new \PDO($connect, $user, $password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET CHARACTER SET utf8");

        return $pdo;
    }

}