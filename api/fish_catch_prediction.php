<?php
// 送られてきた日付の漁獲予測値を取得するAPI
  ini_set('display_errors', "On");
  include "./db_config.php";


  $start_date = $_POST['start_date'];
  $finish_date = $_POST['finish_date'];
  $user_id = $_POST["user_id"];
  // $start_date = "2022-04-08";
  // $finish_date = "2022-04-14";
  // $user_id = 2;

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //eventテーブル一覧を取得
    $stmt = $db->query("SELECT * FROM fish_catch_prediction INNER JOIN users WHERE users.user_id=$user_id and fish_catch_prediction.user_id=users.id and fish_catch_prediction.date>='$start_date' and fish_catch_prediction.date<='$finish_date' ORDER BY fish_catch_prediction.date ASC");
    $catch_forecast = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $db = null;
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
    exit;
  }
  header('Content-Type: application/json'); // apiにしますよーってやつ
  $json = json_encode($catch_forecast);
  print ($json);
?>