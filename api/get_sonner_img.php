<?php
  ini_set('display_errors', "On");

  // 取得したいデバイスのid
  $today = date("Y-m-d");
  $json = array();
  $device_json = array();

  
  $json[] = $device_json;
  $img_dir = "/home/users/1/littlestar.jp-ezaki-lab/web/2022DCON/sonner_images/{$today}/";
  $img_list = glob($img_dir . "*.jpg");
  if (count($img_list) == 0){
    $today = date('Y-m-d', strtotime('-1 day'));
    $img_dir = "/home/users/1/littlestar.jp-ezaki-lab/web/2022DCON/sonner_images/{$today}/";
    $img_list = glob($img_dir . "*.jpg");
  }
  $img_list;
  
  header('Content-Type: application/json'); // apiにしますよーってやつ
  $json = json_encode($img_list);
  print_r ($json);
?>
