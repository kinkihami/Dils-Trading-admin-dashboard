<?php
require_once '../database.php';

class Functions extends Database
{

  public function orderCount()
    {
        $sql = "SELECT count(*) as pcount FROM orders";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pcount'];
    }
  public function pendingorderCount()
    {
        $sql = "SELECT count(*) as pcount FROM orders WHERE status=0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pcount'];
    }
  public function delieveredorderCount()
    {
        $sql = "SELECT count(*) as pcount FROM orders WHERE status=1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pcount'];
    }
  public function dealersCount()
    {
        $sql = "SELECT count(*) as pcount FROM dealers";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pcount'];
    }
}



?>