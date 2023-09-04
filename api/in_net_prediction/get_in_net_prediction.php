<?php
// 送られてきた日付の漁獲予測値を取得するAPI
  ini_set('display_errors', "On");
  include "./../db_config.php";


  // $user_id = $_POST["user_id"];
  $user_id = 2;
  if(!isset($_POST["month"])){
    $today_date = date("Y-m-d");
  }
  else{
    $month = $_POST["month"];
    $date = $_POST["date"];
    $today_date = "2022-$month-$date";
  }

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(!isset($_POST["hour"])){
      $stmt = $db->query("SELECT * FROM in_net_prediction WHERE datetime>'{$today_date} 00:00:00' and datetime<'{$today_date} 23:59:59' ORDER BY datetime DESC");
    }
    else{
      $hour = $_POST["hour"];
      $stmt = $db->query("SELECT * FROM in_net_prediction WHERE datetime>'{$today_date} 00:00:00' and datetime<'{$today_date} {$hour}:59:59' ORDER BY datetime DESC");
    }
    $catch_forecast = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $db = null;
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
    exit;
  }
  header('Content-Type: application/json'); // apiにしますよーってやつ
  $json = json_encode($catch_forecast[0]); # 最新の値のみ
  print ($json);
?>