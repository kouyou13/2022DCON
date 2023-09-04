<?php
// 最新の天気を取得するAPI

  include "./db_config.php";

  $date_now = date("Y-m-d H:i:s");
  $date_30_min_ago = date("Y-m-d H:i:s", strtotime("-30 minute")); // 一応30分前まで
  

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //eventテーブル一覧を取得
    $stmt = $db->query("SELECT * FROM weather_data WHERE datetime>='$date_30_min_ago' and datetime<='$date_now' ORDER BY datetime ASC");
    $temperature = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $db = null;
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
    exit;
  }
  header('Content-Type: application/json'); // apiにしますよーってやつ
  $json = json_encode($temperature);
  print ($json);
?>