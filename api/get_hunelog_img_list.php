<?php
  ini_set('display_errors', "On");

  function selectImg($img_list, $img_dir, $ago_time){
    $ten_min_ago_unix = time() - $ago_time * 60;
    $ten_min_list = []; // 最新の10分間の画像
    for($i=0; $i<count($img_list); $i++){
      $file_unixtime = str_replace($img_dir, "", $img_list[$i]);
      $file_unixtime = str_replace(".jpeg", "", $file_unixtime);
      // echo $file_unixtime;
      if ($file_unixtime > $ten_min_ago_unix)
        $ten_min_list[] = $img_list[$i];
    }
    // もし10分間の画像がなければ
    if (count($ten_min_list) == 0){
      $ago_time += 5;
      $ten_min_list = selectImg($img_list, $img_dir, $ago_time);
    }
    return $ten_min_list;
  }


  function selectImg_temp($img_list, $img_dir, $ago_time, $select_unix){
    $ten_min_ago_unix = $select_unix - $ago_time * 60;
    $hour_later_unix = $select_unix;
    $ten_min_list = []; // 最新の10分間の画像
    for($i=0; $i<count($img_list); $i++){
      $file_unixtime = str_replace($img_dir, "", $img_list[$i]);
      $file_unixtime = str_replace(".jpeg", "", $file_unixtime);
      // echo $file_unixtime;
      if ($file_unixtime > $ten_min_ago_unix and $file_unixtime < $hour_later_unix)
        $ten_min_list[] = $img_list[$i];
    }
    // もし10分間の画像がなければ
    if (count($ten_min_list) == 0 and $ago_time < 50){
      
      $ago_time += 5;
      $ten_min_list = selectImg_temp($img_list, $img_dir, $ago_time, $ten_min_ago_unix);
    }
    return $ten_min_list;
  }
  

  // 取得したいデバイスのid
  $water_device_id = $_POST["water_device_id"];
  $marine_device_id = $_POST["marine_device_id"];
  // $water_device_id = 3001;
  // $marine_device_id = 3002;
  // $water_device_id = 3004;
  // $marine_device_id = 3113;
  // $select_unix = 1647739008;
  // $today = '2022-03-20';
  $json = array(["device_id" => $water_device_id, "data" => []], ["device_id" => $marine_device_id, "data" => []]);
  $device_id = [$water_device_id, $marine_device_id];# 船ログの水中カメラ，水上カメラ
  
  // 日付が送信されなければ今日
  if(!isset($_POST["select_unix"])){
    $today = date("Y-m-d");
    for ($i=0; $i<count($device_id); $i++){
      if ($device_id[$i] != -1){
        $img_dir = "/home/users/1/littlestar.jp-ezaki-lab/web/2022DCON/images/hunelog_imgs/{$device_id[$i]}/{$today}/";
        $img_list = glob($img_dir . "*.jpeg");
        if ($device_id[$i] == 3113)
          $ten_min_list = $img_list;
        else
          $ten_min_list = selectImg($img_list, $img_dir, 10); //10分 or 15分間の画像のリスト
        $json[$i]["data"] = $ten_min_list;
      }
    }
  }
  // 取材用
  else{
    $select_unix = $_POST["select_unix"];
    $today = $_POST["today"];
    for ($i=0; $i<count($device_id); $i++){
      if ($device_id[$i] != -1){
        $img_dir = "/home/users/1/littlestar.jp-ezaki-lab/web/2022DCON/images/hunelog_imgs/{$device_id[$i]}/{$today}/";
        // print_r($img_dir);
        $img_list = glob($img_dir . "*.jpeg");
        $ten_min_list = selectImg_temp($img_list, $img_dir, 10, $select_unix); //10分 or 15分間の画像のリスト
        $json[$i]["data"] = $ten_min_list;
      }
    }
  }
  header('Content-Type: application/json'); // apiにしますよーってやつ
  $json = json_encode($json);
  print_r ($json);
  ?>
