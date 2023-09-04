<?php
// 送られてきた日付の漁獲予測値を取得するAPI
  ini_set('display_errors', "On");
  include "./../db_config.php";

  # 予測した漁獲量のリスト
  $fish_catch_list = [
    $_POST["today"],
    $_POST["tomorrow"],
    $_POST["two_days_later"],
    $_POST["three_days_later"],
    $_POST["four_days_later"],
    $_POST["five_days_later"],
    $_POST["six_days_later"]
    // 100,
    // 200,
    // 300,
    // 400,
    // 500,
    // 600,
    // 700
  ];
  $today = date("Y-m-d");
  # 1週間分の日付
  $days_list = [
    $today,
    date("Y-m-d",strtotime("+1 day")),
    date("Y-m-d",strtotime("+2 day")),
    date("Y-m-d",strtotime("+3 day")),
    date("Y-m-d",strtotime("+4 day")),
    date("Y-m-d",strtotime("+5 day")),
    date("Y-m-d",strtotime("+6 day")),
  ];
  // $user_id = $_POST["user_id"];
  $user_id = 2;

  try 
  {
    //connect
    $db= new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //eventテーブル一覧を取得

    for ($i=0; $i<count($fish_catch_list); $i++){
      echo "INSERT INTO fish_catch_prediction(`date`, `user_id`, `fish_catch`) VALUES ('{$days_list[$i]}',{$user_id},{$fish_catch_list[$i]})<br>";
      $db->exec("INSERT INTO fish_catch_prediction(`date`, `user_id`, `fish_catch`) VALUES ('{$days_list[$i]}',{$user_id},{$fish_catch_list[$i]})");
    }
    // 7日以上前のデータを削除
    // $db->exec("DELETE FROM fish_catch_prediction WHERE date<'$today'");

    $db = null;
  }
  catch (PDOException $e)
  {
    echo $e->getMessage();
    exit;
  }
?>