<?php

/* ------------------------------
 * umilog へのアクセストークンを取得する処理
 * ------------------------------*/
function getUmilogAccessToken() {
  $authentication_url = "https://umilog.cloud/web/api/authenticate";
  $username = 'qqqq1111';
  $password = 'qqqq1111';

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
  * umilog API へリクエストを送る
  * ------------------------------*/
  function requestUmilogAPI($url, $access_token){
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