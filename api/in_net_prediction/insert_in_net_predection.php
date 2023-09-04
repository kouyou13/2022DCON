<?php
// 送られてきた日付の漁獲予測値を取得するAPI
  ini_set('display_errors', "On");
  include "./../db_config.php";

  # 予測した漁獲量のレベル
  // $in_net_prediction = $_POST["in_net_prediction"];
  // $in_net_prediction = 1;

  $today_datetime = date("Y-m-d H:i:s"); # 今日の日付
  $hour_ago = date("Y-m-d H:i:s",strtotime("-1 hour"));
  $user_id = 2;
  $device_id = 3004;
  $fish_catch_level = 0;

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->query("SELECT id FROM detection_img WHERE datetime>='$hour_ago' and datetime<='$today_datetime' and device_id=$device_id and genre_id = 1 ORDER BY datetime ASC");
    $detection_img_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // print_r(count($detection_img_list));
    if (count($detection_img_list) > 100)
      $fish_catch_level = 3;
    else if (count($detection_img_list) > 50)
      $fish_catch_level = 2;
    else
      $fish_catch_level = 1;
    
    echo "INSERT INTO in_net_prediction(`prediction_level`, `user_id`, `datetime`) VALUES ({$fish_catch_level}, {$user_id}, '{$today_datetime}')<br>";
    $db->exec("INSERT INTO in_net_prediction(`prediction_level`, `user_id`, `datetime`) VALUES ({$fish_catch_level}, {$user_id}, '{$today_datetime}')");
    // 2日以上前のデータを削除
    // $db->exec("DELETE FROM in_net_prediction WHERE datetime<'$two_days_ago'");

    $db = null;
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
    exit;
  }
?>