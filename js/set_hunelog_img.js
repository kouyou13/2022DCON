"use strict";
let img_count_water_img = 0;
let img_count_mirine_img = 0;
let img_count = 0;
let img_url;
let unixtime_str;
let datetime;
let device_id_list = [-1, -1]; // 船ログの水中カメラ，水上カメラ
let parameter = {};

const GetHunelogImgData = (parameter) => {
  return $.ajax({
    url: "./api/get_hunelog_img_list.php",
    type: "POST",
    data: parameter,
    catch: false
  });
}


const UnixtimeToDatetime = (unixtime_str) => {
  datetime = new Date(parseInt(unixtime_str, 10) * 1000); // ユニックスタイムから時間に変更
  const year = datetime.getFullYear();
  let month = datetime.getMonth()+1;
  let date = datetime.getDate();
  let hour = datetime.getHours();
  let min = datetime.getMinutes();
  let sec = datetime.getSeconds();
  month = ( '00' + month ).slice( -2 );
  date = ( '00' + date ).slice( -2 );
  hour = ( '00' + hour ).slice( -2 );
  min = ( '00' + min ).slice( -2 );
  sec = ( '00' + sec ).slice( -2 );

  return `${year}-${month}-${date} ${hour}:${min}:${sec}`;
}


const setMarineImg = (img_list, device_list) => {
  const marine_image_ele = document.getElementById("marine_image");
  const underwater_image_ele = document.getElementById("underwater_image");
  const marine_image_time_ele = document.getElementById("marine_image_time");
  const underwater_image_time_ele = document.getElementById("underwater_image_time");
  for (let i=0; i<device_id_list.length; i++){
    if (img_list[i]["device_id"] != -1 && img_list[i]["data"].length != 0){
      if (img_list[i]["device_id"] == device_list[0])
        img_count = img_count_water_img;
      else if (img_list[i]["device_id"] == device_list[1])
        img_count = img_count_mirine_img;
      
      img_url = img_list[i]["data"][img_count].replace("/home/users/1/littlestar.jp-ezaki-lab/web/", "http://ezaki-lab.littlestar.jp/");
      unixtime_str = img_list[i]["data"][img_count].slice(87);
      unixtime_str = unixtime_str.replace(".jpeg", "");
      datetime = UnixtimeToDatetime(unixtime_str);
  
      // 水中カメラの画像セット
      if (img_list[i]["device_id"] == device_list[0]){
        underwater_image_ele.src = `./api/small_image.php?file_name=${img_url}`;
        underwater_image_time_ele.textContent = datetime;
        if (img_count == img_list[i]["data"].length-1)
          img_count_water_img = 0;
        else
          img_count_water_img += 1;
      }
      // 水上カメラの画像セット
      else if (img_list[i]["device_id"] == device_list[1]){
        marine_image_ele.src = `./api/small_image.php?file_name=${img_url}`;
        marine_image_time_ele.textContent = datetime;
        if (img_count == img_list[i]["data"].length-1)
          img_count_mirine_img = 0;
        else
          img_count_mirine_img += 1;
          // location.reload(); // 画像が1周したらリロード
      }
    }
    
  }
}


const sethunelogImg = (device_list) => {
  parameter = {"water_device_id" : device_list[0], "marine_device_id" : device_list[1]};
  GetHunelogImgData(parameter).then(function (hunelog_img_data) {
    device_id_list = device_list;
    // console.log(hunelog_img_data);
    // if (hunelog_img_data[0]["data"].length != 0 && hunelog_img_data[1]["data"].length != 0){
      setMarineImg(hunelog_img_data, device_list);
      setInterval(function(){setMarineImg(hunelog_img_data, device_list)}, 5000); // 5秒ごとに変わる
    // }
  });
}

export default sethunelogImg;