<?php
class Database
{
    private $dbServer = 'localhost';
    private $dbUser = 'root';
    private $dbPassword = '';
    private $dbName = 'dils_trading';
    protected $conn;
    

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->dbServer}; dbname={$this->dbName}; charset=utf8";
            $options = array(PDO::ATTR_PERSISTENT);
            $this->conn = new PDO($dsn, $this->dbUser, $this->dbPassword, $options);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

    }
    public function getConnection() {

        $this->conn = null;

        try {
            
            $this->conn = new PDO("mysql:host=" . $this->dbServer . ";dbname=" . $this->dbName, $this->dbUser, $this->dbPassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (Exception $exception) {
            echo "Connection failed: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
