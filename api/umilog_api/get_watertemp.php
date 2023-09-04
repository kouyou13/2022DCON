<?php
  ini_set('display_errors', "On");
  header('Content-Type: application/json'); // apiにしますよーってやつ
  include "./../db_config.php";
  include "./umilog_util.php";
  // include "./../hunelog_api/hunelog_util.php";

  $top_water_list = []; //表層水温の配列
  $middle_water_list = []; //中層水温の配列
  $bottom_water_list = []; //深層水温の配列
  $time_list = []; //取得時間の配列
  $data = array();
  $device_id = 3071; //デバイスid(決めうち)

  $token = getUmilogAccessToken(); //トークンを取得
  $today = date('Y-m-d');
  $url = "https://umilog.cloud/web/api/sensor_logs?device_id={$device_id}&sensor_grp=water&sensor_key=top&arrived_at[EQGREAT]={$today}%2000:00&arrived_at[EQSMALL]={$today}%2023:59";
  // print_r($url);
  // $json = requestUmilogAPI($url, $token);
  $json = requestUmilogAPI($url, $token);

  $json = json_decode($json, true);
  for ($i=0; $i<count($json["json"]); $i++){
    array_push($top_water_list, $json["json"][$i]["sensor_val"]);
    $datetime = new DateTime($json["json"][$i]["arrived_at"]);
    // print_r($datetime->format('U'));
    array_push($time_list,$datetime->format('U'));
  }

  $url = "https://umilog.cloud/web/api/sensor_logs?device_id={$device_id}&sensor_grp=water&sensor_key=middle&arrived_at[EQGREAT]={$today}%2000:00&arrived_at[EQSMALL]={$today}%2023:59";
  // print_r($url);
  $json = requestUmilogAPI($url, $token);

  $json = json_decode($json, true);
  for ($i=0; $i<count($json["json"]); $i++){
    array_push($middle_water_list, $json["json"][$i]["sensor_val"]);
  }

  $url = "https://umilog.cloud/web/api/sensor_logs?device_id={$device_id}&sensor_grp=water&sensor_key=bottom&arrived_at[EQGREAT]={$today}%2000:00&arrived_at[EQSMALL]={$today}%2023:59";
  // print_r($url);
  $json = requestUmilogAPI($url, $token);

  $json = json_decode($json, true);
  for ($i=0; $i<count($json["json"]); $i++){
    array_push($bottom_water_list, $json["json"][$i]["sensor_val"]);
  }
  $now = date('Y-m-d H:i:s');

  $data["top"] = $top_water_list;
  $data["middle"] = $middle_water_list;
  $data["bottom"] = $bottom_water_list;
  $data["time"] = $time_list;
  $data["last_access_time"] = $now;

  $data = json_encode($data);
  print_r ($data);
?>