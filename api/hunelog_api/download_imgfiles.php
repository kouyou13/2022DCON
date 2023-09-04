<?php
ini_set('display_errors', "On");
// 1時間分の画像を取得して保存，それ以前の画像は削除するapi

/* ---------------------------------------------------------------------------
   * 本日分のうみろぐ画像をダウンロードしてロリポップ内に保存する処理
   * -------------------------------------------------------------------------*/

include "./../db_config.php";
include "./hunelog_util.php";
define("SUFFIX", ".jpeg");

// 取得したいデバイスのid
# ふねログの水中カメラ，水上カメラ，早田の水中カメラ(非稼働)，早田の水上カメラ(カメラ非稼働), ナカトの水上カメラ, アジロの水中カメラ1, アジロの水中カメラ2(カメラ非稼働), ナカトの水中カメラ
$devices = [3001, 3002, 3004, 3068, 3113, 3071, 3072, 3067];
$pass_list = ["ohune3", "hidaohune3", "qqqq1111"];


// アクセストークンを取得しておく
$GATE_hunelog_token = getHunelogAccessToken($pass_list[0]); # GATEのトークン
$hida_hunelog_token = getHunelogAccessToken($pass_list[1]); # 早田のトークン
$umilog_token = getUmilogAccessToken($pass_list[2]); # うみログのトークン
// 本日の日付
$today_date = date("Y-m-d");
// echo $today_date . "<br>";
// 10分前のユニックス
$date = new DateTime(); # 今日の日付
$day_ago = date('Y-m-d', strtotime('-1 day'));
$ten_min_ago_unix = $date->modify("-10 minute")->format("U");
$hour_ago_unix = $date->modify("-1 hour")->format("U"); // 1時間前

// 本日分の画像をダウンロードする ------------------------------------------------
for ($i = 0; $i < count($devices); $i++) {
  // $device_id = $devices[$i]["device_id"];
  $device_id = $devices[$i];
  // if ($device_id != 3004){
  //   $token = $GATE_hunelog_token;
  // }
  // else{
  //   $token = $hida_hunelog_token;
  // }
  // selectImage($device_id, $today_date, $day_ago, $token, $ten_min_ago_unix, $hour_ago_unix);
  if ($device_id == 3004)
    $token = $hida_hunelog_token;

  else if($device_id == 3001 or $device_id == 3002)
    $token = $GATE_hunelog_token;
  
  else
    $token = $umilog_token;
  
  selectImage($device_id, $today_date, $day_ago, $token, $ten_min_ago_unix, $hour_ago_unix);
  
  if($device_id == 3004 or $device_id == 3113){
    coverage($device_id, $token);
  }
  echo "{$device_id} ok<br>";
}

print_r("<br>done!");
// =============================================================================




/* -----------------------------------------------------------------------------
 * 画像をダウンロードしてファイルに保存する処理
 *  $unix_time: 保存するファイル名はunixtimeとする
 * ---------------------------------------------------------------------------*/
function selectImage($device_id, $today_date, $day_ago, $token, $ten_min_ago_unix, $hour_ago_unix){

  // 画像リストを取得する
  if ($device_id == 3001 or $device_id == 3002 or $device_id == 3004)
    $request_url = "https://video.umilog.cloud/web/api/rapid-image-list/{$device_id}/{$today_date}";
  else
    $request_url = "https://umilog.cloud/web/api/rapid-image-list/{$device_id}/{$today_date}";
    
  $ret = requestHunelogAPI($request_url, $token);
  $ret = json_decode($ret, true);
  $ret = $ret["json"];
  if(count($ret) != 0){
    $umilog_img_list = array_map("datetimeToUnixtime", $ret); // 日時リストをunix time に変換する
    // 日時リストから10分前のみを残す
    $download_img_list = array_filter($umilog_img_list, function($val) use($ten_min_ago_unix) {
      return $val > $ten_min_ago_unix;
    });
    // 画像をダウンロードする
    foreach($download_img_list as $key => $value){
      $unix_time = $value;
      saveImage($device_id, $today_date, $unix_time, $token);
    }
  }
  else{
    echo 'ダウンロードするファイルがありません<br>';
  }

  // 本日分のデータでロリポップにダウンロードしてある画像一覧を取得する
  $save_dir = "/home/users/1/littlestar.jp-ezaki-lab/web/2022DCON/images/hunelog_imgs/{$device_id}/";
  $leave_dir_today = "{$save_dir}{$today_date}"; //残すディレクトリ
  $leave_dir_day_ago = "{$save_dir}{$day_ago}"; //残すディレクトリ
  // デバイスごとの保存ディレクトリ一覧を取得する
  $dir_list = glob($save_dir . "*", GLOB_ONLYDIR);

  // print_r($dir_list);
  // 今日以外のディレクトリは削除
  for ($i=0; $i<count($dir_list);$i++){
    // 3/20と4/26は取材用として例外とする
    if ($dir_list[$i] != $leave_dir_today and $dir_list[$i] != $leave_dir_day_ago and $dir_list[$i] != "{$save_dir}2022-03-20" and $dir_list[$i] != "{$save_dir}2022-04-26"){
      deleteFile($dir_list[$i]);
    }
  }
  
  // 1時間以内のみ残す（今日のディレクトリ内のみ）
  $save_dir = "/home/users/1/littlestar.jp-ezaki-lab/web/2022DCON/images/hunelog_imgs/{$device_id}/{$today_date}/";
  $ret = glob($save_dir . "*" . SUFFIX);
  $lolipop_img_list = array_map("getFileNameFromPath", $ret); //ファイル名の部分だけを取得
  for ($i=0;$i<count($lolipop_img_list);$i++){
    if ((int)$lolipop_img_list[$i] < (int)$hour_ago_unix){
      $delete_file_path = "{$save_dir}{$lolipop_img_list[$i]}.jpeg";
      deleteFile($delete_file_path);
    }
  }
}


