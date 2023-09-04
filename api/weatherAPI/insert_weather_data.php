<?php
// 10分おきに天気apiからデータ取得してDBに保存するapi
include '../db_config.php';   // データベース接続のための設定ファイル 
ini_set('display_errors', "On");
$get_weather_data_encode = file_get_contents("http://api.openweathermap.org/data/2.5/weather?zip=519-4204,jp&units=metric&appid=4633ed2abae9c3b6b5d6d4312e955655");
$get_weather_data = json_decode($get_weather_data_encode, "true");
// var_dump($get_weather_data);
// $temp = round($get_weather_data["main"]["temp"], 1);
$deg = $get_weather_data["wind"]["deg"];
$speed = round($get_weather_data["wind"]["speed"], 1);
$weather = $get_weather_data["weather"][0]["description"];
$dt = $get_weather_data["dt"];

echo "風向：" . $deg . "<br>";
echo "風速：" . $speed . "<br>";
echo "天気：" . $weather . "<br>";
echo "日付：" . $dt . "<br>";

$date = date("Y-m-d H:i:s" , $dt);

$date_1_days_ago = date("Y-m-d H:i:s", strtotime("-1 day"));
echo "1日前：" . $date_1_days_ago . "<br>";
$device_id = 3001;

try
{
  // データベースに接続
  $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // ここにデータベースからデータを取り出したり・挿入・編集・削除などの処理を行う
  $db->exec("INSERT INTO `weather_data`(`device_id`,`wind_deg`, `wind_velocity`, `weather`, `datetime`) VALUES ({$device_id},{$deg},{$speed},'{$weather}','{$date}')");

  // 2日前の温度データを削除  
  $db->exec("DELETE FROM `weather_data` WHERE `datetime` < '{$date_1_days_ago} 23:59:59'");

  // データベースから切断
  $db = null;
}
catch(PDOException $e)
{
  // 何かエラーが出たときはここに処理が流れてくる
  echo $e->getMessage();
  exit;
}


?>