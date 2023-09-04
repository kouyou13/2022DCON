<?php
  // 検出した画像の情報をDBに保存
  $device_id = $_GET['device_id'];
  $genre = $_GET["genre"];
  $unixtime = $_GET['unixtime'];
  
  // $device_id = 1;
  // $genre = "fish";
  // $unixtime= 0;
  $datetime = date("Y-m-d H:i:s", $unixtime);

  date_default_timezone_set('Asia/Tokyo');

  include './../db_config.php';

	try {
    // connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("SELECT id FROM genre WHERE genre='{$genre}'");
    $genre_id = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // insert
    $db->exec("INSERT INTO detection_img (device_id, genre_id, datetime) values ({$device_id}, {$genre_id[0]['id']}, '{$datetime}')");
    // データベースから切断
    $db = null;
    
    echo "device_id： {$device_id} \n";
    echo "genre {$genre} \n";
    echo "unixtime ： {$unixtime} \n";

} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
	}
?>