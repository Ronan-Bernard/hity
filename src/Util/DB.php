<?php

namespace Hity\Util;

/**
 * Handles DB connection
 * basic, only to remain framework-agnostic
 */

use M1\Env\Parser;

class DB
{
    private static $_instance = null;
    private static $_pdo = null;
    private $envFileFullPath = '.env';

    private $dbConnection;
    private $dbHost;
    private $dbPort;
    private $dbDatabase;
    private $dbUsername;
    private $dbPassword;

    private function __construct()
    {
        $this->readEnvFile();
        $this->openDbConnection();
    }

    private function readEnvFile()
    {
        $env = new Parser(file_get_contents($this->envFileFullPath));
        $arr = $env->getContent();
        $this->dbConnection = $arr['DB_CONNECTION'];
        $this->dbHost = $arr['DB_HOST'];
        $this->dbPort = $arr['DB_PORT'];
        $this->dbDatabase = $arr['DB_DATABASE'];
        $this->dbUsername = $arr['DB_USERNAME'];
        $this->dbPassword = $arr['DB_PASSWORD'];
    }

    private function openDbConnection()
    {
        try {
            $dsn = $this->dbConnection
                . ':host=' . $this->dbHost
                . ';dbname=' . $this->dbDatabase
            ;
            self::$_pdo = new \PDO($dsn, $this->dbUsername, $this->dbPassword);
        } catch (\PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public static function getInstance() : \PDO
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new DB();
        }
        $instance = self::$_instance;
        return $instance::$_pdo;
    }
}
