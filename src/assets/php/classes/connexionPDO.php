<?php
class Connexion extends PDO
{
    private $servername = "localhost";
    private $dbname = "bachproject";
    private $user = "root";
    private $password = "root";

    const FETCH = 1;
    const FETCH_COLUMN = 2;
    const FETCH_ALL = 3;

    public function __construct()
    {
        try {
            parent::__construct("mysql:host=$this->servername; dbname=$this->dbname", $this->user, $this->password, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function query($q, $fetch, $params = null)
    {
        $result = $this->prepare($q);
        $result->execute($params);

        if ($fetch == self::FETCH) return $result->fetch();
        else if ($fetch == self::FETCH_COLUMN) return $result->fetchColumn();
        else if ($fetch == self::FETCH_ALL) return $result->fetchAll();
    }
}
