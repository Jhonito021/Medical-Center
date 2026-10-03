<?php
class Database {
    private static $instance = null;
    private $connetion;

    private $host = 'localhost';
    private $dbname = 'medical_fictive';
    private $username = 'root';
    private $password = '';

    private function _construct () {
        try {
            $this->connection = new PDO (
                "mysql:host={$this->host};dname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die ("Erreur de connexion : " . $e->getMessage());
        }
    }

    public static function getInstance () {
        if (self::$instance === null) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function getConnection () {
        return $this->connection;
    }
}