<?php
/**
 * User: Script
 * Date: 04.08.2018
 * Time: 20:59
 */

namespace Geega\SimpleOrm;


class Config
{
    private $host;

    private $database;

    private $user;

    private $password;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $host =getenv('PDO_HOST');
        if($host) {
            $this->setHost(trim($host));
        }

        $database = getenv('PDO_DATABASE');
        if($database) {
            $this->setDatabase(trim($database));
        }
        $user = getenv('PDO_USER');
        if($user) {
            $this->setUser($user);
        }

        $password = getenv('PDO_PASSWORD');
        if($password) {
            $this->setPassword($password);
        }
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }


    public function setDatabase($dbName)
    {
        $this->database = $dbName;
        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }
}