function saveImage($device_id, $date, $unix_time, $token){
  $save_dir = "/home/users/1/littlestar.jp-ezaki-lab/web/2022DCON/images/hunelog_imgs/{$device_id}/{$date}/";
  $save_file = $unix_time . SUFFIX;

  // 保存ディレクトリの存在確認と作成 -------------
  if (!file_exists($save_dir)) {
    if (mkdir($save_dir, 0777, TRUE)) {
      //作成に成功した時
    } else {
      echo "false";
      // return FALSE;
    }
  }


  // 画像のダウンロード ------------------------
  $date_time = date("Y-m-d%20H:i:s", $unix_time);
  if ($device_id == 3001 or $device_id == 3002 or $device_id == 3004)
    $request_url = "https://video.umilog.cloud/web/api/rapid-image/{$device_id}/{$date_time}";
  else
    $request_url = "https://umilog.cloud/web/api/rapid-image/{$device_id}/{$date_time}";
  $ret = requestHunelogAPI($request_url, $token);

  // print_r("save: " . $save_file . "<br>");
  // ファイルに保存 ---------------------------
  $fp = fopen($save_dir . $save_file, 'wb');
  fwrite($fp, $ret);
  fclose($fp);
}


// 指定されたロリポップのファイル（フォルダ）を削除
function deleteFile($delete_file_path){
  // 削除基準日より古いデータの場合は削除する
  $command = "rm -rf " . realpath($delete_file_path);
  // echo $command."<br>";
  exec($command); // 削除コマンドの実行
}


/* -----------------------------------------------------------------------------
 * ファイルのパスからファイル名のみを取り出す処理
 * ---------------------------------------------------------------------------*/
function getFileNameFromPath($path){
  return basename($path, SUFFIX);
}


/* -----------------------------------------------------------------------------
 * Y-m-d H:i:s 形式から Unixtime に変換する処理
 * ---------------------------------------------------------------------------*/
function datetimeToUnixtime($date){
  $tmp = new DateTime($date, new DateTimeZone('Asia/Tokyo'));
  return $tmp->format("U");
}

// 取材用
function coverage($device_id, $token) {
  $day_list = ['2022-03-20', '2022-04-26'];
  $now_time = date('H:i:s');
  $ten_min_ago_time = date('H:i:s', strtotime('-10 minute'));
  for($i=0; $i<count($day_list); $i++){
    $today_date = $day_list[$i];
    $now_unix = strtotime($today_date . ' ' . $now_time);
    $ten_min_ago_unix = strtotime($today_date . ' ' . $ten_min_ago_time);
    // 画像リストを取得する
    if ($device_id == 3001 or $device_id == 3002 or $device_id == 3004)
      $request_url = "https://video.umilog.cloud/web/api/rapid-image-list/{$device_id}/{$today_date}";
    else
      $request_url = "https://umilog.cloud/web/api/rapid-image-list/{$device_id}/{$today_date}";
      
    $ret = requestHunelogAPI($request_url, $token);
    $ret = json_decode($ret, true);
    $ret = $ret["json"];
    if(count($ret) != 0){
      $umilog_img_list = array_map("datetimeToUnixtime", $ret); // 日時リストをunix time に変換する
      // 日時リストから10分前のみを残す
      $download_img_list = array_filter($umilog_img_list, function($val) use($ten_min_ago_unix, $now_unix) {
        return $val > $ten_min_ago_unix and $val < $now_unix;
      });
      // 画像をダウンロードする
      foreach($download_img_list as $key => $value){
        $unix_time = $value;
        saveImage($device_id, $today_date, $unix_time, $token);
      }
    }
    else{
      echo 'ダウンロードするファイルがありません<br>';
    }
  }
}