<?php
    class db {
        private $dbHost = 'localhost';
        private $dbUser = 'root';
        private $dbPass = '12345678';
        private $dbName = 'mydb';

        /**
         * Connect
         */
        public function connectionDB() {
            $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
            $dbConnection = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $dbConnection->exec("set names utf8");

            return $dbConnection;
        }
    }