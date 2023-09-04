<?php
// DBからgpsの値を取得するAPI

  include "./../db_config.php";


  // $start_date = $_POST['start_date'];
  // $finish_date = $_POST['finish_date'];
  // $date = date("Y-m-d", strtotime("-1 day"));

  if(!isset($_POST["date"])){
    $date = date("Y-m-d");
  }
  else{
    $date = $_POST["date"];
  }

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //eventテーブル一覧を取得
    $stmt = $db->query("SELECT lat,lng,date FROM gps_data WHERE date>='$date 00:00:00' and date<='$date 23:59:59' ORDER BY date ASC");
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