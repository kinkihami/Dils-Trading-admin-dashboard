<?php
require_once '../database.php';

class Functions extends Database
{

  protected $tableName = 'order_details';

  /**
   * function is used to get records
   * @param int $stmt
   * @param int @limit
   * @return array $results
   */

  public function getRows($start = 0, $limit = 15,$id)
  {
    $sql = "SELECT d.owner, d.shopname, d.phonenumber, d.address, p.productname, p.image, pv.price, pv.size, o.id, o.orderdate, o.status, od.quantity FROM {$this->tableName} as od INNER JOIN products as p ON p.id=od.productid INNER JOIN orders as o ON o.id=od.orderid INNER JOIN dealers as d ON d.username=o.username INNER JOIN variants as pv ON pv.id=od.variantid WHERE od.orderid = ? LIMIT {$start},{$limit}";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      $results = [];
    }
    return $results;
  }


  public function update($id, $status)
  {
      $sql = "UPDATE orders SET status=:status WHERE id=:id";
      $stmt = $this->conn->prepare($sql);
      try {
          $this->conn->beginTransaction();
          $stmt->execute(['id' => $id, 'status' => $status]);
          $this->conn->commit();
      } catch (PDOException $e) {
          $this->conn->rollback();
          $response = [
              'success' => false,
              'message' => "Error: " . $e->getMessage(),
          ];
          echo json_encode($response);
          exit();
      }
  }


  //get fields from table
  public function getRow($field, $value)
  {

    $sql = "SELECT * FROM {$this->tableName}  WHERE {$field}=:{$field}";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([":{$field}" => $value]);
    if ($stmt->rowCount() > 0) {
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
      $result = [];
    }

    return $result;
  }



  public function getCount($id)
    {
        $sql = "SELECT count(*) as pcount FROM {$this->tableName} WHERE orderid=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pcount'];
    }
}



?>