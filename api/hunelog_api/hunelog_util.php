<?php

/* ------------------------------
 * hunelog へのアクセストークンを取得する処理
 * ------------------------------*/
function getHunelogAccessToken($pass) {
  $authentication_url = "https://video.umilog.cloud/web/api/authenticate";
  $username = $pass;
  $password = $pass;

   //username,passwordを送信
  $data = [
    'username' => $username,
    'password' => $password
  ];

  $header = [
    'Content-Type: application/json'
  ];

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $authentication_url);//url
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); // post
  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // jsonデータを送信
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // リクエストにヘッダーを含める
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);

   // curlのエラー確認
  if (curl_errno($curl)) {
    $error = curl_error($curl);
    echo $error;
  }
  curl_close($curl);


   $authentication = json_decode($response, true); //jsonを配列に変換
   return $authentication["token"]; //トークンを取得
}

/* ------------------------------
 * umilog へのアクセストークンを取得する処理
 * ------------------------------*/
function getUmilogAccessToken($pass) {
  $authentication_url = "https://umilog.cloud/web/api/authenticate";
  $username = $pass;
  $password = $pass;

   //username,passwordを送信
  $data = [
    'username' => $username,
    'password' => $password
  ];

  $header = [
    'Content-Type: application/json'
  ];

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $authentication_url);//url
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); // post
  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // jsonデータを送信
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // リクエストにヘッダーを含める
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($curl);

   // curlのエラー確認
  if (curl_errno($curl)) {
    $error = curl_error($curl);
    echo $error;
  }
  curl_close($curl);


   $authentication = json_decode($response, true); //jsonを配列に変換
   return $authentication["token"]; //トークンを取得
}


/* ------------------------------
  * hunelogAPI, umilogAPI へリクエストを送る
  * ------------------------------*/
  function requestHunelogAPI($url, $access_token){
    $header = [
        'Authorization: Bearer '.$access_token,  // 前準備で取得したtokenをヘッダに含める
        'Content-Type: application/json',
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);//url
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // post
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // リクエストにヘッダーを含める
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);

    // curlのエラー確認
    if (curl_errno($curl)) {
      $error = curl_error($curl);
      //echo $error;
      return -1;
    }
    curl_close($curl);

    return $response;
  }
