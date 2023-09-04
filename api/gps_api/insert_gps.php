<?php

    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $mac = $_POST['mac'];

    // $lat = 1;
    // $lng= 1;
    // $mac = 'test';

    date_default_timezone_set('Asia/Tokyo');
    $datetime = date("Y-m-d H:i:s");
    $yesterday = date("Y-m-d H:i:s",strtotime("-1 day"));
    $device_id = 3002;

    include './../db_config.php';

	try {
    // connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SELECT * FROM mac_address WHERE mac='{$mac}'
    $stmt = $db->query("SELECT id FROM mac_address WHERE mac='{$mac}'");
    $mac_address_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $mac_id = $mac_address_id[0]["id"];
    if (count($mac_address_id)==0) {
        $db->exec("INSERT INTO mac_address (mac) values ('{$mac}')");
    }

    // insert
    if ($lat != 0 and $lng != 0)
        $db->exec("INSERT INTO gps_data (device_id, lat, lng, mac, date) values ({$device_id}, {$lat}, {$lng}, {$mac_id}, '{$datetime}')");
        // $db->exec("DELETE FROM gps_data WHERE date<'{$yesterday}'");
    // データベースから切断
    $db = null;
    
    echo "日付 ： {$datetime} \n";
    echo "緯度 ： {$lat} \n";
    echo "経度 ： {$lng} \n";
    echo "macアドレス ： {$mac} \n";

} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
	}
?>