<?php
/**
 * User: Script
 * Date: 04.08.2018
 * Time: 20:59
 */

namespace Geega\SimpleOrm;

/**
 * Class Config
 * @package Geega\SimpleOrm
 */
class Config
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Main init method
     */
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

    /**
     * @param $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }


    /**
     * @param $dbName
     * @return $this
     */
    public function setDatabase($dbName)
    {
        $this->database = $dbName;
        return $this;
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
}