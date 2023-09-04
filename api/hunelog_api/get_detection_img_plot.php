<?php
  ini_set('display_errors', "On");

  include "./../db_config.php";

  $today_start_unix = $_POST["start_unix"];
  $today_finish_unix = $_POST["finish_unix"];
  $device_id = $_POST["device_id"];

  // $today_start_unix = 1648047600;
  // $today_finish_unix = 1648133999;
  // $device_id = 3001;
  $start_datetime = date("Y-m-d H:i:s", $today_start_unix);
  $finish_datetime = date("Y-m-d H:i:s", $today_finish_unix);

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("SELECT genre_id,datetime FROM detection_img WHERE datetime>='$start_datetime' and datetime<='$finish_datetime' and device_id=$device_id ORDER BY datetime ASC");
    $detection_img_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $db = null;
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
    exit;
  }
  header('Content-Type: application/json'); // apiにしますよーってやつ
  $json = json_encode($detection_img_list);
  print ($json);
?>