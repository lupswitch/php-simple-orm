<?php
/**
 * Created by PhpStorm.
 * User: chastener
 * Date: 16.06.2019
 * Time: 16:52
 */

namespace Geega\SimpleOrm\Drivers;


use Geega\SimpleOrm\Config;

class PdoFactory
{

    const MySQL = 'MySQL';


    private $db = self::MySQL;

    /**
     * @var PDODriver
     */
    private $driver;

    private $config = null;


    /**
     * PdoFactory constructor.
     * @param $db
     * @param Config $config
     */
    public function __construct($db)
    {
        $this->db = $db;


    }


    /**
     * @param $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function getInstance(Config $config)
    {
        $this->setConfig($config);
        $this->createInstance();
        return $this->driver;
    }

    private function createInstance()
    {
        $this->driver = new $this->db($this->config);
    }
}