<?php
/**
 * Created by PhpStorm.
 * User: chastener
 * Date: 16.06.2019
 * Time: 17:55
 */

namespace Geega\SimpleOrm\Drivers;


use Geega\SimpleOrm\Config;

abstract class PDODriver
{
    protected $config;


    public function __construct(Config $config)
    {
        $this->config = $config;
    }




    abstract public function getPdo();
}