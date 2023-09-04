let marker = [];
let map;
// URLを取得
const url = new URL(window.location.href);
// URLSearchParamsオブジェクトを取得
const params = url.searchParams;
const user_id = params.get("user_id");
let parameter = {};
let lat = -1;
let lng = -1;
if (user_id == 1){ // アジロの座標
  lat = 33.938047597243;
  lng = 136.2103115312378;
}
else if (user_id == 2){ // 早田の座標
  lat = 33.99632719063642;
  lng = 136.26145947256194;
}
else{ // 鳥羽商船の座標
  lat = 34.48226722584333;
  lng = 136.82501367512108
}


const gpsPOST = (parameter) => {
  return $.ajax({
    url: "./api/gps_api/get_gps.php",
    type: "POST",
    data: parameter,
    cache: false
  });
}


const selectPlotImg = (now_unix, date) => {
  let img = "";
  const target_unix = Math.floor(Date.parse(date) / 1000);
  // console.log(target_unix);
  const unix_diff = now_unix - target_unix;

  if (unix_diff < 3600) // 1時間前まで
    return "./images/point_imgs/point1.png";
  else if (unix_diff < 3600 * 2) // 2時間前まで
    return "./images/point_imgs/point2.png";
  else if (unix_diff < 3600 * 3) // 3時間前まで
    return "./images/point_imgs/point3.png";
  else if (unix_diff < 3600 * 4) // 4時間前まで
    return "./images/point_imgs/point4.png";
  else // 5時間前まで
    return "./images/point_imgs/point5.png";
}

//Googleマップを生成
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 16, //マップの初期縮尺
    center: {
      //マップの初期位置
      lat: lat,
      lng: lng
    }, 
    mapTypeControl: false, // 地図タイプの変更をできなくする
    disableDefaultUI: true, // UI(拡大ボタンとか)を消す
    keyboardShortcuts:false, // ショートカットを使えないようにする（表示を消したかっただけ）
    mapTypeId: google.maps.MapTypeId.HYBRID //マップのタイプ、今は航空写真
  });

  // 地図の地名を消す処理
  const styleOptions = [{
    featureType: "poi",
    elementType: "labels",
    stylers: [
      { visibility: "off" }
    ]}
  ];
  map.setOptions({styles: styleOptions});

  // URLを取得
  const url = new URL(window.location.href);
  // URLSearchParamsオブジェクトを取得
  const params = url.searchParams;
  if(params.has("month") && params.get("month") == 4){
    parameter = {"date":"2022-05-18"};
  }
  gpsPOST(parameter).then(function(data) {
    const gps_data = data;
    // console.log(gps_data);
    if (gps_data.length != null) {
      draw_func(gps_data);
    }
  });
}

const draw_func = (gps_data) => {
  const date = new Date();
  const temp = date.getTime();
  const now_unix = Math.floor(temp / 1000);
  const departure_time_ele = document.getElementById("departure_time");

  // img = "./images/point_imgs/point1.png";
  if (gps_data.length != 0){
    departure_time_ele.textContent = gps_data[0]["date"].substr(11, 5) + "に出航";
    for (let i = 0; i < gps_data.length; i++) {
      lat = parseFloat(gps_data[i]['lat']);
      lng = parseFloat(gps_data[i]['lng']);
      img = selectPlotImg(now_unix, gps_data[i]["date"]);
  
      // 座標の表示
      markerLatLng = new google.maps.LatLng({
        lat: lat,
        lng: lng
      }); // 緯度経度のデータ作成
      marker[i] = new google.maps.Marker({ // マーカーの追加
        position: markerLatLng, // マーカーを立てる位置を指定
        map: map, // マーカーを立てる地図を指定
        icon: img,
      });
    }
  }
  else{
    departure_time_ele.textContent = "まだ出航していません";
  }
}
