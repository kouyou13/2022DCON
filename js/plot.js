"use strict";
let device_id;
let detection_plot_list = [];
let genre_plot_list = [];
const level_overview_list = [
  "あまり獲れない",
  "獲れる！",
  "たくさん獲れる！！",
];
const level_overview_imgs = [
  "./images/fish_prediction_img/1.png",
  "./images/fish_prediction_img/2.png",
  "./images/fish_prediction_img/3.png",
]

const detection_plot_time_ele = document.getElementById("detection_plot_time");
const time_marker_ele = document.getElementById("time_marker");
const time_split_ele = document.getElementById("time_split");
const in_net_prediction_img_ele = document.getElementById("in_net_prediction_img");
const evaluation_overview = document.getElementById("evaluation_overview");
const in_net_status_message = document.getElementById("in_net_status_message");

const detection_img_POST = (parameter) => {
  return $.ajax({
    url: "./api/hunelog_api/get_detection_img_plot.php",
    type: "POST",
    data: parameter,
    cache: false
  });
}

const getNetPrediction = () => {
  return $.ajax({
    url: "./api/in_net_prediction/get_in_net_prediction.php",
    type: "POST",
    // data: parameter,
    cache: false
  });
}

// 今日の開始時と終了時のユニックスを取得
const getUnixTime = () => {
  const now = new Date();
  const year = now.getFullYear();
  const month = now.getMonth()+1;
  const date = now.getDate();

  let today_start = new Date( year, month-1, date, 0, 0, 0); // 今日の0時のユニックス
  let today_finish = new Date( year, month-1, date, 23,59, 59); // 今日の23時のユニックス
  const today_start_unix = Math.floor(today_start.getTime() / 1000);
  const today_finish_unix = Math.floor(today_finish.getTime() / 1000);

  return [today_start_unix, today_finish_unix];
}

// ユニックスタイムを文字列の日付に変換（日付のみ）
const UnixToDatetimeString = (unixtime) => {
  const dateTime = new Date(unixtime * 1000);
  const year = dateTime.getFullYear();
  let month = dateTime.getMonth()+1;
  let date =  dateTime.getDate();
  let hour = dateTime.getHours();
  let min = dateTime.getMinutes();
  let sec = dateTime.getSeconds();
  month = ( '00' + month ).slice( -2 );
  date = ( '00' + date ).slice( -2 );
  hour = ( '00' + hour ).slice( -2 );
  min = ( '00' + min ).slice( -2 );
  sec = ( '00' + sec ).slice( -2 );

  return `${year}-${month}-${date}`;
}

// プロット
const detection_plot = () => {
  let datetime;
  let temp;
  let datetime_count_list = [];
  let color_plot_list = [];
  // 何時何分だけ見て当てはまる箇所に赤い印をつける
  for (let i=0; i<detection_plot_list.length; i++){
    // console.log(list[i]);
    // console.log(detection_plot_list[i]);
    datetime = new Date(detection_plot_list[i]);
    temp = (datetime.getHours()) * 60 + datetime.getMinutes();
    // console.log(datetime);
    if (datetime_count_list.indexOf(temp) == -1){
      datetime_count_list.push(temp);
      color_plot_list.push(genre_plot_list[i]);
    }
  }

  let time_marker_child;
  for (let i=0; i<24*60; i++){
    time_marker_child = document.createElement("div");
    time_marker_child.style.width = "calc(24vw/24/60)";
    time_marker_child.style.height = "1vh";
    time_marker_child.style.margin = 0;
    time_marker_child.style.display = "flex";
    if (datetime_count_list.length != 0 && datetime_count_list.indexOf(i) != -1){
      if(color_plot_list[datetime_count_list.indexOf(i)] == 1)
        time_marker_child.style.backgroundColor = "rgba(255,0,0,0.8)";
      else if(color_plot_list[datetime_count_list.indexOf(i)] == 2)
        time_marker_child.style.backgroundColor = "rgba(0,0,255,0.8)";
    }
    time_marker_ele.appendChild(time_marker_child);
  }
}

// プロット部分の時間の列
const SetTimeRange = () => {
  let time_split_child;
  for (let i=0; i<24; i++){
    time_split_child = document.createElement("div");
    time_split_child.style.width = "calc(100%/24)";
    time_split_child.style.height = "2vh";
    time_split_child.style.display = "flex";
    time_split_child.style.margin = "auto";
    if (i%2 == 0){ //iが偶数
      time_split_child.className = "time_split_1";
      time_split_child.style.backgroundColor = "#555555";
      time_split_child.textContent = i;
      time_split_child.style.flexDirection = "column";
      time_split_child.style.justifyContent = "center";
      time_split_child.style.color = "#FFFFFF";
      time_split_child.style.fontSize = "x-small";
    }
    else{
      time_split_child.className = "time_split_2";
      time_split_child.style.backgroundColor = "#666666";
    }
    time_split_ele.appendChild(time_split_child);
  }
}


const detection_time_slider = (device_list) => {
  device_id = device_list[0];
  // device_id = 3071;
  let detection_plot_list_temp = []; //検出した時間の配列
  let genre_plot_list_temp = []; //魚か小魚の値の配列
  SetTimeRange();
  const unix_time_list = getUnixTime();
  const parameter = { "start_unix":unix_time_list[0], "finish_unix":unix_time_list[1], "device_id": device_id};
  // 検出のプロット
  detection_img_POST(parameter).then(function(detection_img_data) {
    const datetime_str = UnixToDatetimeString(unix_time_list[0]);
    detection_plot_time_ele.textContent = datetime_str;
    if (detection_img_data.length != 0){
      for (let i=0; i<detection_img_data.length; i++){
        detection_plot_list_temp.push(Number(Date.parse(detection_img_data[i]["datetime"])));
        genre_plot_list_temp.push(detection_img_data[i]["genre_id"]);
      }
    }
    detection_plot_list = detection_plot_list_temp;
    genre_plot_list = genre_plot_list_temp;
    
    detection_plot();
  });

  // 入網状況
  getNetPrediction().then(function(prediction_data) {
    const prediction_level =  prediction_data["prediction_level"];
    in_net_prediction_img_ele.src = level_overview_imgs[ prediction_level - 1 ];
    evaluation_overview.textContent = level_overview_list[ prediction_level - 1 ];
    const prediction_time = prediction_data["datetime"];
    in_net_status_message.textContent = "現在の入網状況： " + prediction_time.substr(11, 5) + "に予測"
  });
}

export default detection_time_slider;