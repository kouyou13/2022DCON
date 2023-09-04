<?php
  include "./db_config.php";
  ini_set('display_errors', "On");
  $user_id = $_POST["user_id"];
  // $user_id = 1;

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //eventテーブル一覧を取得
    $stmt = $db->query("SELECT * FROM devices INNER JOIN users WHERE users.user_id=$user_id and devices.user_id=users.id");
    $device = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $db = null;
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
    exit;
  }
  header('Content-Type: application/json'); // apiにしますよーってやつ
  $json = json_encode($device);
  print ($json);
?>