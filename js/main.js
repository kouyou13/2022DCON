"use strict";

import header from "./header.js";
import sethunelogImg from "./set_hunelog_img.js";
import SetWeatherData from "./weather.js";
import detection_time_slider from "./plot.js";
import plotChart from "./chart.js";
import reload from "./reload.js";
import Corverage from "./../coverage/coverage.js";
import temp_chart from "./temp_chart.js";

let parameter = {};
let device_list = [-1, -1]; // はじめが水中カメラのdevice_id，後ろが水上カメラのdevice_id
let place_neme;

const device_POST = (parameter) => {
  return $.ajax({
    url: "./api/get_device_id.php",
    type: "POST",
    data: parameter,
    cache: false
  });
}

const main = (user_id) => {
  parameter = {"user_id" : user_id};
  device_POST(parameter).then(function(device_data) {
    place_neme = device_data[0]["name"];

    for (let i=0; i<device_data.length; i++){
      if (device_data[i]["up_down"] == 0 || device_data[i]["up_down"] == 1)
        device_list[device_data[i]["up_down"]] = device_data[i]["device_id"];
    }

    // 月日のパラメーターがあったら取材用の関数を実行
    if (params.has("month") && params.has("day") && params.has("hour")){
      Corverage(params.get("month"), params.get("day"), params.get("hour"), place_neme, device_list);
      SetWeatherData(device_list);
      const next_day_input = document.getElementById("next_day");
      next_day_input.onclick = function(){
        window.location.href = 'https://ezaki-lab.littlestar.jp/2022DCON/index.html?user_id=2&month=3&day=20&hour=8';
      }
    }

    // 通常の関数を実行
    else{
      header(place_neme);
      sethunelogImg(device_list);
      SetWeatherData(device_list);
      detection_time_slider(device_list);
      // plotChart();
      temp_chart();
      reload();
    }

  });
}

// URLを取得
const url = new URL(window.location.href);

// URLSearchParamsオブジェクトを取得
const params = url.searchParams;

const user_id = params.get("user_id");
if (user_id != null)
  main(user_id);