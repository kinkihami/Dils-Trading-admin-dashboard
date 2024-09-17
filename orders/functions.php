<?php
require_once '../database.php';

class Functions extends Database
{

  protected $tableName = 'orders';

  /**
   * function is used to get records
   * @param int $stmt
   * @param int @limit
   * @return array $results
   */

  public function getRows($start = 0, $limit = 15, $status = 4)
  {
    if($status==4){
      $sql = "SELECT orders.id, orders.orderdate, orders.totalamount, orders.deliverydate, orders.status, orders.username, dealers.shopname, dealers.phonenumber FROM {$this->tableName} INNER JOIN dealers ON orders.username=dealers.username ORDER BY id DESC LIMIT {$start},{$limit}";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
    }else{
      $sql = "SELECT orders.id, orders.orderdate, orders.totalamount, orders.deliverydate, orders.status, orders.username, dealers.shopname, dealers.phonenumber FROM {$this->tableName} INNER JOIN dealers ON orders.username=dealers.username WHERE status=:status ORDER BY id DESC LIMIT {$start},{$limit}";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([':status' => $status]);
    }


    if ($stmt->rowCount() > 0) {
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {

      $results = [];
    }
    return $results;
  }


  public function getCount($status=3)
    {
        if($status==3){
          $sql = "SELECT count(*) as pcount FROM {$this->tableName}";
          $stmt = $this->conn->prepare($sql);
          $stmt->execute();
        }else{
          $sql = "SELECT count(*) as pcount FROM {$this->tableName} WHERE status=:status";
          $stmt = $this->conn->prepare($sql);
          $stmt->execute([':status' => $status]);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pcount'];
    }
}



?>